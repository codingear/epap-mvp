<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\User;
use App\Models\Course;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $courses = Course::all();

        if ($users->count() > 0 && $courses->count() > 0) {
            // Create some sample payments - using only allowed status values
            $statuses = ['pending', 'completed', 'failed', 'refunded'];
            $paymentMethods = ['card', 'bank_transfer', 'cash'];

            for ($i = 0; $i < 20; $i++) {
                $user = $users->random();
                $course = $courses->random();

                Payment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'amount' => $course->price ?? rand(50, 500),
                    'currency' => $course->currency ?? 'usd',
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
