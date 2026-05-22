<x-emails.layout subject="New Expense Request — Action Required">

    <div class="email-icon-wrap" style="background: #FFF7ED;">
        <span style="font-size: 30px;">📋</span>
    </div>

    <h1 class="email-title" style="text-align: center;">New Expense Request</h1>
    <p class="email-subtitle" style="text-align: center;">
        A manager has submitted a new expense request that requires your review and approval.
    </p>

    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">⏳ Pending Your Approval</p>
        <p class="alert-text">
            This request is currently in <strong>Pending</strong> status. Please review the details and
            approve or decline as soon as possible.
        </p>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Submitted By</span>
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
            <span class="info-label">Amount Requested</span>
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
            <span class="info-label">Submitted At</span>
            <span class="info-value">{{ $transaction->created_at->format('d M Y, g:i A') }}</span>
        </div>
    </div>

    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/admin/finance/expenses/{{ $transaction->id }}" class="cta-btn">
            Review &amp; Approve Request
        </a>
    </div>

    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        This is an automated alert from the T-Akomz Agro Estates finance system.<br>
        Log in to the admin panel to approve or decline this request.
    </p>

</x-emails.layout>
