<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRequest;
use App\Http\Requests\Room\UpdateRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Role;
use App\Models\Room;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request): RoomCollection
    {
        $user = $request->user();
        $query = Room::with('details');

        if ($user->role_id == Role::OWNER) {
            $query->ownedBy($user->id);
        }

        $room = $query->paginate(10);

        return new RoomCollection($room);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->only([
            'name',
            'price',
            'location',
            'owner_id',
            'details',
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

            $this->authorize('index', $room);

            return response()->api(true, 'OK!', new RoomResource($room));
        } catch (AuthorizationException $e) {
            return response()->api(false, $e->getMessage(), null, 403);
        } catch (\Throwable $e) {
            return response()->api(false, $e->getMessage(), null, 500);
        }
    }

    public function update(UpdateRequest $request, $id): JsonResponse
    {
        $data = $request->only([
            'name',
            'price',
            'location',
            'details',
        ]);

        try {
            $room = Room::with('owner')->findOrFail($id);

            $this->authorize('index', $room);

            $room->update($data);

            if ($data['details']) {
                $room->details()->updateMany($data['details']);
            }

            return response()->api(true, 'OK!', new RoomResource($room));
        } catch (AuthorizationException $e) {
            return response()->api(false, $e->getMessage(), null, 403);
        } catch (\Throwable $e) {
            return response()->api(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $room = Room::findOrFail($id);

            $this->authorize('index', $room);

            $room->delete();

            return response()->api(true, 'OK!');
        } catch (AuthorizationException $e) {
            return response()->api(false, $e->getMessage(), null, 403);
        } catch (\Throwable $e) {
            return response()->api(false, $e->getMessage(), null, 500);
        }
    }
}
