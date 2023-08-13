<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MockCandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidates = collect([
            (object)[
                "name" => "Juma Calisto",
                "position" => 1,
                "photo" => "profile-photos/candidate-01.png"
            ],
            (object)[
                "name" => "Ochieng' Otieno",
                "position" => 1,
                "photo" => "profile-photos/candidate-02.png"
            ],
            (object)[
                "name" => "Achieng' Achieng'",
                "position" => 1,
                "photo" => "profile-photos/candidate-03.png"
            ],
            (object)[
                "name" => "Linet Atieno",
                "position" => 2,
                "photo" => "profile-photos/candidate-04.png"
            ],
            (object)[
                "name" => "Odhiambo Otieno",
                "position" => 2,
                "photo" => "profile-photos/candidate-05.png"
            ],
            (object)[
                "name" => "Samuel Otieno",
                "position" => 3,
                "photo" => "profile-photos/candidate-06.png"
            ],
            (object)[
                "name" => "John Paul",
                "position" => 3,
                "photo" => "profile-photos/candidate-07.png"
            ],
            (object)[
                "name" => "Jane Doe",
                "position" => 3,
                "photo" => "profile-photos/candidate-08.png"
            ],
            (object)[
                "name" => "John Doe",
                "position" => 4,
                "photo" => "profile-photos/candidate-09.png"
            ],
            (object)[
                "name" => "Mike Oluoch",
                "position" => 4,
                "photo" => "profile-photos/candidate-10.png"
            ],
        ]);

        $election = \App\Models\Election::first();

        $candidates->each(function ($item) use ($election) {
            $faker = \Faker\Factory::create();
            $user = new \App\Models\User();
            $user->name = $item->name;
            $user->profile_photo_path = $item->photo;
            $user->phone = $faker->e164PhoneNumber();
            $user->password = bcrypt('password');
            $user->first_login_completed = 1;
            $user->save();

            $candidate = new \App\Models\Candidate();
            $candidate->user_id = $user->id;
            $candidate->position_id = $item->position;
            $candidate->election_id = $election->id;
            $candidate->save();
        });
    }
}
