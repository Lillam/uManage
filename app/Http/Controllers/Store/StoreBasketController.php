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

        $this->vs->set('has_sidebar', false);
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
    * @param string $product
    * @return RedirectResponse
    */
    public function _addToStoreBasketGet(string $product): RedirectResponse
    {
        try {
            StoreBasket::create([
                'user_id'          => Auth::id(),
                'store_product_id' => $product
            ]);
        } catch (\Exception $exception) {

        } return back();
    }

    /**
    * @param string $product
    * @return RedirectResponse
    */
    public function _removeFromStoreBasketGet(string $product): RedirectResponse
    {
        try {
            StoreBasket::where([
                'user_id'          => Auth::id(),
                'store_product_id' => $product
            ])->delete();
        } catch (\Exception $exception) {

        } return back();
    }
}
