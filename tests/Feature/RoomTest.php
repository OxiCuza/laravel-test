<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function dummy(): array
    {
        return [
            'name' => 'room testing owner',
            'price' => 1000,
            'location' => 'Jawa Timur',
            'details' => [
                ['name' => 'AC'],
                ['name' => 'kamar mandi'],
            ],
        ];
    }

    public function test_regular_cannot_create_room(): void
    {
        $this->actingAs($this->getRegular());

        $data = $this->dummy();

        $response = $this->postJson('/api/v1/rooms', $data);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'status',
                'message',
            ]);
    }

    public function test_owner_can_create_room(): void
    {
        $this->actingAs($this->getOwner());

        $data = $this->dummy();

        $response = $this->postJson('/api/v1/rooms', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'price',
                    'location',
                ],
            ]);

        $this->assertDatabaseHas('rooms', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'price' => $response->json('data.price'),
            'location' => $response->json('data.location'),
        ]);
    }

    public function test_owner_can_see_list_room(): void
    {
        $this->actingAs($this->getOwner());

        $response = $this->getJson('/api/v1/rooms');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'location',
                    ],
                ],
            ]);
    }

    public function test_regular_can_see_list_room(): void
    {
        $this->actingAs($this->getRegular());

        $response = $this->getJson('/api/v1/rooms');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'location',
                    ],
                ],
            ]);
    }

    public function test_owner_can_see_detail_room(): void
    {
        $user = $this->getOwner();

        $this->actingAs($user);

        $room = Room::where('owner_id', $user->id)->first();

        $response = $this->getJson("/api/v1/rooms/$room->id");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'price',
                    'location',
                    'owner' => [
                        'id',
                        'name',
                        'email',
                        'credit'
                    ],
                ],
            ]);
    }

    public function test_owner_can_update_room(): void
    {
        $user = $this->getOwner();

        $this->actingAs($user);

        $room = Room::with('details')->where('owner_id', $user->id)->first();

        $data = [
            'name' => 'room testing update',
            'price' => 2000,
            'location' => 'Surabaya',
            'details' => [
                [
                    'id' => $room->details[0]->id,
                    'name' => 'Jendela'
                ],
                [
                    'id' => $room->details[1]->id,
                    'name' => 'Kipas Angin'
                ],
            ],
        ];

        $response = $this->putJson("/api/v1/rooms/$room->id", $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'OK!',
                'data' => [
                    'id' => $response->json('data.id'),
                    'name' => $data['name'],
                    'price' => $data['price'],
                    'location' => $data['location'],
                ],
            ]);

        $this->assertDatabaseHas('rooms', [
            'name' => $data['name'],
            'price' => $data['price'],
            'location' => $data['location'],
        ]);
    }

    public function test_owner_can_delete_room(): void
    {
        $user = $this->getOwner();

        $this->actingAs($user);

        $room = Room::where('owner_id', $user->id)->first();

        $response = $this->deleteJson("/api/v1/rooms/$room->id");

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'OK!',
            ]);

        $this->assertDatabaseMissing('rooms', [
            'id' => $room->id,
        ]);
    }

    public function test_regular_cannot_delete_room(): void
    {
        $regular = $this->getRegular();
        $owner = $this->getOwner();

        $this->actingAs($regular);

        $room = Room::where('owner_id', $owner->id)->first();

        $response = $this->deleteJson("/api/v1/rooms/$room->id");

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Unauthorized',
                'data' => null
            ]);
    }
}
