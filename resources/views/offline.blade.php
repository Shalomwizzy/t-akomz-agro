<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#050505" />
    <title>You're Offline — T-Akomz Agro Estates</title>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #050505;
            color: #F5F5F5;
            font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
            min-height: 100svh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem 1.5rem;
            gap: 1.25rem;
            max-width: 480px;
            width: 100%;
        }

        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .logo-svg {
            width: 72px;
            height: 72px;
            flex-shrink: 0;
        }

        .brand-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.25em;
            color: #B8F397;
            text-transform: uppercase;
        }

        .divider {
            width: 48px;
            height: 2px;
            background: #2A2A2A;
            border-radius: 1px;
        }

        .icon-wifi {
            width: 56px;
            height: 56px;
            color: #2A2A2A;
            margin-bottom: 0.25rem;
        }

        h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 28px;
            font-weight: 700;
            color: #F5F5F5;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 15px;
            color: #888888;
            line-height: 1.6;
            max-width: 320px;
        }

        .btn-retry {
            display: inline-block;
            background-color: #B8F397;
            color: #050505;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 32px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            letter-spacing: 0.02em;
            transition: background-color 0.18s ease, transform 0.12s ease;
            margin-top: 0.5rem;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-retry:hover {
            background-color: #6FAE4B;
            transform: translateY(-1px);
        }

        .btn-retry:active {
            transform: translateY(0);
        }

        .footer-note {
            font-size: 12px;
            color: #444444;
            margin-top: 1rem;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
    <div class="container">

        <!-- Brand logo -->
        <div class="logo-wrap">
            <svg class="logo-svg" viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="T-Akomz Agro Estates logo">
                <path d="M60 4 C60 4 12 62 12 94 C12 120 34 136 60 136 C86 136 108 120 108 94 C108 62 60 4 60 4 Z"
                      stroke="#B8F397" stroke-width="5.5" stroke-linejoin="round" fill="none"/>
                <line x1="60" y1="118" x2="60" y2="60"
                      stroke="#B8F397" stroke-width="4.5" stroke-linecap="round"/>
                <path d="M60 95 C60 95 36 88 34 68 C32 52 48 46 58 56"
                      stroke="#B8F397" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <path d="M60 82 C60 82 84 76 86 56 C88 40 72 34 62 44"
                      stroke="#B8F397" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <path d="M30 132 Q60 145 90 132"
                      stroke="#B8F397" stroke-width="4" stroke-linecap="round" fill="none"/>
            </svg>
            <span class="brand-name">T-Akomz</span>
        </div>

        <div class="divider"></div>

        <!-- Wi-fi off icon -->
        <svg class="icon-wifi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="1" y1="1" x2="23" y2="23"/>
            <path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"/>
            <path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39"/>
            <path d="M10.71 5.05A16 16 0 0 1 22.56 9"/>
            <path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88"/>
            <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
            <circle cx="12" cy="20" r="1" fill="currentColor"/>
        </svg>

        <h1>You're Offline</h1>

        <p class="subtitle">
            No internet connection. Check your network and try again.
        </p>

        <button class="btn-retry" onclick="window.location.reload()">
            Try Again
        </button>

        <p class="footer-note">T-Akomz Agro Estates Ltd &mdash; Oke-Ido Road, Ido Ekiti, Ekiti State</p>

    </div>
</body>
</html>
