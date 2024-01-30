<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomDiscussionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_regular_can_ask_about_room(): void
    {
        $regular = $this->getRegular();
        $owner = $this->getOwner();

        $this->actingAs($regular);

        $room = Room::where('owner_id', $owner->id)->first();

        $data = [
            'message' => 'test message',
        ];

        $response = $this->postJson("/api/v1/rooms/$room->id/discussions", $data);

        $response->assertStatus(201)
            ->assertJson([
                "status" => true,
                "message" => "OK!",
                "data" => [
                    "id" => $response->json("data.id"),
                    "message" => "test message",
                ]
            ]);


        $this->assertDatabaseHas('room_discussions', [
            'id' => $response->json('data.id'),
            'message' => $response->json('data.message'),
        ]);


        $this->assertDatabaseHas('users', [
            'id' => $regular->id,
            'credit' => 15,
        ]);
    }

    public function test_user_can_view_all_discussion(): void
    {
        $regular = $this->getRegular();
        $owner = $this->getOwner();

        $this->actingAs($regular);

        $room = Room::where('owner_id', $owner->id)->first();

        $response = $this->getJson("/api/v1/rooms/$room->id/discussions");

        $response->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    '*' => [
                        "id",
                        "message",
                        "user"
                    ]
                ]
            ]);


    }
}
