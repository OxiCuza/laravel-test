<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'owner@localhost.com')->first();

        $room = Room::create([
            'name' => 'room testing owner',
            'price' => 1000,
            'location' => 'Jawa Timur',
            'owner_id' => $user->id,
        ]);

        $room->details()->createMany([
            ['name' => 'AC'],
            ['name' => 'kamar mandi'],
        ]);
    }
}
