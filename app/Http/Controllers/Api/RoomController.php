<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request): RoomCollection
    {
        $room = Room::paginate(2);

        return new RoomCollection($room);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'location' => 'required',
            'owner_id' => 'required',
        ]);

        try {
            $room = Room::create($data);

            return response()->api(true, 'OK!', new RoomResource($room));
        } catch (\Throwable $e) {
            $res = [
                'status' => false,
                'message' => $e->getMessage(),
            ];

            return response()->api(true, 'OK!', new ErrorResource($res, 500));
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $room = Room::with('owner')->find($id);

            return response()->api(true, 'OK!', new RoomResource($room));
        } catch (\Throwable $e) {
            $res = [
                'status' => false,
                'message' => $e->getMessage(),
            ];

            return response()->api(true, 'OK!', new ErrorResource($res, 500));
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'location' => 'required',
        ]);

        try {
            $room = Room::with('owner')->findOrFail($id);
            $room->update($data);

            return response()->api(true, 'OK!', new RoomResource($room));
        } catch (\Throwable $e) {
            $res = [
                'status' => false,
                'message' => $e->getMessage(),
            ];

            return response()->api(true, 'OK!', new ErrorResource($res, 500));
        }
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        if (Room::destroy($id)) {
            return response()->api(true, 'OK!');
        }

        return response()->api(false, 'Failed!');
    }
}
