<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder {
    public function run(): void {
        $gertrud = User::where('username', 'Gertrud123')->first();
        $bob = User::where('username', 'bob')->first();
        $group = Group::factory([
            'name' => 'Jährliches Modelleisenbahntreffen ' . date('Y'),
            'description' => 'Sammelgruppe für die Anreise zum Jährlichen Modelleisenbahntreffen',
            'owner_id' => $gertrud->id,
        ])->create();

        $gertrud->groups()->attach($group);
        $bob->groups()->attach($group);
    }
}
