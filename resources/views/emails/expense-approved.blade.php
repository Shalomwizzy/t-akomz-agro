<x-emails.layout subject="Your Expense Request Has Been Approved">

    <div class="email-icon-wrap" style="background: #F0FBE8;">
        <span style="font-size: 30px;">✅</span>
    </div>

    <h1 class="email-title" style="text-align: center;">Expense Request Approved</h1>
    <p class="email-subtitle" style="text-align: center;">
        Great news! Your expense request has been reviewed and approved.
        You can now proceed with the purchase and confirm spending once done.
    </p>

    <div class="alert-box-green">
        <p class="alert-title" style="color: #065F46;">Approved by {{ $transaction->approver->name ?? 'Administrator' }}</p>
        <p class="alert-text">
            Once you complete the purchase, log into the admin panel to confirm spending and
            optionally upload a receipt to complete the record.
        </p>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Request Title</span>
            <span class="info-value">{{ $transaction->short_title }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Category</span>
            <span class="info-value">{{ ucfirst($transaction->category) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Approved Amount</span>
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
            <span class="info-label">Status</span>
            <span class="info-value" style="color: #2E7A10; font-weight: 700;">APPROVED</span>
        </div>
    </div>

    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/admin/finance/expenses/{{ $transaction->id }}" class="cta-btn-green">
            Confirm Spending
        </a>
    </div>

    <hr class="divider">

    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        This is an automated alert from the T-Akomz Agro Estates finance system.<br>
        If you have questions, contact your administrator.
    </p>

</x-emails.layout>
