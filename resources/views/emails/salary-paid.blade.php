<x-emails.layout subject="Salary Payment Received">

    <div class="email-icon-wrap" style="background: #F0FBE8;">
        <span style="font-size: 30px;">💰</span>
    </div>

    <h1 class="email-title" style="text-align: center;">Your Salary Has Been Paid</h1>
    <p class="email-subtitle" style="text-align: center;">
        Hi {{ $payroll->worker->full_name }}, your salary for the period below has been processed.
        Please find your payment details below.
    </p>

    <div class="highlight-box">
        <span class="amount">₦{{ number_format($payroll->total_payable, 2) }}</span>
        <span class="amount-label">Net Salary Paid</span>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Worker Name</span>
            <span class="info-value">{{ $payroll->worker->full_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Role</span>
            <span class="info-value">{{ $payroll->worker->role }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Pay Period</span>
            <span class="info-value">{{ $payroll->period_label }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Base Salary</span>
            <span class="info-value">₦{{ number_format($payroll->base_salary, 2) }}</span>
        </div>
        @if($payroll->bonuses > 0)
        <div class="info-row">
            <span class="info-label">Bonuses</span>
            <span class="info-value" style="color: #2E7A10;">+ ₦{{ number_format($payroll->bonuses, 2) }}</span>
        </div>
        @endif
        @if($payroll->deductions > 0)
        <div class="info-row">
            <span class="info-label">Deductions</span>
            <span class="info-value" style="color: #B91C1C;">− ₦{{ number_format($payroll->deductions, 2) }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Net Amount Paid</span>
            <span class="info-value green">₦{{ number_format($payroll->total_payable, 2) }}</span>
        </div>
        @if($payroll->payment)
        <div class="info-row">
            <span class="info-label">Payment Method</span>
            <span class="info-value">{{ ucfirst(str_replace('_', ' ', $payroll->payment->payment_method)) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date Paid</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($payroll->payment->date_paid)->format('d M Y') }}</span>
        </div>
        @if($payroll->payment->reference)
        <div class="info-row">
            <span class="info-label">Reference</span>
            <span class="info-value mono">{{ $payroll->payment->reference }}</span>
        </div>
        @endif
        @endif
        @if($payroll->worker->bank_name)
        <div class="info-row">
            <span class="info-label">Bank</span>
            <span class="info-value">{{ $payroll->worker->bank_name }}</span>
        </div>
        @endif
        @if($payroll->worker->bank_account)
        <div class="info-row">
            <span class="info-label">Account Number</span>
            <span class="info-value mono">{{ $payroll->worker->bank_account }}</span>
        </div>
        @endif
    </div>

    @if($payroll->notes)
    <div class="alert-box-green">
        <p class="alert-title" style="color: #065F46;">Note from Administrator</p>
        <p class="alert-text">{{ $payroll->notes }}</p>
    </div>
    @endif

    <hr class="divider">

    <p style="font-size: 13px; color: #555; text-align: center; line-height: 1.7; margin: 0 0 16px;">
        If you believe there is an error in this payment, please contact the farm administrator immediately.
    </p>
    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        T-Akomz Agro Estates Ltd · Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria<br>
        Thank you for your service and hard work.
    </p>

</x-emails.layout>
