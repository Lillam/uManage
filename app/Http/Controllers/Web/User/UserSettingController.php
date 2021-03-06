<?php

namespace App\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use App\Models\User\UserSetting;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserSettingController extends Controller
{
    /**
    * Method for setting and storing the user's preference for the sidebar has being collapsed or not. if the user
    * collapses their sidebar; then we'll need to store and remember this preference so that on their next refresh the
    * sidebar will still remain hidden.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxSetSidebarCollapsed(Request $request): JsonResponse
    {
        UserSetting::query()
            ->where('user_id', '=', Auth::id())
            ->update([
                'sidebar_collapsed' => $request->input('is_collapsed')
            ]);

        return response()->json([
            'response' => 'Menu Collapsed',
        ]);
    }
}
