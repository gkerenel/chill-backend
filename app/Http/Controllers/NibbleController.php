<?php

namespace App\Http\Controllers;

use App\Http\Resources\NibbleCollection;
use App\Http\Resources\NibbleResource;
use App\Models\Delight;
use App\Models\Nibble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NibbleController extends Controller
{
    public function index(Delight $delight): NibbleCollection|JsonResponse
    {
        $gourmet = Auth::user();

        if ($delight->public && !$gourmet->isTasting($delight->gourmet)) {
            return response()->json(['message' => 'Nibbles are disabled on public delights'], 403);
        }

        if ($delight->gourmet_id !== Auth::id() && !$gourmet->isTasting($delight->gourmet)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new NibbleCollection($delight->nibbles()->with('gourmet')->paginate(10));
    }

    public function store(Request $request, Delight $delight): JsonResponse|NibbleResource
    {
        $gourmet = Auth::user();

        if ($delight->public && !$gourmet->isTasting($delight->gourmet)) {
            return response()->json(['message' => 'Nibbles are disabled on public delights'], 403);
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $nibble = $delight->nibbles()->create([
            'gourmet_id' => Auth::id(),
            'content' => $request['content'],
        ]);

        return new NibbleResource($nibble->load('gourmet'));
    }

    public function show(Nibble $nibble): NibbleResource
    {
        return new NibbleResource($nibble->load('gourmet', 'delight'));
    }

    public function update(Request $request, Nibble $nibble): JsonResponse|NibbleResource
    {
        if ($nibble->gourmet_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'string',
        ]);

        $nibble->update($request->only('content'));

        return new NibbleResource($nibble->load('gourmet', 'delight'));
    }

    public function destroy(Nibble $nibble): JsonResponse
    {
        if ($nibble->gourmet_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $nibble->delete();

        return response()->json(['message' => 'Nibble deleted']);
    }

}
