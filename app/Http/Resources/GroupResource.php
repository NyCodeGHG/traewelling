<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource {

    /**
     * @var User[]
     */
    private Collection $members;
    private User $owner;

    public function __construct(private Group $group) {
        $this->members = $group->members()->get();
        $this->owner = $group->owner()->firstOrFail();
    }

    public function toArray($request): array {
        $members = $this->members->map(fn($member) => new UserResource($member));
        return [
            'id' => (int) $this->group->id,
            'name' => (string) $this->group->name,
            'description' => $this->group->description,
            'inactivityHours' => (int) $this->group->inactivity_hours,
            'members' => $members,
            'owner' => new UserResource($this->owner),
        ];
    }
}
