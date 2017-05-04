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

        factory(Sheet::class)->create($sheetData);

        factory(User::Class)->create([
            'api_token' => '1234',
        ]);
    }
}
