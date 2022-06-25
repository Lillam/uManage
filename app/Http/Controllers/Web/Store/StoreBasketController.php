<?php

namespace App\Http\Controllers\Web\Store;

use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Store\StoreBasket;
use App\Models\Store\StoreProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
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
    * @param StoreProduct $product
    * @return RedirectResponse
    */
    public function _addToStoreBasketGet(StoreProduct $product): RedirectResponse
    {
        try {
            StoreBasket::query()
                ->create([
                    'user_id'          => Auth::id(),
                    'store_product_id' => $product->id
                ]);
        } catch (Exception $exception) {
            dd($exception);
        } return back();
    }

    /**
    * @param StoreProduct $product
    * @return RedirectResponse
    */
    public function _removeFromStoreBasketGet(StoreProduct $product): RedirectResponse
    {
        try {
            StoreBasket::query()
                ->where([
                    'user_id'          => Auth::id(),
                    'store_product_id' => $product->id
                ])->delete();
        } catch (Exception $exception) {
            dd($exception);
        } return back();
    }
}
