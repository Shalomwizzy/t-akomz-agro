<x-emails.layout :subject="$mailSubject">

    <div class="email-icon-wrap" style="background: #F0F9FF;">
        <span style="font-size: 30px;">📢</span>
    </div>

    <h1 class="email-title" style="text-align: center;">{{ $mailSubject }}</h1>

    <hr class="divider">

    <div style="font-size: 15px; color: #1A1A1A; line-height: 1.8; white-space: pre-line;">{{ $mailBody }}</div>

    <hr class="divider">

    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        This message was sent by <strong>{{ $senderName }}</strong> at T-Akomz Agro Estates Ltd.<br>
        Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria
    </p>

</x-emails.layout>
