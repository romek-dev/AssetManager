<?php

namespace App\Http\Controllers;

use App\Data\AssetData;
use App\Data\AssignedUserData;
use App\Data\CategoryData;
use App\Http\Requests\AssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use App\Models\AssignedUser;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class AssetController extends Controller
{
    public function index(): Response
    {
        $assets = Asset::with('image', 'category', 'assignedUser', 'assetComponent')->get()->map(function ($asset) {
            return AssetData::fromModel($asset);
        });

        $categories = Category::get()->map(function ($category) {
            return CategoryData::fromModel($category);
        });

        $assignableUsers = AssignedUser::get()->map(function ($assignableUsers) {
            return AssignedUserData::fromModel($assignableUsers);
        });

        return Inertia::render(
            'Assets',
            [
                'assets' => $assets,
                'categories' => $categories,
                'assignableUsers' => $assignableUsers,
            ]
        );
    }

    public function store(AssetRequest $request): AssetResource
    {
        /** @var array<string, mixed> $requestData */
        $requestData = $request->validated();

        return new AssetResource(Asset::create($requestData));
    }

    public function show(Asset $asset): AssetResource
    {
        return new AssetResource($asset);
    }

    public function update(AssetRequest $request, Asset $asset): AssetResource
    {
        /** @var array<string, mixed> $requestData */
        $requestData = $request->validated();

        $asset->update($requestData);

        return new AssetResource($asset);
    }

    public function destroy(Asset $asset): JsonResponse
    {
        $asset->delete();

        return response()->json();
    }

    /**
     * Search for categories based on the query parameter.
     *
     * @param  Request  $request The incoming HTTP request.
     * @return Collection The search results.
     */
    public function search(Request $request): Collection
    {
        return Asset::search($request->query('asset-name'))->get()->map(function ($category) {
            return AssetData::fromModel($category);
        });
    }

    public function getAssetWithCategory(Request $request)
    {
        return Asset::getAssetWithCategory();
    }
}
