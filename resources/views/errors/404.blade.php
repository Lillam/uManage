<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>404 - {{ env('APP_NAME') }}</title>
        {{-- Styles --}}
        {!! ($vs->css)('assets/vendor/fontawesome/fontawesome') !!}
        {!! ($vs->css)('assets/vendor/uikit/uikit.min') !!}
        {!! ($vs->css)('assets/css/application') !!}
    </head>
    <body class="error-404 uk-flex uk-flex-middle uk-flex-center uk-grid uk-grid-collapse">
        <div class="error-message uk-width-1-3 uk-flex uk-flex-center uk-flex-middle">
            <div>
                {{-- Error Title --}}
                <h1><span>Error</span> 404</h1>
                <hr />
                <p>{{ trans('error.404') }}</p>
                <a href="{{ url()->previous() }}" class="uk-button">Back</a>
            </div>
        </div>
        <div class="error-image uk-width-2-3 uk-flex uk-flex-center uk-flex-middle">
            {{-- Insert an image here. --}}
            <p>{{ $exception->getMessage() }}</p>
        </div>
        {!! ($vs->js)('assets/vendor/uikit/uikit.min') !!}
        {!! ($vs->js)('assets/js/application') !!}
    </body>
</html>