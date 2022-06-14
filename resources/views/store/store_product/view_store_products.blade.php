@extends('template.master')
@section('css')
    {!! ($vs->css)('views/store/store_product/view_store_products') !!}
@endsection
@section('title-block')
    {{-- Store Products Title --}}
    <div class="uk-width-expand">
        <span class="store_product_title">Store</span>
    </div>
    <div class="uk-flex store_product_title_wrapper">
        <div class="uk-width-auto uk-flex uk-flex-middle">
            <span>£{{ $user->basket_price }}</span>
        </div>
        <div class="uk-width-auto uk-margin-left">
            <a class="product_basket_button" href="{{ action('Store\StoreBasketController@_viewStoreBasketGet') }}">
                <span class="product_basket_count">{{ $user->basket_items }}</span>
                <i class="fa fa-shopping-basket"></i>
            </a>
        </div>
    </div>
@endsection
@section('body')
    @foreach($store_products as $store_product_key => $store_product)
        <div class="store_product_item no-border-top">
            <div class="uk-flex">
                <div class="uk-width-expand@s">
                    <div class="uk-flex">
                        <div class="uk-width-auto">
                            <span class="store_product_item_image">
                                <i class="fa fa-cog"></i>
                            </span>
                        </div>
                        <div class="uk-width-expand uk-flex uk-flex-middle">
                            <h2>{{ $store_product->name }}</h2>
                        </div>
                    </div>
                </div>
                <div class="uk-width-auto uk-flex uk-flex-bottom">
                    <span class="product_price">
                        <span class="product_price_currency">£</span>{{ $store_product->price }}
                    </span>
                </div>
            </div>
            <hr />
            <div class="uk-flex uk-grid uk-grid-small" uk-grid>
                <div class="uk-width-expand@m">
                    @foreach ($store_product->package as $package)
                        <p class="product_package_item">
                            <span class="product_package_image"><i class="fa {{ __("system.{$package}.icon") }}"></i></span>
                            {{ __("system.{$package}.name") }}
                        </p>
                    @endforeach
                </div>
                <div class="uk-width-auto uk-flex uk-flex-bottom @m">
                    <a href="{{ action('Store\StoreProductController@_viewStoreProductGet', $store_product->alias) }}"
                       class="view_product">Read More</a>
                    @if (array_key_exists($store_product->id, $user->user_basket_products))
                        <a href="{{ action('Store\StoreBasketController@_removeFromStoreBasketGet', $store_product->id) }}"
                           class="add_product_to_basket">Remove from Basket</a>
                    @else
                        <a href="{{ action('Store\StoreBasketController@_addToStoreBasketGet', $store_product->id) }}"
                           class="add_product_to_basket">Add to Basket</a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endsection