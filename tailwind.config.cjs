const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');
const typography = require('@tailwindcss/typography');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    green:        'rgb(var(--brand-green) / <alpha-value>)',
                    'dark-green': 'rgb(var(--brand-dark-green) / <alpha-value>)',
                },
                // These reference CSS custom properties so light/dark theme works
                // by changing the variables — no class changes needed in Blade files.
                surface: {
                    bg:       'rgb(var(--surface-bg) / <alpha-value>)',
                    card:     'rgb(var(--surface-card) / <alpha-value>)',
                    elevated: 'rgb(var(--surface-elevated) / <alpha-value>)',
                    border:   'rgb(var(--surface-border) / <alpha-value>)',
                },
                content: {
                    primary:   'rgb(var(--content-primary) / <alpha-value>)',
                    secondary: 'rgb(var(--content-secondary) / <alpha-value>)',
                    muted:     'rgb(var(--content-muted) / <alpha-value>)',
                },
            },

            fontFamily: {
                display: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
                sans:    ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                mono:    ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },

            backgroundImage: {
                'gradient-brand': 'linear-gradient(135deg, rgb(var(--brand-green)) 0%, rgb(var(--brand-dark-green)) 100%)',
                'gradient-dark':  'linear-gradient(180deg, rgb(var(--surface-bg)) 0%, rgb(var(--surface-card)) 100%)',
            },

            boxShadow: {
                'glow-green':  '0 0 30px rgb(var(--brand-green) / 0.15)',
                'glow-sm':     '0 0 15px rgb(var(--brand-green) / 0.10)',
                'glow-lg':     '0 0 60px rgb(var(--brand-green) / 0.20), 0 0 30px rgb(var(--brand-green) / 0.10)',
                'card':        '0 4px 6px rgba(0,0,0,0.3), 0 20px 40px rgba(0,0,0,0.2), 0 1px 0 rgba(255,255,255,0.04) inset',
                'card-hover':  '0 8px 12px rgba(0,0,0,0.4), 0 30px 60px rgba(0,0,0,0.3), 0 1px 0 rgba(255,255,255,0.06) inset',
                'header':      '0 4px 20px rgba(0,0,0,0.4), 0 1px 0 rgba(255,255,255,0.04) inset',
                'sidebar':     '4px 0 40px rgba(0,0,0,0.5)',
                'float':       '0 20px 60px rgba(0,0,0,0.5), 0 4px 12px rgba(0,0,0,0.3)',
            },

            animation: {
                'fade-in':    'fadeIn 0.5s ease-in-out',
                'slide-up':   'slideUp 0.4s ease-out',
                'slide-in':   'slideIn 0.3s ease-out',
                'pulse-slow': 'pulse 3s infinite',
            },

            keyframes: {
                fadeIn:  { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                slideUp: { '0%': { transform: 'translateY(20px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
                slideIn: { '0%': { transform: 'translateX(100%)' }, '100%': { transform: 'translateX(0)' } },
            },

            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },

            borderRadius: {
                'xl2': '1rem',
                'xl3': '1.5rem',
            },

            screens: {
                'xs': '375px',
            },
        },
    },

    plugins: [forms, typography],
};
