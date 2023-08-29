<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class GroupController extends Controller {
    public function renderCreateGroup(): Renderable {
        return view('groups.create');
    }
}
