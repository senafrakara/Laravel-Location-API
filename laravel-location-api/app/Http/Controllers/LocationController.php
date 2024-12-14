<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Http\Requests\LocationRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Laravel Location API", version="3.0.1")
 * @OA\SecurityScheme(
 *      securityScheme="X-Api-Key",
 *      in="header",
 *      name="X-Api-Key",
 *      type="apiKey",
 *  ),
 * @OA\OpenApi(
 *      security={
 *          {"apiKeyAuth": {}}
 *      }
 *  )
 *
 *
 */
class LocationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/locations",
     *     summary="List all locations",
     *     tags={"Locations"},
     *     @OA\Response(response="200", description="Successful location listing")
     * )
     */
    public function index(): JsonResponse
    {
        $locations = Location::all();
        return response()->json($locations);
    }

    /**
     * @OA\Post(
     *     path="/api/locations",
     *     summary="Create a new location",
     *     tags={"Locations"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="latitude", type="number"),
     *             @OA\Property(property="longitude", type="number"),
     *             @OA\Property(property="color", type="string")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Location created successfully")
     * )
     */
    public function store(LocationRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $location = Location::create($validatedData);

        return response()->json($location, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/locations/{id}",
     *     summary="Get a specific location",
     *     tags={"Locations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Location ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Location retrieved successfully"),
     *     @OA\Response(response="404", description="Location not found")
     * )
     */
    public function show(Location $location): JsonResponse
    {
        return response()->json($location);
    }

    /**
     * @OA\Put(
     *     path="/api/locations/{id}",
     *     summary="Update an existing location",
     *     tags={"Locations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Location ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="latitude", type="number", format="float"),
     *             @OA\Property(property="longitude", type="number", format="float"),
     *             @OA\Property(property="color", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Location updated successfully"),
     *     @OA\Response(response="404", description="Location not found"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function update(LocationRequest $request, Location $location): JsonResponse
    {
        $validatedData = $request->validated();
        $location->update($validatedData);

        return response()->json($location);
    }

    /**
     * @OA\Get(
     *     path="/api/locations/route",
     *     summary="Get all locations sorted by distance from the first location",
     *     tags={"Locations"},
     *     @OA\Response(response="200", description="Locations retrieved successfully")
     * )
     */
    public function route(): JsonResponse
    {
        $locations = Location::all()->sortBy(function($location) {
            $first = Location::first();
            return $first->calculateDistanceLocations($location);
        });

        return response()->json($locations);
    }
}
