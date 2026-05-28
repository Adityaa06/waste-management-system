<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test complaints index page.
     */
    public function test_user_can_view_complaints_index()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('user.complaints.index'));

        $response->assertStatus(200);
        $response->assertSee('Complaints Registry');
    }

    /**
     * Test creating a complaint.
     */
    public function test_user_can_submit_complaint()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('user.complaints.store'), [
            'title' => 'Bin not cleared',
            'description' => 'Sector 4 bin has not been cleared for 3 days.',
            'category' => 'Missed Collection',
            'priority' => 'high',
        ]);

        $response->assertRedirect(route('user.complaints.index'));
        $this->assertDatabaseHas('complaints', [
            'user_id' => $user->id,
            'title' => 'Bin not cleared',
            'priority' => 'high',
            'status' => 'pending',
        ]);
    }

    /**
     * Test deleting a complaint.
     */
    public function test_user_can_delete_complaint()
    {
        $user = User::factory()->create(['role' => 'user']);
        $complaint = Complaint::create([
            'user_id' => $user->id,
            'title' => 'Old Complaint',
            'description' => 'Some description',
            'category' => 'Other',
            'priority' => 'low',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->delete(route('user.complaints.destroy', $complaint));

        $response->assertRedirect(route('user.complaints.index'));
        $this->assertDatabaseMissing('complaints', [
            'id' => $complaint->id,
        ]);
    }
}
