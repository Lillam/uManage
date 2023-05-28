<?php

namespace App\Http\Controllers\Web\Store;

use App\Http\Controllers\Web\Controller;
use App\Models\Store\StoreProduct;
use App\Models\User\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreProductController extends Controller
{
    /**
    * StoreProductController constructor.
    */
    public function __construct()
    {
        parent::__construct();

        $this->vs->set('hasSidebar', false);
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
                 ->set('currentPage', 'page.store');

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
                 ->set('currentPage', 'page.store');

        return view('store.store_product.view_store_product', compact(
            'storeProduct',
            'user'
        ));
    }
}
