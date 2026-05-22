<x-emails.layout subject="Your Expense Request Was Declined">

    <div class="email-icon-wrap" style="background: #FEF2F2;">
        <span style="font-size: 30px;">❌</span>
    </div>

    <h1 class="email-title" style="text-align: center;">Expense Request Declined</h1>
    <p class="email-subtitle" style="text-align: center;">
        Your expense request has been reviewed and was not approved at this time.
        Please see the reason below and speak to your administrator if you have questions.
    </p>

    <div class="alert-box-red">
        <p class="alert-title" style="color: #B91C1C;">Reason for Decline</p>
        <p class="alert-text">{{ $transaction->rejection_reason ?? 'No reason provided. Please contact your administrator.' }}</p>
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
            <span class="info-label">Amount Requested</span>
            <span class="info-value">₦{{ number_format($transaction->amount, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Expense Date</span>
            <span class="info-value">{{ $transaction->expense_date->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Reviewed By</span>
            <span class="info-value">{{ $transaction->approver->name ?? 'Administrator' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value" style="color: #B91C1C; font-weight: 700;">DECLINED</span>
        </div>
    </div>

    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/admin/finance/expenses/{{ $transaction->id }}" class="cta-btn">
            View Request Details
        </a>
    </div>

    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        This is an automated alert from the T-Akomz Agro Estates finance system.<br>
        You may submit a revised request if appropriate.
    </p>

</x-emails.layout>
