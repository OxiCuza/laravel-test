<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\RoomDiscussionCollection;
use App\Http\Resources\RoomDiscussionResource;
use App\Models\Room;
use App\Models\RoomDiscussion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RoomDiscussionController extends Controller
{
    public function show(Request $request, $roomId): JsonResponse
    {
        $discussions = RoomDiscussion::with('user')
            ->where('room_id', $roomId)
            ->get();

        return response()->api(true, 'OK!', new RoomDiscussionCollection($discussions));
    }

    public function store(Request $request, $roomId): JsonResponse
    {
        $user = $request->user();
        $data = [
            'message' => $request->get('message'),
            'user_id' => $user->id,
        ];

        try {
            $discussion = Room::find($roomId)->discussions()->create($data);
            $user->update([
                'credit' => $user->credit - 5,
            ]);

            return response()->api(true, 'OK!', new RoomDiscussionResource($discussion), 201);
        } catch (\Throwable $e) {
            $res = [
                'status' => false,
                'message' => $e->getMessage(),
            ];

            return response()->api(true, 'OK!', new ErrorResource($res, 500), 500);
        }
    }
}
