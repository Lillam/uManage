<?php

namespace App\Http\Controllers\Store;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Store\StoreProduct;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class StoreProductController extends Controller
{
    /**
    * StoreProductController constructor.
    */
    public function __construct()
    {
        parent::__construct();

        $this->vs->set('has_sidebar', false);
    }

    /**
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _viewStoreProductsGet(Request $request): Application|Factory|View
    {
        $user = User::query()
            ->select('*')
            ->with([
                'user_basket_products',
                'user_basket_products.store_product'
            ])
            ->where('id', '=', Auth::id())
            ->first();

        $user->basket_items = $user->user_basket_products->count();
        $user->basket_price = 0;

        $user->user_basket_products = $user->user_basket_products->keyBy('store_product_id')->toArray();

        $store_products = StoreProduct::all()->map(function ($store_product) {
            $store_product->package = json_decode($store_product->package, true);
            return $store_product;
        });

        $this->vs->set('title', '- Store Products')
                 ->set('current_page', 'page.store');

        return view('store.store_product.view_store_products', compact(
            'store_products',
            'user'
        ));
    }

    /**
    * @param Request $request
    * @param string $product
    * @return Application|Factory|View
    */
    public function _viewStoreProductGet(Request $request, string $product): Application|Factory|View
    {
        $user = User::query()
            ->select('*')
            ->with([
                'user_basket_products',
                'user_basket_products.store_product'
            ])
            ->where('id', '=', Auth::id())
            ->first();

        $user->basket_items = $user->user_basket_products->count();

        $user->user_basket_products = $user->user_basket_products->keyBy('store_product_id')->toArray();

        $store_product = StoreProduct::query()
            ->where('alias', '=', $product)
            ->first();

        $store_product->package = json_decode($store_product->package);

        $this->vs->set('title', " - Store Products - {$store_product->name}")
                 ->set('current_page', 'page.store');

        return view('store.store_product.view_store_product', compact(
            'store_product',
            'user'
        ));
    }
}
