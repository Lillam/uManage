<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>503 - {{ env('APP_NAME') }}</title>
        {{-- Styles --}}
        {!! ($vs->css)('assets/vendor/fontawesome/fontawesome') !!}
        {!! ($vs->css)('assets/vendor/uikit/uikit') !!}
        {!! ($vs->css)('assets/css/application') !!}
    </head>
    <body class="error-404 uk-flex uk-flex-middle uk-flex-center uk-grid uk-grid-collapse">
        <div class="error-message uk-width-1-3 uk-flex uk-flex-center uk-flex-middle">
            {{-- Error Title --}}
            <div>
                <h1><span>Error</span> 503</h1>
                <hr />
                <p>{{ trans('error.503') }}</p>
            </div>
        </div>
        {{-- Insert an image here. --}}
        <div class="error-image uk-width-2-3 uk-flex uk-flex-center uk-flex-middle">
            <p>{{ $exception->getMessage() }}</p>
        </div>
        {!! ($vs->js)('assets/vendor/uikit/uikit.min') !!}
        {!! ($vs->js)('assets/js/applications') !!}
    </body>
</html>