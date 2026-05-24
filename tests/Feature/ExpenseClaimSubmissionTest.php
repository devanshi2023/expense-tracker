<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ExpenseClaim;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseClaimSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_claim_submission_rejects_more_than_two_decimal_places(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Travel', 'monthly_budget_limit' => 10000]);

        $response = $this->actingAs($user)->post(route('team.claims.store'), [
            'category_id' => $category->id,
            'amount' => '100.999',
            'claim_date' => now()->toDateString(),
            'description' => 'Taxi fare for client meeting',
        ]);

        $response->assertSessionHasErrors('amount');
        $this->assertDatabaseCount('expense_claims', 0);
    }

    public function test_team_member_can_submit_valid_claim(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Meals', 'monthly_budget_limit' => 5000]);

        $response = $this->actingAs($user)->post(route('team.claims.store'), [
            'category_id' => $category->id,
            'amount' => '125.50',
            'claim_date' => now()->toDateString(),
            'description' => 'Dinner during client workshop',
            'receipt_note' => 'Receipt uploaded separately',
        ]);

        $response->assertRedirect(route('team.claims.index'));
        $this->assertDatabaseHas('expense_claims', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 125.50,
            'status' => ExpenseClaim::STATUS_PENDING,
        ]);
    }
}
