<?php

namespace App\Http\Controllers\Web\Store;

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
                'userBasketProducts',
                'userBasketProducts.storeProduct'
            ])
            ->where('id', '=', Auth::id())
            ->first();

        $user->basketItems = $user->userBasketProducts->count();
        $user->basketPrice = 0;

        $user->userBasketProducts = $user->userBasketProducts->keyBy('store_product_id')->toArray();

        $storeProducts = StoreProduct::all()->map(function (StoreProduct $storeProduct) {
            $storeProduct->package = json_decode($storeProduct->package, true);
            return $storeProduct;
        });

        $this->vs->set('title', '- Store Products')
                 ->set('current_page', 'page.store');

        return view('store.store_product.view_store_products', compact(
            'storeProducts',
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
                'userBasketProducts',
                'userBasketProducts.storeProduct'
            ])
            ->where('id', '=', Auth::id())
            ->first();

        $user->basketItems = $user->userBasketProducts->count();

        $user->userBasketProducts = $user->userBasketProducts->keyBy('store_product_id')->toArray();

        $storeProduct = StoreProduct::query()
            ->where('alias', '=', $product)
            ->first();

        $storeProduct->package = json_decode($storeProduct->package);

        $this->vs->set('title', " - Store Products - {$storeProduct->name}")
                 ->set('current_page', 'page.store');

        return view('store.store_product.view_store_product', compact(
            'storeProduct',
            'user'
        ));
    }
}
