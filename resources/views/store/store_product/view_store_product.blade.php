@extends('template.master')
@section('css')
    {!! ($vs->css)('views/store/store_product/view_store_product') !!}
    {!! ($vs->css)('views/store/store_product/view_store_products') !!}
@endsection
@section('title-block')
    {{-- Store Products Title --}}
    <div class="uk-width-expand store_product_title_wrapper">
        <span class="store_product_title">{{ $storeProduct->name }}</span>
    </div>
    <div class="uk-width-auto uk-flex store_product_title_wrapper">
        <div class="uk-width-auto">
            @if (array_key_exists($storeProduct->id, $user->userBasketProducts))
                <a href="{{ route('store.basket.remove', $storeProduct->id) }}"
                   class="add_product_to_basket uk-button uk-icon-button">
                    <i class="fa fa-minus"></i>
                </a>
            @else
                <a href="{{ route('store.basket.add', $storeProduct->id) }}"
                   class="add_product_to_basket uk-button uk-icon-button">
                    <i class="fa fa-plus"></i>
                </a>
            @endif
        </div>
        <div class="uk-width-auto">
            <a href="{{ route('store.basket') }}" class="product_basket_button uk-button uk-icon-button">
                <span class="product_basket_count">{{ $user->basketItems }}</span>
                <i class="fa fa-shopping-basket"></i>
            </a>
        </div>
    </div>
@endsection
@section('body')
    @php ($images = [])
    @foreach ($storeProduct->package as $package)
        @if(gettype(__("system.{$package}.images")) !== 'string')
            @php($images = array_merge($images, __("system.{$package}.images")))
        @endif
        <div class="no-border-top store_product_item">
            <div class="uk-flex">
                <div class="uk-width-auto">
                    <span class="store_product_item_image">
                        <i class="fa {{ __("system.{$package}.icon") }}"></i>
                    </span>
                </div>
                <div class="uk-width-expand uk-flex uk-flex-middle">
                    <h2 class="store_product_name">{{ __("system.{$package}.name") }}</h2>
                </div>
            </div>
            <hr />
            <p class="store_product_description">{{ __("system.{$package}.description") }}</p>
        </div>
    @endforeach
    @if(! empty($images))
        <div class="section bg_grey no-border-top">
            <h2 class="section_title">Preview</h2>
            <div class="uk-grid uk-grid-small" uk-grid>
                @foreach ($images as $image)
                    <div class="uk-width-1-3@l uk-width-1-2@m">
                        <div class="store_product_preview_image">
                            <img src="{{ $image }}" alt="{{ __("system.{$package}.name") }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection