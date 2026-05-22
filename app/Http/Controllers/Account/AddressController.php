<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->get();
        return view('account.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'      => ['required', 'string', 'max:50'],
            'phone'      => ['required', 'string', 'max:20'],
            'street'     => ['required', 'string', 'max:255'],
            'city'       => ['required', 'string', 'max:100'],
            'state'      => ['required', 'string'],
        ]);

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        auth()->user()->addresses()->create([
            'label'         => $request->label,
            'full_name'     => auth()->user()->name,
            'phone'         => $request->phone,
            'address_line1' => $request->street,
            'city'          => $request->city,
            'state'         => $request->state,
            'is_default'    => $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Address saved successfully.');
    }

    public function update(Request $request, Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);

        $request->validate([
            'label'  => ['required', 'string', 'max:50'],
            'phone'  => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'city'   => ['required', 'string', 'max:100'],
            'state'  => ['required', 'string'],
        ]);

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        $address->update([
            'label'         => $request->label,
            'phone'         => $request->phone,
            'address_line1' => $request->street,
            'city'          => $request->city,
            'state'         => $request->state,
            'is_default'    => $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $address->delete();
        return back()->with('success', 'Address removed.');
    }

    public function setDefault(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return back()->with('success', 'Default address updated.');
    }
}
