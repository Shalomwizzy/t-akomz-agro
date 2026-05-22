<x-emails.layout subject="Welcome to T-Akomz Agro Estates">

    <div class="email-icon-wrap" style="background: #F0FBE8;">
        <span style="font-size: 30px;">🌱</span>
    </div>

    <h1 class="email-title" style="text-align: center;">Welcome to the Team!</h1>
    <p class="email-subtitle" style="text-align: center;">
        Hi {{ $worker->full_name }}, we're excited to welcome you to T-Akomz Agro Estates.
        You are now officially part of our farm family. Below are your employment details.
    </p>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Full Name</span>
            <span class="info-value">{{ $worker->full_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Role</span>
            <span class="info-value green">{{ $worker->role }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Employment Type</span>
            <span class="info-value">{{ ucfirst($worker->employment_type) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Start Date</span>
            <span class="info-value">{{ $worker->start_date->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Salary Type</span>
            <span class="info-value">{{ match($worker->salary_type) {
                'fixed'      => 'Fixed Monthly Salary',
                'daily_rate' => 'Daily Rate',
                'hourly'     => 'Hourly Rate',
                default      => ucfirst($worker->salary_type),
            } }}</span>
        </div>
        @if($worker->bank_name && $worker->bank_account)
        <div class="info-row">
            <span class="info-label">Bank Details</span>
            <span class="info-value">{{ $worker->bank_name }} — {{ $worker->bank_account }}</span>
        </div>
        @endif
    </div>

    <ul class="steps-list">
        <li class="step-item">
            <div class="step-num">1</div>
            <div class="step-content">
                <h4>Report on Time</h4>
                <p>Attendance is tracked daily. Your salary is calculated based on your attendance records.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num">2</div>
            <div class="step-content">
                <h4>Know Your Rights</h4>
                <p>You are entitled to your agreed pay on schedule. Contact your supervisor if you have questions about your salary or working conditions.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num">3</div>
            <div class="step-content">
                <h4>Keep Your Details Updated</h4>
                <p>Ensure your bank account and contact details on file are correct so salary payments reach you without issues.</p>
            </div>
        </li>
    </ul>

    <hr class="divider">

    <p style="font-size: 13px; color: #555; text-align: center; line-height: 1.7; margin: 0 0 16px;">
        We're glad to have you on the team. Let's grow together!
    </p>
    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        T-Akomz Agro Estates Ltd · Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria<br>
        Questions? Contact us at <a href="mailto:admin@takomzagro.com" style="color: #3B8A1E; text-decoration: none;">admin@takomzagro.com</a>
    </p>

</x-emails.layout>
