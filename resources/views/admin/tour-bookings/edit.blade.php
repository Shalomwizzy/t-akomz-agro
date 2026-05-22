@extends('layouts.admin')
@section('page-title', 'Edit Booking')
@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.tour-bookings.index') }}" class="text-content-muted hover:text-content-primary transition-colors text-sm">← Bookings</a>
        <span class="text-content-muted">/</span>
        <span class="font-mono text-brand-green text-sm">{{ $tourBooking->reference }}</span>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl text-sm font-medium mb-6" style="background:rgba(184,243,151,0.08);border:1px solid rgba(184,243,151,0.2);color:#B8F397;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="p-4 rounded-xl text-sm text-red-400 mb-6" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('admin.tour-bookings.update', $tourBooking) }}" method="POST">
        @csrf @method('PUT')
        <div class="card p-6 space-y-5">
            <h1 class="font-display text-xl font-bold text-content-primary">Edit Tour Booking</h1>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $tourBooking->name) }}" class="input text-sm" required>
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $tourBooking->phone) }}" class="input text-sm" required>
                </div>
            </div>

            <div>
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email', $tourBooking->email) }}" class="input text-sm" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Package</label>
                    <select name="package" class="input text-sm" required>
                        @foreach(['individual'=>'Individual (₦5,000/person)','group'=>'Group (₦3,500/person)','corporate'=>'Corporate / School'] as $val=>$label)
                        <option value="{{ $val }}" {{ old('package',$tourBooking->package)===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Number of Persons</label>
                    <input type="number" name="persons" value="{{ old('persons', $tourBooking->persons) }}" min="1" class="input text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Preferred Date</label>
                    <input type="date" name="preferred_date" value="{{ old('preferred_date', $tourBooking->preferred_date?->format('Y-m-d')) }}" class="input text-sm" required>
                </div>
                <div>
                    <label class="label">Confirmed Date <span class="font-normal text-content-muted">(optional)</span></label>
                    <input type="date" name="confirmed_date" value="{{ old('confirmed_date', $tourBooking->confirmed_date?->format('Y-m-d')) }}" class="input text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Payment Status</label>
                    <select name="payment_status" class="input text-sm" required>
                        @foreach(['pending','paid','failed'] as $s)
                        <option value="{{ $s }}" {{ old('payment_status',$tourBooking->payment_status)===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Booking Status</label>
                    <select name="booking_status" class="input text-sm" required>
                        @foreach(['pending','approved','rejected','cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('booking_status',$tourBooking->booking_status)===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="label">Admin Note <span class="font-normal text-content-muted">(internal)</span></label>
                <textarea name="admin_note" rows="2" class="input resize-none text-sm">{{ old('admin_note', $tourBooking->admin_note) }}</textarea>
            </div>

            <div>
                <label class="label">Customer Notes</label>
                <textarea name="notes" rows="2" class="input resize-none text-sm">{{ old('notes', $tourBooking->notes) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary px-6 py-2.5 text-sm">Save Changes</button>
                <a href="{{ route('admin.tour-bookings.index') }}" class="btn-outline px-6 py-2.5 text-sm">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
