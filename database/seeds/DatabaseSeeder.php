<?php

use App\User;
use App\Sheet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::Class)->create([
            'email' => 'admin@sheeta.dev',
            'password' => bcrypt('demo'),
            'username' => 'DemoAdmin',
            'type' => 'super',
        ]);


        $user = factory(User::Class)->create([
            'email' => 'user@sheeta.dev',
            'password' => bcrypt('demo'),
            'username' => 'DemoUser',
            'type' => 'user',
        ]);

        factory(Sheet::class)->create(array_merge(
            yaml_parse(file_get_contents(base_path('sampleChar.yml'))),
            ['user_id' => $user->id]
        ));

        factory(Sheet::class)->create(array_merge(
            yaml_parse(file_get_contents(base_path('sampleTodo.yml'))),
            ['user_id' => $user->id]
        ));

        // factory(Sheet::class, 503)->create();
    }
}
