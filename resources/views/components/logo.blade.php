@props(['class' => 'h-10 w-auto'])
<img {{ $attributes->merge(['class' => $class]) }}
     src="{{ asset('images/icons/t_akomz_logo_100kb.jpg') }}"
     alt="T-Akomz Agro"
     loading="eager">
