<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Menampilkan daftar kamar.
     */
    public function index()
    {
        $rooms = Room::latest()->get();
        return view('owner.rooms.index', compact('rooms'));
    }

    /**
     * Menampilkan form untuk menambah kamar baru.
     */
    public function create()
    {
        return view('owner.rooms.create');
    }

    /**
     * Menyimpan data kamar baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:20|unique:rooms,room_number',
            'room_type' => 'required|string|max:50',
            'monthly_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|file|max:2048', // Diubah menjadi 'file' agar aman dari error fileinfo
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        // Menangani upload foto secara manual tanpa mime guesser
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            // Simpan ke folder public/storage/rooms
            $file->move(public_path('storage/rooms'), $filename);
            $validated['photo'] = 'rooms/' . $filename;
        }

        Room::create($validated);

        return redirect()->route('owner.rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu kamar.
     */
    public function show(Room $room)
    {
        return view('owner.rooms.show', compact('room'));
    }

    /**
     * Menampilkan form edit kamar.
     */
    public function edit(Room $room)
    {
        return view('owner.rooms.edit', compact('room'));
    }

    /**
     * Memperbarui data kamar di database.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:20|unique:rooms,room_number,' . $room->id,
            'room_type' => 'required|string|max:50',
            'monthly_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|file|max:2048', // Diubah menjadi 'file'
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($room->photo && file_exists(public_path('storage/' . $room->photo))) {
                @unlink(public_path('storage/' . $room->photo));
            }

            $file = $request->file('photo');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('storage/rooms'), $filename);
            $validated['photo'] = 'rooms/' . $filename;
        }

        $room->update($validated);

        return redirect()->route('owner.rooms.index')
            ->with('success', 'Data kamar berhasil diperbarui.');
    }

    /**
     * Menghapus data kamar dari database.
     */
    public function destroy(Room $room)
    {
        // Hapus foto dari storage jika ada
        if ($room->photo && file_exists(public_path('storage/' . $room->photo))) {
            @unlink(public_path('storage/' . $room->photo));
        }

        $room->delete();

        return redirect()->route('owner.rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }
}