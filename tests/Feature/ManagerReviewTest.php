<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ExpenseClaim;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_approve_a_team_member_claim(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $member = User::factory()->create(['role' => User::ROLE_TEAM_MEMBER]);
        $category = Category::create(['name' => 'Travel', 'monthly_budget_limit' => 10000]);
        $claim = ExpenseClaim::create([
            'user_id' => $member->id,
            'category_id' => $category->id,
            'amount' => 450,
            'claim_date' => now()->toDateString(),
            'description' => 'Train ticket for client visit',
        ]);

        $response = $this->actingAs($manager)->patch(route('manager.claims.review', $claim), [
            'status' => ExpenseClaim::STATUS_APPROVED,
            'manager_comment' => 'Looks good.',
        ]);

        $response->assertRedirect(route('manager.dashboard'));
        $this->assertDatabaseHas('expense_claims', [
            'id' => $claim->id,
            'status' => ExpenseClaim::STATUS_APPROVED,
            'reviewed_by' => $manager->id,
            'manager_comment' => 'Looks good.',
        ]);
    }

    public function test_manager_cannot_approve_own_claim(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $category = Category::create(['name' => 'Software', 'monthly_budget_limit' => 8000]);
        $claim = ExpenseClaim::create([
            'user_id' => $manager->id,
            'category_id' => $category->id,
            'amount' => 900,
            'claim_date' => now()->toDateString(),
            'description' => 'Project software subscription',
        ]);

        $response = $this->actingAs($manager)->patch(route('manager.claims.review', $claim), [
            'status' => ExpenseClaim::STATUS_APPROVED,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('expense_claims', [
            'id' => $claim->id,
            'status' => ExpenseClaim::STATUS_PENDING,
        ]);
    }
}
