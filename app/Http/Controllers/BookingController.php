<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return response()->json(Booking::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_price' => 'required|numeric',
            'status' => 'in:pending,confirmed,cancelled',
        ]);

        $booking = Booking::create($validated);
        return response()->json($booking, 201);
    }

    public function show($id)
    {
        $booking = Booking::find($id);
        return $booking ? response()->json($booking) : response()->json(['error' => 'Not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) return response()->json(['error' => 'Not found'], 404);

        $booking->update($request->all());
        return response()->json($booking);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        if (!$booking) return response()->json(['error' => 'Not found'], 404);

        $booking->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
