<?php

namespace Tests\Feature\APIv1;

use App\Models\User;
use App\Providers\AuthServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ApiTestCase;

class GroupTest extends ApiTestCase {
    use RefreshDatabase;
    use WithFaker;

    public function testCurrentGroupWithoutAnyGroup(): void {
        $userToken = $this->getTokenForTestUser();

        $response = $this->getJson(
            uri: '/api/v1/group/current',
            headers: ['Authorization' => 'Bearer ' . $userToken],
        );
        $response->assertNotFound();
        $this->assertEquals('User is not in a group', $response->json('message'));
    }

    public function testCreatingUserIsOwnerAndMemberOfGroup(): void {
        $user = User::factory()->create();
        $token = $user->createToken('token', array_keys(AuthServiceProvider::$scopes))->accessToken;

        $response = $this->postJson(
            uri: '/api/v1/group',
            data: [
                'name' => $this->faker->name,
                'description' => $this->faker->name,
                'inactivityHours' => 8,
            ],
            headers: ['Authorization' => 'Bearer ' . $token],
        );
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'owner' => [
                    'id' => $user->id,
                    'username' => $user->username,
                ],
                'members' => [
                    [
                        'id' => $user->id,
                        'username' => $user->username,
                    ]
                ]
            ],
        ]);
    }
}
