<?php

namespace App\Http\Controllers\API\v1;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\GroupController as GroupBackend;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupController extends Controller {
    public function createGroup(Request $request): GroupResource {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'inactivityHours' => ['required', 'min:1', 'max:48'],
        ]);
        $group = GroupBackend::createGroup(
            name: $validated['name'],
            description: $validated['description'] ?? null,
            inactivity_hours: $validated['inactivityHours'],
            owner: $request->user()
        );
        return new GroupResource($group);
    }

    public function getGroup(Request $request, int $groupId): GroupResource {
        $userId = $request->user()->id;
        $group = Group::with('members')
            ->whereHas('members', function (Builder $query) use ($userId) {
                $query->where('user_id', '=', $userId);
            })
            ->where('id', $groupId)
            ->orderBy('created_at')
            ->firstOrFail();
        return new GroupResource($group);
    }

    public function listGroups(Request $request): ResourceCollection {
        $userId = $request->user()->id;
        return new ResourceCollection(
            Group::with('members')
            ->whereHas('members', function (Builder $query) use ($userId) {
                $query->where('user_id', '=', $userId);
            })
            ->orderBy('created_at')
            ->simplePaginate()
        );
    }
}