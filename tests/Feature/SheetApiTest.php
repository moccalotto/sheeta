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
 *
 * @codingStandardsIgnoreStart
 */
class SheetApiTest extends TestCase
{
    use DatabaseMigrations;

    protected function signIn() : User
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api');

        return $user;
    }

    /**
     * Ensure that we get a sane error if we don't authenticate.
     *
     * @test
     */
    public function give_sane_response_if_user_not_authenticated()
    {
        $response = $this->json('POST', 'api/sheets', []);

        $response->assertStatus(401);
    }

    /**
     * Ensure that the response code is correct if an entity cannot be found.
     *
     * @test
     */
    public function give_sane_response_if_sheet_could_not_be_found()
    {
        $this->signIn();

        $this->json('GET', 'api/sheets/42')
            ->assertStatus(404)
            ->assertJsonStructure(['error']);

        $this->json('PUT', 'api/sheets/42', [])
            ->assertStatus(404)
            ->assertJsonStructure(['error']);

        $this->json('PUT', 'api/sheets/foo', [])
            ->assertStatus(404)
            ->assertJsonStructure(['error']);
    }


    /**
     * Test the creation of a sheet.
     *
     * Remember to test edge case security;
     * we do not want people to be able to
     * create sheets belonging to other users,
     * or otherwise corrupt the DB.
     *
     * @test
     */
    public function it_can_create_a_new_sheet()
    {
        $user = $this->signIn();

        $sheetRaw = factory(Sheet::class)->raw(['version' => 1, 'user_id' => $user->id]);

        $response = $this->json('POST', 'api/sheets', $sheetRaw);

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
     *
     * @test
     */
    public function it_can_return_a_single_sheet()
    {
        $user = $this->signIn();

        $sheet = factory(Sheet::class)->create([
            'version' => 1,
            'user_id' => $user->id,
            'allow_view' => true,
        ]);

        $response = $this->json('GET', sprintf('api/sheets/%s', $sheet->id));

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
     *
     * @test
     */
    public function it_can_clone_a_single_sheet()
    {
        $user = $this->signIn();

        $sheet = factory(Sheet::class)->create([
            'version' => 1,
            'user_id' => $user->id,
            'allow_clone' => true,
        ]);

        $response = $this->json('POST', sprintf('api/sheets/%s/clone', $sheet->id));

        $response->assertStatus(201);

        $response->assertJson([
            'headline' => $sheet->headline,
            'allow_clone' => $sheet->allow_clone,
            'tables' => $sheet->tables,
        ]);
    }

    /**
     * Test that we can search for sheets via headlines.
     *
     * @test
     */
    public function it_can_search_for_sheets()
    {
        $this->signIn();

        factory(Sheet::class)->create(['headline' => 'Lorem Ipsum']);
        factory(Sheet::class)->create(['headline' => 'Example 2']);
        factory(Sheet::class)->create(['headline' => 'Foo Bar Baz Example']);

        $this->json(
            'GET',
            sprintf('api/sheets/?headline=%s', 'ipsum')
        )->assertStatus(200)
        ->assertJson([ 'total' => 1, ])
        ->assertJson([
            'data' => [
                [ 'headline' => 'Lorem Ipsum', ]
            ]
        ]);

        $this->json(
            'GET',
            sprintf('api/sheets/?headline=%s', 'bar')
        )->assertStatus(200)
        ->assertJson(['total' => 1])
        ->assertJson([
            'data' => [
                [ 'headline' => 'Foo Bar Baz Example', ]
            ]
        ]);

        $this->json(
            'GET',
            sprintf('api/sheets/?headline=%s', 'example')
        )->assertStatus(200)
        ->assertJson(['total' => 2])
        ->assertJson([
            'data' => [
                [ 'headline' => 'Example 2', ],
                [ 'headline' => 'Foo Bar Baz Example', ],
            ]
        ]);

    }
}
