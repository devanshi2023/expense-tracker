<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ExpenseClaim;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => 'password', 'role' => User::ROLE_ADMIN]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@example.com'],
            ['name' => 'Manager User', 'password' => 'password', 'role' => User::ROLE_MANAGER]
        );

        $teamOne = User::updateOrCreate(
            ['email' => 'team1@example.com'],
            ['name' => 'Team Member One', 'password' => 'password', 'role' => User::ROLE_TEAM_MEMBER]
        );

        $teamTwo = User::updateOrCreate(
            ['email' => 'team2@example.com'],
            ['name' => 'Team Member Two', 'password' => 'password', 'role' => User::ROLE_TEAM_MEMBER]
        );

        $travel = Category::updateOrCreate(
            ['name' => 'Travel'],
            ['monthly_budget_limit' => 10000, 'is_active' => true]
        );

        $meals = Category::updateOrCreate(
            ['name' => 'Meals'],
            ['monthly_budget_limit' => 5000, 'is_active' => true]
        );

        $software = Category::updateOrCreate(
            ['name' => 'Software'],
            ['monthly_budget_limit' => 8000, 'is_active' => true]
        );

        $today = Carbon::today();

        ExpenseClaim::updateOrCreate(
            ['user_id' => $teamOne->id, 'description' => 'Taxi fare for client meeting'],
            [
                'category_id' => $travel->id,
                'amount' => 1250,
                'claim_date' => $today->copy()->subDays(5),
                'receipt_note' => 'Cab receipt available',
                'status' => ExpenseClaim::STATUS_APPROVED,
                'manager_comment' => 'Approved for client visit.',
                'reviewed_by' => $manager->id,
                'reviewed_at' => $today->copy()->subDays(4),
            ]
        );

        ExpenseClaim::updateOrCreate(
            ['user_id' => $teamTwo->id, 'description' => 'Team lunch after release'],
            [
                'category_id' => $meals->id,
                'amount' => 2100.50,
                'claim_date' => $today->copy()->subDays(3),
                'receipt_note' => null,
                'status' => ExpenseClaim::STATUS_PENDING,
            ]
        );

        ExpenseClaim::updateOrCreate(
            ['user_id' => $manager->id, 'description' => 'Project management software subscription'],
            [
                'category_id' => $software->id,
                'amount' => 3200,
                'claim_date' => $today->copy()->subDays(2),
                'receipt_note' => 'Annual plan prorated',
                'status' => ExpenseClaim::STATUS_PENDING,
            ]
        );

        ExpenseClaim::updateOrCreate(
            ['user_id' => $teamOne->id, 'description' => 'Last month airport transfer'],
            [
                'category_id' => $travel->id,
                'amount' => 1800,
                'claim_date' => $today->copy()->subMonth()->startOfMonth()->addDays(4),
                'receipt_note' => null,
                'status' => ExpenseClaim::STATUS_APPROVED,
                'manager_comment' => 'Previous month approved sample.',
                'reviewed_by' => $admin->id,
                'reviewed_at' => $today->copy()->subDays(20),
            ]
        );
    }
}
