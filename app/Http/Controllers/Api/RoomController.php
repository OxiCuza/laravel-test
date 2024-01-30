<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRequest;
use App\Http\Requests\Room\UpdateRequest;
use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Role;
use App\Models\Room;
use App\Strategy\Implement\LocationSearchStrategy;
use App\Strategy\Implement\NameSearchStrategy;
use App\Strategy\Implement\PriceSearchStrategy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index(Request $request): RoomCollection
    {
        $user = $request->user();
        $query = Room::with('details');

        if ($user->role_id == Role::OWNER) {
            $query->ownedBy($user->id);
        }

        $searchStrategy = [
            'name' => new NameSearchStrategy(),
            'price' => new PriceSearchStrategy(),
            'location' => new LocationSearchStrategy(),
        ];

        foreach ($searchStrategy as $param => $strategy) {
            if ($request->filled($param)) {
                $strategy->search($query, $request->input($param));
            }
        }

        if ($request->filled('sort')) {
            $query->sortByPrice($request->input('sort'));
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

        DB::beginTransaction();
        try {
            $room = Room::create($data);
            $room->details()->createMany($data['details']);

            DB::commit();

            return response()->api(true, 'OK!', new RoomResource($room));
        } catch (\Throwable $e) {

            DB::rollback();

            return response()->api(false, $e->getMessage(), null, 500);
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

        DB::beginTransaction();
        try {
            $room = Room::with('owner')->findOrFail($id);

            $this->authorize('index', $room);

            $room->update($data);

            if ($data['details']) {
                foreach ($data['details'] as $detailData) {
                    $room->details()->updateOrCreate(
                        ['room_id' => $room->id, 'id' => $detailData['id']],
                        $detailData
                    );
                }
            }

            DB::commit();

            return response()->api(true, 'OK!', new RoomResource($room));
        } catch (AuthorizationException $e) {

            return response()->api(false, $e->getMessage(), null, 403);
        } catch (\Throwable $e) {
            DB::rollback();

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
        } catch (ModelNotFoundException $e) {

            return response()->api(false, 'Kost / Room Not Found', null, 404);
        } catch (\Throwable $e) {

            return response()->api(false, $e->getMessage(), null, 500);
        }
    }
}
