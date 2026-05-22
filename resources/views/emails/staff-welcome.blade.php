<x-emails.layout subject="Your T-Akomz Staff Account is Ready — Action Required">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #F0F0FF;">
        <span style="font-size: 30px;">🔑</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">Your Staff Account is Ready</h1>
    <p class="email-subtitle" style="text-align: center;">
        Hi {{ $user->name }}, an administrator has created a staff account for you on
        T-Akomz Agro Estates. Your login credentials are below — please log in and
        change your password immediately.
    </p>

    {{-- Critical security alert --}}
    <div class="alert-box-red">
        <p class="alert-title" style="color: #B91C1C;">🔒 Change Your Password Immediately</p>
        <p class="alert-text">
            Your account was created with a temporary password. You <strong>must</strong> change it
            on your first login. Do not share your credentials with anyone — not even with other
            staff members or administrators. If you did not request this account, contact us immediately.
        </p>
    </div>

    {{-- Account credentials --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Full Name</span>
            <span class="info-value">{{ $user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email (Login)</span>
            <span class="info-value">{{ $user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Role</span>
            <span class="info-value green">{{ strtoupper($role) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Temporary Password</span>
            <span class="info-value" style="font-family: 'Courier New', Courier, monospace; font-size: 15px; font-weight: 700; color: #C0392B; letter-spacing: 1px;">
                {{ $temporaryPassword }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Account Created</span>
            <span class="info-value">{{ $user->created_at->format('d M Y, g:i A') }}</span>
        </div>
    </div>

    {{-- Steps --}}
    <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #999; margin: 0 0 14px;">
        Getting Started
    </p>
    <ul class="steps-list">
        <li class="step-item">
            <div class="step-num">1</div>
            <div class="step-content">
                <h4>Log In with Temporary Password</h4>
                <p>Go to the admin login page and enter your email and the temporary password shown above.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num">2</div>
            <div class="step-content">
                <h4>Change Your Password</h4>
                <p>Navigate to your Profile settings and set a strong, unique password. Use at least 12 characters with a mix of letters, numbers and symbols.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num">3</div>
            <div class="step-content">
                <h4>Access the Admin Panel</h4>
                <p>Once your password is set, you have full access to the admin panel based on your assigned role.</p>
            </div>
        </li>
    </ul>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/admin" class="cta-btn">Login to Admin Panel</a>
    </div>

    <hr class="divider">

    {{-- Security note --}}
    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">Security Reminder</p>
        <p class="alert-text">
            Never share your login credentials with anyone — this includes your manager,
            other team members or support staff. T-Akomz will never ask for your password
            via email or phone. If you suspect your account has been compromised, contact
            the system administrator immediately.
        </p>
    </div>

    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        This email was sent by the T-Akomz Agro Estates system administrator.
        If you did not expect this, contact <a href="mailto:admin@takomzagro.com" style="color: #3B8A1E; text-decoration: none;">admin@takomzagro.com</a> immediately.
    </p>

</x-emails.layout>
