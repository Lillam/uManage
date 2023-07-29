@extends('template.master')
@section('css')
    {!! ($vs->css)('views/store/store_product/view_store_products') !!}
@endsection
@section('title-block')
    {{-- Store Products Title --}}
    <div class="uk-width-expand">
        <span class="store_product_title">Store</span>
    </div>
    <div class="uk-flex store_product_title_wrapper navigation">
        <div class="uk-width-auto uk-flex uk-flex-middle">
            <span>£{{ $user->basketPrice }}</span>
        </div>
        <div class="uk-width-auto uk-margin-left">
            <a class="product_basket_button" href="{{ route('store.basket') }}">
                <span class="product_basket_count">{{ $user->basketItems }}</span>
                <i class="fa fa-shopping-basket"></i>
            </a>
        </div>
    </div>
@endsection
@section('body')
    @foreach($storeProducts as $storeProductKey => $storeProduct)
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
                            <h2 class="store_product_name">{{ $storeProduct->name }}</h2>
                        </div>
                    </div>
                </div>
                <div class="uk-width-auto uk-flex uk-flex-bottom">
                    <span class="product_price">
                        <span class="product_price_currency">£</span>{{ $storeProduct->price }}
                    </span>
                </div>
            </div>
            <hr />
            <div class="uk-flex uk-grid uk-grid-small" uk-grid>
                <div class="uk-width-expand@m">
                    @foreach ($storeProduct->package as $package)
                        <p class="product_package_item">
                            <span class="product_package_image"><i class="fa {{ __("system.{$package}.icon") }}"></i></span>
                            {{ __("system.{$package}.name") }}
                        </p>
                    @endforeach
                </div>
                <div class="uk-width-auto uk-flex uk-flex-bottom @m">
                    <a href="{{ route('store.products.product', $storeProduct->alias) }}"
                       class="view_product"
                    >Read More</a>
                    @if (array_key_exists($storeProduct->id, $user->userBasketProducts))
                        <a href="{{ route('store.basket.remove', $storeProduct->id) }}"
                           class="add_product_to_basket"
                        >Remove from Basket</a>
                    @else
                        <a href="{{ route('store.basket.add', $storeProduct->id) }}"
                           class="add_product_to_basket"
                        >Add to Basket</a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endsection