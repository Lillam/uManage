<?php

namespace App\Http\Controllers\Web\Store;

use App\Http\Controllers\Web\Controller;
use App\Models\Store\StoreBasket;
use App\Models\Store\StoreProduct;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StoreBasketController extends Controller
{
    /**
    * StoreBasketController constructor.
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
    public function _viewStoreBasketGet(Request $request): Application|Factory|View
    {
        $this->vs->set('title', '- Store Basket')
                 ->set('currentPage', 'page.store');

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
