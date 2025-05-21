<?php

namespace App\Http\Controllers;

use App\Models\ListingImage;
use Illuminate\Http\Request;

class ListingImageController extends Controller
{
    public function index()
    {
        return response()->json(ListingImage::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'url' => 'required|string',
            'is_cover' => 'boolean',
        ]);

        $image = ListingImage::create($validated);
        return response()->json($image, 201);
    }

    public function show($id)
    {
        $image = ListingImage::find($id);
        return $image ? response()->json($image) : response()->json(['error' => 'Not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $image = ListingImage::find($id);
        if (!$image) return response()->json(['error' => 'Not found'], 404);

        $image->update($request->all());
        return response()->json($image);
    }

    public function destroy($id)
    {
        $image = ListingImage::find($id);
        if (!$image) return response()->json(['error' => 'Not found'], 404);

        $image->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
