<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.promotions.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'            => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type'            => ['required', 'in:PERCENTAGE,FIXED,FREE_DELIVERY'],
            'value'           => ['nullable', 'numeric', 'min:0'],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_uses'        => ['nullable', 'integer', 'min:1'],
            'valid_from'      => ['nullable', 'date'],
            'valid_until'     => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_active'       => ['boolean'],
        ]);

        $data = [
            'code'            => strtoupper($validated['code']),
            'type'            => $validated['type'],
            'value'           => $validated['value'] ?? 0,
            'min_order_value' => $validated['min_order_value'] ?? null,
            'max_uses'        => $validated['max_uses'] ?? null,
            'valid_from'      => $validated['valid_from'] ?? now(),
            'valid_until'     => $validated['valid_until'] ?? now()->addYear(),
            'is_active'       => $request->boolean('is_active', true),
        ];

        Coupon::create($data);

        return back()->with('success', 'Coupon created.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return back()->with('success', 'Coupon ' . ($coupon->is_active ? 'deactivated' : 'activated') . '.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }
}
