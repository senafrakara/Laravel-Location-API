<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Http\Requests\LocationRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;

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
     *     @OA\Response(response="200", description="Successful location listing"),
     *     @OA\Response(response="404", description="Locations not found")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $locations = Location::all();

            if ($locations->isEmpty()) {
                return response()->json(['message' => 'No locations found'], 404);
            }

            return response()->json($locations, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving locations',
                'error' => $e->getMessage(),
            ], 500);
        }
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
     *     @OA\Response(response="201", description="Location created successfully"),
     *     @OA\Response(response="400", description="Invalid input or server error")
     * )
     */
    public function store(LocationRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $location = Location::create($validatedData);

            return response()->json($location, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the location',
                'message' => $e->getMessage(),
            ], 400);
        }
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
     *     @OA\Response(response="404", description="Location not found"),
     * )
     */
    public function show(Location $location): JsonResponse
    {
        try {
            return response()->json($location, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Location not found or an error occurred',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/locations/{id}",
     *     summary="Partially update an existing location",
     *     tags={"Locations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Location ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="latitude", type="number", format="float"),
     *             @OA\Property(property="longitude", type="number", format="float"),
     *             @OA\Property(property="color", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Location updated successfully"),
     *     @OA\Response(response="404", description="Location not found"),
     *     @OA\Response(response="422", description="Validation error"),
     *     @OA\Response(response="400", description="An error occurred")
     * )
     */
    public function update(LocationRequest $request, Location $location): JsonResponse
    {
        try {
            $data = $request->only(['name', 'latitude', 'longitude', 'color']);

            if (empty($data)) {
                return response()->json([
                    'error' => 'No data provided for update',
                ], 400);
            }
            $location->update($data);

            return response()->json($location, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Location not found',
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while updating the location',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/locations/route",
     *     summary="Get all locations sorted by distance from the specified starting location",
     *     tags={"Locations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="start_location_id",
     *                 type="integer",
     *                 description="The ID of the starting location",
     *                 example=1
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Locations retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="start_location",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="latitude", type="number", format="float"),
     *                 @OA\Property(property="longitude", type="number", format="float"),
     *                 @OA\Property(property="color", type="string")
     *             ),
     *             @OA\Property(
     *                 property="sorted_locations",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="latitude", type="number", format="float"),
     *                     @OA\Property(property="longitude", type="number", format="float"),
     *                     @OA\Property(property="color", type="string"),
     *                     @OA\Property(property="distance", type="number", format="float")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Start location not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="An error occurred",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function route(Request $request): JsonResponse
    {
        try {
            $startLocationId = $request->input('start_location_id');
            $startLocation = Location::findOrFail($startLocationId);

            $locations = Location::where('id', '!=', $startLocationId)
                ->get()
                ->map(function ($location) use ($startLocation) {
                    $distance = $startLocation->calculateDistanceLocations($location);
                    return [
                        'id' => $location->id,
                        'name' => $location->name,
                        'latitude' => $location->latitude,
                        'longitude' => $location->longitude,
                        'color' => $location->color,
                        'distance' => $distance,
                    ];
                })
                ->sortBy('distance')
                ->values();

            return response()->json([
                'start_location' => $startLocation,
                'sorted_locations' => $locations,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Start location not found',
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while calculating the route',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
