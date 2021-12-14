<?php

namespace App\Http\Controllers\Store;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Store\StoreBasket;
use App\Models\Store\StoreProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;

class StoreBasketController extends Controller
{
    /**
    * StoreBasketController constructor.
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _viewStoreBasketGet(Request $request): Application|Factory|View
    {
        $this->vs->set('title', '- Store Basket')
                 ->set('current_page', 'page.store');

        return view('store.store_basket.view_store_basket');
    }

    /**
    * @param Request $request
    * @param string $product
    * @return RedirectResponse
    */
    public function _addToStoreBasketGet(Request $request, string $product): RedirectResponse
    {
        $store_product = StoreProduct::where('alias', '=', $product)->first();
        try {
            StoreBasket::create([
                'user_id'          => Auth::id(),
                'store_product_id' => $store_product->id
            ]);
        } catch (\Exception $exception) {

        } return back();
    }

    /**
    * @param Request $request
    * @param string $product
    * @return RedirectResponse
    */
    public function _removeFromStoreBasketGet(Request $request, string $product): RedirectResponse
    {
        $store_product = StoreProduct::where('alias', '=', $product)->first();
        try {
            StoreBasket::where([
                'user_id'          => Auth::id(),
                'store_product_id' => $store_product->id
            ])->delete();
        } catch (\Exception $exception) {

        } return back();
    }
}
