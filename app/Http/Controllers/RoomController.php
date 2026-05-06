<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $floors = $landlord->rooms()->select('floor')->distinct()->orderBy('floor')->pluck('floor');

        $query = $landlord->rooms()->with('activeLease.tenants.person')->orderBy('room_number');

        if ($search = request('search')) {
            $query->where('room_number', 'like', "%{$search}%");
        }

        if (($floor = request('floor')) && $floor !== 'all') {
            $query->where('floor', $floor);
        }

        if (($status = request('status')) && $status !== 'all') {
            $query->where('status', $status);
        }

        $rooms = $query->paginate(10)->through(function (Room $room) {
            $primaryTenant = $room->activeLease?->tenants->first()?->person;
            $tenantName = $primaryTenant ? trim("{$primaryTenant->first_name} {$primaryTenant->last_name}") : null;

            return [
                'room_id' => $room->room_id,
                'room_number' => $room->room_number,
                'floor' => $room->floor,
                'monthly_rent' => (float) $room->monthly_rent,
                'status' => $room->status,
                'tenant_name' => $tenantName ?: null,
                'created_at' => $room->created_at->format('M d, Y'),
            ];
        });

        return view('rooms.index', [
            'rooms' => $rooms,
            'floors' => $floors,
            'statuses' => ['Available', 'Occupied', 'Under Maintenance'],
        ]);
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(StoreRoomRequest $request)
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $landlord->rooms()->create($request->validated());

        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $this->authorizeRoom($room);

        $room->load('activeLease.tenants.person');

        $activeLease = null;
        $tenants = null;

        if ($room->activeLease) {
            $activeLease = [
                'lease_id' => $room->activeLease->lease_id,
                'room_number' => $room->room_number,
                'start_date' => $room->activeLease->start_date ? \Carbon\Carbon::parse($room->activeLease->start_date)->format('M d, Y') : null,
            ];

            $tenants = $room->activeLease->tenants
                ->map(
                    fn($tenant) => [
                        'tenant_id' => $tenant->tenant_id,
                        'name' => trim("{$tenant->person->first_name} {$tenant->person->last_name}"),
                        'is_primary' => (bool) ($tenant->pivot->is_primary_tenant ?? false),
                    ],
                )
                ->values()
                ->all();
        }

        return view('rooms.show', compact('room', 'activeLease', 'tenants'));
    }

    public function edit(Room $room)
    {
        $this->authorizeRoom($room);

        return view('rooms.edit', compact('room'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $this->authorizeRoom($room);

        $room->update($request->validated());

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $this->authorizeRoom($room);

        // Prevent deletion of an occupied room
        if ($room->activeLease) {
            return redirect()->route('rooms.index')->with('error', 'Cannot delete a room with an active lease.');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }

    private function authorizeRoom(Room $room): void
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        abort_if((string) $room->landlord_id !== (string) $landlord->landlord_id, 403, 'This room does not belong to your account.');
    }
}
