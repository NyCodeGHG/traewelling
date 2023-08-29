<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller {
    public static function createGroup(
        string $name,
        string|null $description,
        int $inactivity_hours,
        User $owner,
    ): Group {
        // todo: check that user is not in an active group right now

        DB::beginTransaction();
        $group = Group::create([
            'name' => $name,
            'description' => $description,
            'inactivity_hours' => $inactivity_hours,
            'owner_id' => $owner->id,
        ]);
        $owner->groups()->attach($group);
        DB::commit();
        return $group;
    }
}
