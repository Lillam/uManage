<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $vs->applicationTheme }}">
    <head>
        <title>{{ $vs->title }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        {{-- Favicons --}}
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
        <link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        {{-- Style Yielding --}}
        @yield('css')
        @cssAsset('assets/vendor/uikit/uikit.min')
        @cssAsset('assets/vendor/fontawesome/all')
        @cssAsset('assets/vendor/select2/select2')
        @cssAsset('assets/vendor/summernote/summernote-lite')
        @if (env('PUSHER_ENABLED'))
            {!! ($vs->css)('css/app') !!}
        @endif
        @cssAsset('assets/css/application')
    </head>
    <body class="{{ $bodyClass }}"
          data-collapse_sidebar_url="{{ route('user.settings.sidebar-collapse') }}"
          data-set_theme_url="{{ route('user.settings.theme') }}"
          data-get_emojis_url="{{ route('system.emojis') }}"
    >
        {{-- Header --}}
        @include('template.header')
        {{-- Body --}}
        @if ($vs->hasTitle)
            <div class="title-block">
                @yield('title-block')
            </div>
        @endif
        @yield('body')
        {{-- Ajax Message Helper --}}
        <div class="ajax_message_helper"></div>
        {{-- Footer --}}
        @include('template.footer')
        {{-- Script Yielding --}}
        @jsAsset('assets/vendor/uikit/jquery')
        @jsAsset('assets/vendor/uikit/uikit.min')
        @jsAsset('assets/vendor/select2/select2')
        @jsAsset('assets/vendor/summernote/summernote-lite')
        @if (env('PUSHER_ENABLED'))
            {!! ($vs->js)('js/app') !!}
        @endif
        @yield('js')
        @if ($vs->user instanceof \App\Models\User\User)
            @jsAsset('assets/js/application')
        @endif
    </body>
</html>