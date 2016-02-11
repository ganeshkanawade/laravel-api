<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Models\Post;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $faker = Faker\Factory::create();
        
        foreach(range(1,10) as $index)
        {
            Post::create([
                'title' => $faker->paragraph($nbWords  = 3),
                'post' => $faker->text($maxNbChars  = 200),
                'user_id' =>$faker->numberBetween($min = 1, $max = 5)
            ]);
        }
    }
}
