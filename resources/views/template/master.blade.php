<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ $vs->title }}</title>
        {{-- Style Yielding --}}
        @yield('css')
        {!! ($vs->css)('assets/vendor/uikit/uikit.min') !!}
        {!! ($vs->css)('assets/vendor/fontawesome/all') !!}
        {!! ($vs->css)('assets/vendor/summernote/summernote-lite') !!}
        @if((bool) env('PUSHER_ENABLED'))
            {!! ($vs->css)('css/app') !!}
        @endif
        {!! ($vs->css)('assets/css/application') !!}
    </head>
    <body class="{{ $theme }} {{ $current_page }} {{ $sidebar_class }}"
          data-get_emojis_url="{{ action('System\SystemController@_getSummernoteEmojis') }}">
        {{-- Header --}}
        @include('template.header')
        {{-- Body --}}
        @yield('body')
        {{-- Ajax Message Helper --}}
        <div class="ajax_message_helper"></div>
        {{-- Footer --}}
        @include('template.footer')
        {{-- Script Yielding --}}
        {!! ($vs->js)('assets/vendor/uikit/jquery') !!}
        {!! ($vs->js)('assets/vendor/uikit/uikit.min') !!}
        {!! ($vs->js)('assets/vendor/summernote/summernote-lite') !!}
        @if ((bool) env('PUSHER_ENABLED'))
            {!! ($vs->js)('js/app.js') !!}
        @endif
        @yield('js')
        @if ($vs->user instanceof \App\Models\User\User)
            {!! ($vs->js)('assets/js/application') !!}
        @endif
    </body>
</html>