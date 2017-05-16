<?php

namespace Tests\Feature;

use App\User;
use App\Sheet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Test the SheetsController
 */
class SheetApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testMustHaveApiToken()
    {
        $response = $this->json('POST', 'api/sheets', []);

        die(($response->__toString()));
        $response->assertStatus(401);
    }


    /**
     * Test the creation of a sheet.
     *
     * Remember to test edge case security;
     * we do not want people to be able to
     * create sheets belonging to other users,
     * or otherwise corrupt the DB.
     */
    public function testCreation()
    {
        $user = factory(User::class)->create();

        $sheetRaw = factory(Sheet::class)->raw(['version' => 1, 'user_id' => $user->id]);

        $response = $this->json(
            'POST',
            sprintf('api/sheets?api_token=%s', $user->api_token),
            $sheetRaw
        );

        $response->assertStatus(201);

        $response->assertJson([
            'version' => 1,
            'allow_clone' => $sheetRaw['allow_clone'],
            'headline' => $sheetRaw['headline'],
            'tables' => $sheetRaw['tables'],
        ]);
    }

    /**
     * Test the fetching of a single sheet.
     */
    public function testFetch()
    {
        $user = factory(User::class)->create();

        $sheet = factory(Sheet::class)->create(['version' => 1, 'user_id' => $user->id]);

        $response = $this->json(
            'GET',
            sprintf('api/sheets/%s?api_token=%s', $sheet->id, $user->api_token)
        );

        $response->assertStatus(200);

        $response->assertJson($sheet->toArray());
    }

    /**
     * Test the clone of a sheet.
     *
     * Remember to test edge case security;
     * we do not want people to be able to
     * create sheets belonging to other users,
     * or otherwise corrupt the DB.
     */
    public function testClone()
    {
        $user = factory(User::class)->create();

        $sheet = factory(Sheet::class)->create(['version' => 1, 'user_id' => $user->id]);

        $response = $this->json(
            'POST',
            sprintf('api/sheets/%s/clone?api_token=%s', $sheet->id, $user->api_token)
        );

        $response->assertStatus(201);

        $response->assertJson([
            'headline' => $sheet->headline,
            'allow_clone' => $sheet->allow_clone,
            'tables' => $sheet->tables,
        ]);
    }
}
