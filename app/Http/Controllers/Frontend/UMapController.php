<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UMapController extends Controller
{
    public static function index(User $user): object | null {
        return $user->uMap;
    }

    public function updateUMapSettings(Request $request): RedirectResponse {
        return back();
    }
}
