@props([
    'title'       => '',
    'description' => '',
    'image'       => null,
    'type'        => 'website',
    'noindex'     => false,
])

@php
    $siteName      = 'T-Akomz Agro Estates';
    $resolvedTitle = $title ? "$title | $siteName" : $siteName;
    $resolvedImage = $image ?? asset('images/logo-mark.svg');
    $canonicalUrl  = request()->url();
@endphp

<title>{{ $resolvedTitle }}</title>
<meta name="description" content="{{ $description }}">

@if($noindex)
<meta name="robots" content="noindex,nofollow">
@endif

<link rel="canonical" href="{{ $canonicalUrl }}">

{{-- Open Graph --}}
<meta property="og:title"       content="{{ $resolvedTitle }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image"       content="{{ $resolvedImage }}">
<meta property="og:type"        content="{{ $type }}">
<meta property="og:url"         content="{{ $canonicalUrl }}">
<meta property="og:site_name"   content="{{ $siteName }}">

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $resolvedTitle }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image"       content="{{ $resolvedImage }}">
