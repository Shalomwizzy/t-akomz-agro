<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    public function __construct(
        private readonly ?string $category = null,
        private readonly ?bool   $active   = null,
    ) {}

    public function view(): View
    {
        $query = Product::with('category')->latest();

        if ($this->category) {
            $query->where('category_id', $this->category);
        }
        if ($this->active !== null) {
            $query->where('is_active', $this->active);
        }

        $products = $query->get();

        return view('exports.products', compact('products'));
    }

    public function title(): string
    {
        return 'Products';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '2D6A16']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
