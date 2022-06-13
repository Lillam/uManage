@extends('template.master')
@section('css')
    {!! ($vs->css)('views/store/store_product/view_store_product') !!}
@endsection
@section('body')
    @php ($images = [])
    {{-- Store Products Title --}}
    <div class="store_product_title_wrapper">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <span class="store_product_title">{{ $store_product->name }}</span>
            </div>
            <div class="uk-width-auto uk-border-left">
                @if (array_key_exists($store_product->id, $user->user_basket_products))
                    <a href="{{ action('Store\StoreBasketController@_removeFromStoreBasketGet', $store_product->id) }}"
                       class="add_product_to_basket">
                        <i class="fa fa-minus"></i>
                    </a>
                @else
                    <a href="{{ action('Store\StoreBasketController@_addToStoreBasketGet', $store_product->id) }}"
                       class="add_product_to_basket">
                        <i class="fa fa-plus"></i>
                    </a>
                @endif
            </div>
            <div class="uk-width-auto uk-border-left">
                <a href="{{ action('Store\StoreBasketController@_viewStoreBasketGet') }}"
                   class="product_basket_button">
                    <span class="product_basket_count">{{ $user->basket_items }}</span>
                    <i class="fa fa-shopping-basket"></i>
                </a>
            </div>
        </div>
    </div>

    @foreach ($store_product->package as $package)
        @if(gettype(__("system/lang_system_module.{$package}.images")) !== 'string')
            @php($images = array_merge($images, __("system/lang_system_module.{$package}.images")))
        @endif
        <div class="section">
            <span class="product_package_image"><i class="fa {{ __("system/lang_system_module.{$package}.icon") }}"></i></span>
            <h2 class="section_title">{{ __("system/lang_system_module.{$package}.name") }}</h2>
            <p>{{ __("system/lang_system_module.{$package}.description") }}</p>
        </div>
    @endforeach

    @if(! empty($images))
        <div class="section bg_grey">
            <h2 class="section_title">Preview</h2>
            <div class="uk-grid uk-grid-small" uk-grid>
                @foreach ($images as $image)
                    <div class="uk-width-1-3@l uk-width-1-2@m">
                        <div class="store_product_preview_image">
                            <img src="{{ $image }}" alt="{{ __("system/lang_system_module.{$package}.name") }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection