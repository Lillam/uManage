<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Web\Controller;
use App\Models\User\UserSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    /**
     * Method for setting and storing the user's preference on the theme. If the user clicks on being Dark or Light
     * theme then persist this into the database so on the next refresh the user will retain the same theme.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function _ajaxSetTheme(Request $request): JsonResponse
    {
        UserSetting::query()
            ->where('user_id', '=', Auth::id())
            ->update([
                'theme_color' => $request->input('theme_color')
            ]);

        return response()->json([
            'response' => 'Theme Changed'
        ]);
    }
}
