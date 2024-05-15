<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Submission;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $contest = Contest::factory()->create();
        $questions = Question::factory()->for($contest)->count(10)->create();
        $participants = Participant::factory()->for($contest)->count(5)->create();
        for( $i = 0 ; $i < 10 ; $i++ ) {
            Submission::factory()->for($participants[rand(0, 4)]->user)->for($questions[rand(0, 9)])->create();
        }
    }
}
