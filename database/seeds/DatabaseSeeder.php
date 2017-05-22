<?php

use App\User;
use App\Sheet;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sheetData = Yaml::parse(file_get_contents(base_path('sampleChar.yml')));

        $user = factory(User::Class)->create();

        factory(Sheet::class)->create(array_merge(
            $sheetData,
            ['user_id' => $user->id]
        ));

        factory(Sheet::class, 500)->create();
    }
}
