<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Models\Course;
use App\Models\Level;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $courses = Course::all();
        $levels = Level::all();
        $completedPayments = Payment::where('status', 'completed')->get();

        if ($users->count() > 0 && $courses->count() > 0) {
            // Create some sample enrollments
            $statuses = ['active', 'completed', 'cancelled', 'suspended'];

            for ($i = 0; $i < 15; $i++) {
                $user = $users->random();
                $course = $courses->random();

                // Check if enrollment already exists for this user-course combination
                $existingEnrollment = Enrollment::where('user_id', $user->id)
                                               ->where('course_id', $course->id)
                                               ->first();

                if (!$existingEnrollment) {
                    $payment = null;
                    if ($completedPayments->count() > 0 && rand(0, 1)) {
                        $payment = $completedPayments->where('user_id', $user->id)
                                                   ->where('course_id', $course->id)
                                                   ->first();
                        if (!$payment) {
                            $payment = $completedPayments->random();
                        }
                    }

                    Enrollment::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'level_id' => $levels->count() > 0 ? $levels->random()->id : null,
                        'payment_id' => $payment ? $payment->id : null,
                        'status' => $statuses[array_rand($statuses)],
                        'amount' => $course->price ?? rand(50, 500),
                        'created_at' => now()->subDays(rand(0, 30)),
                    ]);
                }
            }
        }
    }
}
