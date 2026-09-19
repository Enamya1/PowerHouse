<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('membership_type')->insert([
            [
                'membership_type' => 'Basic',
                'price' => 9.99,
                'description' => 'Basic monthly access to standard facilities and equipment.',
                'duration_days' => 30,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_type' => 'Standard',
                'price' => 19.99,
                'description' => 'Standard monthly membership with group class access.',
                'duration_days' => 30,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_type' => 'Premium',
                'price' => 29.99,
                'description' => 'Premium all-access membership with locker and towel service.',
                'duration_days' => 30,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_type' => 'Quarterly',
                'price' => 49.99,
                'description' => 'Quarterly plan with full access at a discounted monthly rate.',
                'duration_days' => 90,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_type' => 'Annual',
                'price' => 99.99,
                'description' => 'Annual membership with all premium benefits included.',
                'duration_days' => 365,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_type' => 'Student',
                'price' => 14.99,
                'description' => 'Discounted monthly membership for students with valid ID.',
                'duration_days' => 30,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_type' => 'Trial',
                'price' => 0.00,
                'description' => '7-day free trial for new members.',
                'duration_days' => 7,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
