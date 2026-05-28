<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WasteRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can delete their own request.
     */
    public function test_user_can_delete_own_request()
    {
        $user = User::factory()->create(['role' => 'user']);
        $request = WasteRequest::create([
            'user_id' => $user->id,
            'title' => 'My Old Junk',
            'description' => 'Junk details',
            'waste_type' => 'dry',
            'address' => '123 Main St',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->delete(route('user.requests.destroy', $request));

        $response->assertRedirect();
        $this->assertDatabaseMissing('waste_requests', [
            'id' => $request->id,
        ]);
    }

    /**
     * Test user cannot delete another user's request.
     */
    public function test_user_cannot_delete_other_users_request()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        
        $request = WasteRequest::create([
            'user_id' => $user1->id,
            'title' => 'User 1 Junk',
            'description' => 'Junk details',
            'waste_type' => 'dry',
            'address' => '123 Main St',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user2)->delete(route('user.requests.destroy', $request));

        $response->assertStatus(403);
        $this->assertDatabaseHas('waste_requests', [
            'id' => $request->id,
        ]);
    }
}
