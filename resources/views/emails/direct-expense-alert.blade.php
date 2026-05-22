<x-emails.layout subject="Direct Expense Logged — Immediate Wallet Deduction">

    <div class="email-icon-wrap" style="background: #EFF6FF;">
        <span style="font-size: 30px;">💸</span>
    </div>

    <h1 class="email-title" style="text-align: center;">Direct Expense Recorded</h1>
    <p class="email-subtitle" style="text-align: center;">
        A direct expense has been logged and the amount has been immediately deducted from the farm wallet.
        This is for your records.
    </p>

    <div class="highlight-box">
        <span class="amount">₦{{ number_format($transaction->amount, 2) }}</span>
        <span class="amount-label">Deducted from Farm Wallet</span>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Logged By</span>
            <span class="info-value">{{ $transaction->creator->name ?? 'Unknown' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Title</span>
            <span class="info-value">{{ $transaction->short_title }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Category</span>
            <span class="info-value">{{ ucfirst($transaction->category) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Amount</span>
            <span class="info-value green">₦{{ number_format($transaction->amount, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Expense Date</span>
            <span class="info-value">{{ $transaction->expense_date->format('d M Y') }}</span>
        </div>
        @if($transaction->vendor_name)
        <div class="info-row">
            <span class="info-label">Vendor</span>
            <span class="info-value">{{ $transaction->vendor_name }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Description</span>
            <span class="info-value" style="text-align: left; font-weight: 400; color: #444;">{{ $transaction->description }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Logged At</span>
            <span class="info-value">{{ $transaction->created_at->format('d M Y, g:i A') }}</span>
        </div>
    </div>

    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/admin/finance/expenses/{{ $transaction->id }}" class="cta-btn">
            View Expense Record
        </a>
    </div>

    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        This is an automated alert from the T-Akomz Agro Estates finance system.<br>
        This expense was logged directly and wallet has already been deducted.
    </p>

</x-emails.layout>
