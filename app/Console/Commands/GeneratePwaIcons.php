<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePwaIcons extends Command
{
    protected $signature   = 'pwa:icons';
    protected $description = 'Generate PWA icon PNGs from the brand palette';

    public function handle(): int
    {
        if (! function_exists('imagecreatetruecolor')) {
            $this->error('GD extension is not available. Install php-gd and try again.');
            return self::FAILURE;
        }

        $dir = public_path('images/icons');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach ([192, 512] as $size) {
            $this->info("Generating {$size}x{$size} icon...");
            $png = $this->generateIcon($size);
            file_put_contents("{$dir}/pwa-{$size}.png", $png);
            $this->info("  → public/images/icons/pwa-{$size}.png");
        }

        // Also generate 180x180 apple-touch-icon
        $png = $this->generateIcon(180);
        file_put_contents("{$dir}/apple-touch-icon.png", $png);
        $this->info('  → public/images/icons/apple-touch-icon.png');

        $this->info('Done! Run php artisan pwa:icons after changing brand assets.');
        return self::SUCCESS;
    }

    private function generateIcon(int $size): string
    {
        // ── Canvas ────────────────────────────────────────────────────────
        $img = imagecreatetruecolor($size, $size);
        imageantialias($img, true);
        imagesavealpha($img, true);

        // ── Colours ───────────────────────────────────────────────────────
        // Background  #050505
        $bg    = imagecolorallocate($img, 5, 5, 5);
        // Brand green #B8F397
        $green = imagecolorallocate($img, 184, 243, 151);
        // Slightly darker green for depth on the inner leaf veins
        $greenDark = imagecolorallocate($img, 111, 174, 75);

        // Fill background
        imagefill($img, 0, 0, $bg);

        // ── Scale & centre ────────────────────────────────────────────────
        // The SVG viewBox is 120 wide × 140 tall.
        // We scale to fit within the icon with 6 % padding on each side.
        $padding  = (int) round($size * 0.06);
        $drawSize = $size - 2 * $padding;

        // Scale factors for SVG coords → icon coords
        $sx = $drawSize / 120.0; // horizontal
        $sy = $drawSize / 140.0; // vertical

        // Translate SVG point to icon pixel
        $tx = static function (float $x) use ($padding, $sx): int {
            return (int) round($x * $sx + $padding);
        };
        $ty = static function (float $y) use ($padding, $sy): int {
            return (int) round($y * $sy + $padding);
        };

        // ── 1. Outer teardrop body ────────────────────────────────────────
        // The SVG path is: M60,4 → curves down to a circle centred ~(60,94)
        // with radius ≈ 44 in SVG units.
        //
        // Approach: build a polygon from arc points (bottom semicircle) +
        // tip point at the top, then fill it.

        $circCX  = 60.0;  // SVG circle centre X
        $circCY  = 94.0;  // SVG circle centre Y
        $circR   = 44.0;  // SVG circle radius
        $tipX    = 60.0;  // SVG tip X
        $tipY    =  4.0;  // SVG tip Y (top of teardrop)

        // Build polygon: tip → right arc (top-right down to bottom) →
        //                      left arc (bottom up to top-left) → tip
        $points  = [];
        $steps   = 80; // more steps → smoother curve

        // Right half: 270° (top) → 90° (bottom-right), i.e. going CW
        for ($i = 0; $i <= $steps; $i++) {
            $angleDeg = -90 + 180 * ($i / $steps); // -90 → +90
            $angleRad = deg2rad($angleDeg);
            $px = $circCX + $circR * cos($angleRad);
            $py = $circCY + $circR * sin($angleRad);
            $points[] = $tx($px);
            $points[] = $ty($py);
        }

        // Left half: 90° (bottom-right) → 270° (top), i.e. finishing arc
        for ($i = 0; $i <= $steps; $i++) {
            $angleDeg = 90 + 180 * ($i / $steps); // 90 → 270
            $angleRad = deg2rad($angleDeg);
            $px = $circCX + $circR * cos($angleRad);
            $py = $circCY + $circR * sin($angleRad);
            $points[] = $tx($px);
            $points[] = $ty($py);
        }

        // Tip point (top of the drop)
        $points[] = $tx($tipX);
        $points[] = $ty($tipY);

        $numPoints = count($points) / 2;
        imagefilledpolygon($img, $points, $numPoints, $green);

        // ── 2. Vertical stem ─────────────────────────────────────────────
        // SVG: line x1="60" y1="118" x2="60" y2="60"
        // Thickness ≈ 4.5 * sy but at minimum 2 px, carved in bg colour
        $stemThick = max(2, (int) round(4.5 * $sy));
        imagesetthickness($img, $stemThick);
        imageline(
            $img,
            $tx(60), $ty(60),
            $tx(60), $ty(118),
            $bg
        );
        imagesetthickness($img, 1);

        // ── 3. Left leaf vein ────────────────────────────────────────────
        // SVG: M60,95 C60,95 36,88 34,68 C32,52 48,46 58,56
        // Approximate with a bezier polygon (GD has no native bezier, so
        // we use line segments along a hand-sampled cubic curve).
        $veinThick = max(2, (int) round(3.5 * $sy));
        imagesetthickness($img, $veinThick);

        // Segment 1: M60,95 → cubic to 34,68 (control pts 60,95 and 36,88)
        $this->drawCubicBezier(
            $img, $bg,
            $tx(60), $ty(95),
            $tx(60), $ty(95),
            $tx(36), $ty(88),
            $tx(34), $ty(68),
            24
        );
        // Segment 2: continue cubic from 34,68 → 58,56 (ctrl 32,52 and 48,46)
        $this->drawCubicBezier(
            $img, $bg,
            $tx(34), $ty(68),
            $tx(32), $ty(52),
            $tx(48), $ty(46),
            $tx(58), $ty(56),
            24
        );

        // ── 4. Right leaf vein ───────────────────────────────────────────
        // SVG: M60,82 C60,82 84,76 86,56 C88,40 72,34 62,44
        $this->drawCubicBezier(
            $img, $bg,
            $tx(60), $ty(82),
            $tx(60), $ty(82),
            $tx(84), $ty(76),
            $tx(86), $ty(56),
            24
        );
        $this->drawCubicBezier(
            $img, $bg,
            $tx(86), $ty(56),
            $tx(88), $ty(40),
            $tx(72), $ty(34),
            $tx(62), $ty(44),
            24
        );

        imagesetthickness($img, 1);

        // ── 5. Bottom root arc ────────────────────────────────────────────
        // SVG: M30,132 Q60,145 90,132  (quadratic bezier)
        // Draw with bg colour (same as vein strategy — carve into body)
        $rootThick = max(2, (int) round(3.5 * $sy));
        imagesetthickness($img, $rootThick);
        $this->drawQuadBezier(
            $img, $bg,
            $tx(30), $ty(132),
            $tx(60), $ty(145),
            $tx(90), $ty(132),
            20
        );
        imagesetthickness($img, 1);

        // ── Output PNG ────────────────────────────────────────────────────
        ob_start();
        imagepng($img);
        $content = ob_get_clean();
        imagedestroy($img);

        return $content;
    }

    /**
     * Draw a cubic Bézier curve as line segments.
     *
     * @param resource|\GdImage $img
     * @param int $color
     */
    private function drawCubicBezier(
        $img,
        int $color,
        int $x0, int $y0,   // start
        int $cx0, int $cy0,  // control 1
        int $cx1, int $cy1,  // control 2
        int $x1, int $y1,    // end
        int $steps = 30
    ): void {
        $prevX = $x0;
        $prevY = $y0;

        for ($i = 1; $i <= $steps; $i++) {
            $t  = $i / $steps;
            $u  = 1 - $t;
            $px = (int) round(
                $u * $u * $u * $x0
                + 3 * $u * $u * $t * $cx0
                + 3 * $u * $t * $t * $cx1
                + $t * $t * $t * $x1
            );
            $py = (int) round(
                $u * $u * $u * $y0
                + 3 * $u * $u * $t * $cy0
                + 3 * $u * $t * $t * $cy1
                + $t * $t * $t * $y1
            );
            imageline($img, $prevX, $prevY, $px, $py, $color);
            $prevX = $px;
            $prevY = $py;
        }
    }

    /**
     * Draw a quadratic Bézier curve as line segments.
     *
     * @param resource|\GdImage $img
     * @param int $color
     */
    private function drawQuadBezier(
        $img,
        int $color,
        int $x0, int $y0,   // start
        int $cx, int $cy,    // control
        int $x1, int $y1,    // end
        int $steps = 20
    ): void {
        $prevX = $x0;
        $prevY = $y0;

        for ($i = 1; $i <= $steps; $i++) {
            $t  = $i / $steps;
            $u  = 1 - $t;
            $px = (int) round($u * $u * $x0 + 2 * $u * $t * $cx + $t * $t * $x1);
            $py = (int) round($u * $u * $y0 + 2 * $u * $t * $cy + $t * $t * $y1);
            imageline($img, $prevX, $prevY, $px, $py, $color);
            $prevX = $px;
            $prevY = $py;
        }
    }
}
