@php
    $meta = $meta ?? [];
    $siteName = 'TheSPARK';
    $title = $meta['title'] ?? 'The Spark - Official Publication';
    $description = $meta['description'] ?? 'TheSPARK is the official student publication of Camarines Sur Polytechnic Colleges. Truth knows no limits.';
    $image = $meta['image'] ?? url('/assets/Spark_Logo.png');
    $pageUrl = $meta['url'] ?? url('/');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $pageUrl }}">

    <!-- Link previews (Facebook, Messenger, X, ...) -->
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $meta['type'] ?? 'website' }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:card" content="{{ isset($meta['image']) ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $image }}">
    @if (!empty($meta['published']))
        <meta property="article:published_time" content="{{ $meta['published'] }}">
    @endif
    @if (!empty($meta['section']))
        <meta property="article:section" content="{{ $meta['section'] }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/White_Spark_Logo.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div id="app"></div>
</body>

</html>
