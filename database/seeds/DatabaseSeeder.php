<?php

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

        Sheet::forceCreate($sheetData);
    }
}
