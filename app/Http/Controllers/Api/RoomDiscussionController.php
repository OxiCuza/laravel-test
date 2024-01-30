<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\DiscussionRequest;
use App\Http\Resources\RoomDiscussionCollection;
use App\Http\Resources\RoomDiscussionResource;
use App\Models\RoomDiscussion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomDiscussionController extends Controller
{
    public function show(Request $request, $roomId): JsonResponse
    {
        $discussions = RoomDiscussion::with('user')
            ->where('room_id', $roomId)
            ->get();

        return response()->api(true, 'OK!', new RoomDiscussionCollection($discussions));
    }

    public function store(DiscussionRequest $request, $roomId): JsonResponse
    {
        $user = $request->user();
        $data = [
            'message' => $request->get('message'),
            'user_id' => $user->id,
            'room_id' => $roomId,
        ];

        try {
            $discussion = RoomDiscussion::create($data);
            $user->update([
                'credit' => $user->credit - 5,
            ]);

            return response()->api(true, 'OK!', new RoomDiscussionResource($discussion), 201);
        } catch (\Throwable $e) {

            return response()->api(false, $e->getMessage(), null, 500);
        }
    }
}
