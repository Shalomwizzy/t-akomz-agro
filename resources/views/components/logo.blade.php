@props(['class' => 'h-10 w-auto'])
<svg {{ $attributes->merge(['class' => $class . ' text-brand-green']) }}
     viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M60 4 C60 4 12 62 12 94 C12 120 34 136 60 136 C86 136 108 120 108 94 C108 62 60 4 60 4 Z"
          stroke="currentColor" stroke-width="5.5" fill="none" stroke-linejoin="round"/>
    <line x1="60" y1="118" x2="60" y2="60" stroke="currentColor" stroke-width="4.5" stroke-linecap="round"/>
    <path d="M60 95 C60 95 36 88 34 68 C32 52 48 46 58 56"
          stroke="currentColor" stroke-width="4.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M60 82 C60 82 84 76 86 56 C88 40 72 34 62 44"
          stroke="currentColor" stroke-width="4.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M30 132 Q60 145 90 132"
          stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round"/>
</svg>
