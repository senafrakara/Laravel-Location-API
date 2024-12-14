<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Location;

class LocationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    public function test_can_create_location()
    {
        $locationData = [
            'name' => 'Home',
            'latitude' => 38.7869353,
            'longitude' => 35.6128734,
            'color' => '#FF0000'
        ];

        $response = $this->postJson('/api/locations', $locationData);

        $response
            ->assertStatus(201)
            ->assertJson($locationData);
    }

    public function test_can_list_locations()
    {
        Location::factory()->count(1)->create();

        $response = $this->getJson('/api/locations');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_can_update_location()
    {
        $location = Location::factory()->create();

        $updateData = [
            'name' => 'Updated Location',
            'latitude' => 38.7869353,
            'longitude' => 35.6128734,
            'color' => '#00FF00'
        ];

        $response = $this->putJson("/api/locations/{$location->id}", $updateData);

        $response
            ->assertStatus(200)
            ->assertJson($updateData);
    }

    public function test_routing_endpoint()
    {
        $locations = Location::factory()->count(3)->create();

        $response = $this->getJson('/api/locations/route');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3);
    }
}
