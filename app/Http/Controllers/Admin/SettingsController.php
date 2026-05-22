<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private array $settingKeys = [
        'delivery_fee_standard', 'delivery_fee_express', 'delivery_fee_pickup',
        'min_order_amount', 'contact_phone', 'contact_phone_2', 'contact_email',
        'contact_address', 'whatsapp_number', 'social_instagram', 'social_facebook',
        'social_twitter', 'social_youtube', 'banner_text', 'banner_active',
        'maintenance_mode', 'cod_allowed_states', 'bank_account_name',
        'bank_account_number', 'bank_name', 'google_maps_embed',
        'active_payment_gateway', 'bank_transfer_enabled', 'cod_enabled',
        'image_watermark', 'watermark_text',
        'paystack_public_key',
        'flutterwave_public_key',
        'gallery_title', 'gallery_subtitle', 'gallery_instagram_cta',
    ];

    public function index()
    {
        $settings = SiteSetting::whereIn('key', $this->settingKeys)->pluck('value', 'key')->toArray();

        // Pre-populate public payment keys from config/.env when not yet saved in DB
        foreach ([
            'paystack_public_key'    => config('services.paystack.public_key'),
            'flutterwave_public_key' => config('services.flutterwave.public_key'),
        ] as $key => $configValue) {
            if (empty($settings[$key]) && $configValue) {
                $settings[$key] = $configValue;
            }
        }

        return view('admin.settings.index', compact('settings'));
    }

    // Checkbox keys per section — only the active section's checkboxes get reset on partial save
    private array $sectionCheckboxKeys = [
        'announcements' => ['banner_active', 'maintenance_mode'],
        'bank-transfer' => ['bank_transfer_enabled', 'cod_enabled'],
        'images'        => ['image_watermark'],
    ];

    public function update(Request $request)
    {
        $section = $request->input('_section'); // null = full save (unused now, kept for safety)
        $activeCheckboxKeys = $section
            ? ($this->sectionCheckboxKeys[$section] ?? [])
            : array_merge(...array_values($this->sectionCheckboxKeys));

        foreach ($this->settingKeys as $key) {
            $isCheckbox = in_array($key, $activeCheckboxKeys);
            if ($isCheckbox) {
                // Only reset checkboxes that belong to the submitted section
                if ($section && !in_array($key, $activeCheckboxKeys)) continue;
                SiteSetting::set($key, $request->has($key) ? '1' : '0');
            } elseif ($request->has($key)) {
                SiteSetting::set($key, $request->input($key));
            }
        }

        // Handle About page image upload
        if ($section === 'about-images' && $request->hasFile('about_image_1_file') && $request->file('about_image_1_file')->isValid()) {
            $old = SiteSetting::get('about_image_1');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('about_image_1_file')->store('about', 'public');
            SiteSetting::set('about_image_1', $path);
        }

        Artisan::call('cache:clear');

        return back()->with('success', 'Settings saved.');
    }
}
