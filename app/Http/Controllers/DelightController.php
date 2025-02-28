<?php

namespace App\Http\Controllers;

use App\Http\Resources\DelightCollection;
use App\Http\Resources\DelightResource;
use App\Models\Delight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DelightController extends Controller
{
    public function index(): DelightCollection
    {
        $gourmet = Auth::user();

        $followingIds = $gourmet->tasting()->pluck('taster_id');
        $followingIds->push($gourmet->id);

        $delights = Delight::with('gourmet')
            ->where(function ($query) use ($followingIds) {
                $query->whereIn('gourmet_id', $followingIds)
                    ->orWhere('public', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return new DelightCollection($delights);
    }

    public function store(Request $request): DelightResource
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'public' => 'boolean',
        ]);

        $delight = Delight::create([
            'gourmet_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'public' => $request->input('public', false),
        ]);

        return new DelightResource($delight->load('gourmet'));
    }

    public function show(Delight $delight): DelightResource|JsonResponse
    {
        $gourmet = Auth::user();

        if ($delight->public && !$gourmet->isTasting($delight->gourmet)) {
            return new DelightResource($delight->load('gourmet'));
        }

        if ($delight->gourmet_id !== Auth::id() && !$gourmet->isTasting($delight->gourmet)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new DelightResource($delight->load('gourmet', 'nibbles', 'eats'));
    }

    public function update(Request $request, Delight $delight): DelightResource|JsonResponse
    {
        if ($delight->gourmet_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'public' => 'boolean',
        ]);

        $delight->update($request->only('title', 'content', 'public'));

        return new DelightResource($delight->load('gourmet'));
    }

    public function destroy(Delight $delight): JsonResponse
    {
        if ($delight->gourmet_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $delight->delete();

        return response()->json(['message' => 'Delight deleted']);
    }

    public function eat(Delight $delight): DelightResource|JsonResponse
    {
        $gourmet = Auth::user();

        if ($delight->public && !$gourmet->isTasting($delight->gourmet)) {
            return response()->json(['message' => 'Cannot eat public posts'], 403);
        }

        if ($delight->isEatenBy($gourmet->id)) {
            return response()->json(['message' => 'Delight already eaten'], 400);
        }

        $delight->eats()->create(['gourmet_id' => $gourmet->id]);

        return new DelightResource($delight->load('gourmet', 'eats'));
    }

    public function uneat(Delight $delight): DelightResource|JsonResponse
    {
        $gourmet = Auth::user();
        if ($delight->public && !$gourmet->isTasting($delight->gourmet)) {
            return response()->json(['message' => 'Cannot uneat public posts'], 403);
        }

        $eat = $delight->eats()->where('gourmet_id', $gourmet->id)->first();

        if (!$eat) {
            return response()->json(['message' => 'Delight not eaten'], 400);
        }

        $eat->delete();

        return new DelightResource($delight->load('gourmet', 'eats'));
    }
}
