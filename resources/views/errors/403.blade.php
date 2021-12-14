<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>403 - {{ env('APP_NAME') }}</title>
        {{-- Styles --}}
        {!! ($vs->css)('assets/vendor/fontawesome/fontawesome') !!}
        {!! ($vs->css)('assets/vendor/uikit/uikit.min') !!}
        {!! ($vs->css)('assets/css/application') !!}
    </head>
    <body class="error-404 uk-flex uk-flex-middle uk-flex-center uk-grid uk-grid-collapse">
        <div class="error-message uk-width-1-3 uk-flex uk-flex-center uk-flex-middle">
            <div>
                {{-- Error Title --}}
                <h1><span>Error</span> 403</h1>
                <hr />
                <p>Unfortunately, my good sir, you do not have the permissions to view this, you may want to check out the store, get yourself the access to this particular portion of the system, if you don't really care, then feel free to trape back to where you came from, foo.</p>
                <a href="" class="uk-button"><i class="fa fa-arrow-left"></i></a>
                <a class="uk-button"
                   href="{{ action([\App\Http\Controllers\Store\StoreProductController::class, '_viewStoreProductsGet']) }}"
                >Store</a>
            </div>
        </div>
        <div class="error-image uk-width-2-3 uk-flex uk-flex-center uk-flex-middle">
            {{-- Insert an image here. --}}
            <p>{{ $exception->getMessage() }}</p>
        </div>
        {!! ($vs->js)('assets/vendor/uikit/uikit') !!}
        {!! ($vs->js)('assets/js/application') !!}
    </body>
</html>