@php
    /**
     * @var \Aurora\Models\Page $page
     * @var \Aurora\Models\Layout|null $layout
     * @var bool $preview  a preview link, or the page not live yet
     * @var bool $embedded  inside the visual editor
     */
    $siteName = \Aurora\Models\Setting::get('site_name', config('app.name'));
    $description = $page->meta_description ?: $page->excerpt;
@endphp
{{-- The website's blank layout: the page's blocks alone, without a header, menu or footer (the
     homepage's welcome card). Published from Aurora and made this site's own: its look is
     resources/css/app.css. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->seoTitle() }} · {{ $siteName }}</title>
    @if (filled($description))
        <meta name="description" content="{{ $description }}">
    @endif
    @if ($page->noindex || $page->isRestricted() || $preview || $embedded)
        <meta name="robots" content="noindex">
    @endif

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('brand-mark.png') }}">

    {{ \Illuminate\Support\Facades\Vite::fonts() }}
    {{-- The blocks' own layout (columns, spacers, buttons): the page builder's stylesheet. --}}
    <x-page-builder::styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('aurora::partials.brand-palette')
</head>
<body>
<div class="site">
    <main>
        <x-aurora::blocks :page="$page" />
    </main>
</div>
</body>
</html>
