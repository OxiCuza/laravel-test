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
        $room = Room::with('details')->paginate(5);

        return new RoomCollection($room);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'location' => 'required',
            'owner_id' => 'required',
            'details' => 'required|array|min:1',
            'details.*.name' => 'required',
        ]);

        try {
            $room = Room::create($data);
            $room->details()->createMany($data['details']);

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
            $room = Room::with(['owner', 'details', 'discussions'])->find($id);

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
            'details' => 'nullable|array|min:1',
            'details.*.id' => 'required|exists:room_details,id',
            'details.*.name' => 'required',
        ]);

        try {
            $room = Room::with('owner')->findOrFail($id);
            $room->update($data);

            if ($data['details']) {
                $room->details()->updateMany($data['details']);
            }

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
