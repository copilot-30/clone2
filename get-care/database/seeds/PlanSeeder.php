<?php

use Illuminate\Database\Seeder;
use App\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing plans to prevent duplicates
        Plan::truncate();

        // Core Plan
        Plan::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Core',
            'price' => 599.00,
            'description' => "Dedicated Attending Physician,Initial Wellness Check,Multidisciplinary Plan,Access to all 8 specialties,Secure Digital Health Record,Ideal For: Individuals needing foundational specialist access and proactive planning.",
        ]);

        // Premium Plan
        Plan::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Premium',
            'price' => 2499.00,
            'description' => "Dedicated Attending Physician,Initial Wellness Check,Multidisciplinary Plan,On-demand Group Chat with all specialists,Up to four (4) specialist consultations per month (Online, F2F option available),Access to all 8 specialties,Secure Digital Health Record,Ideal For: Individuals anticipating more regular specialist interaction or with multiple health problems",
        ]);
    }
}
