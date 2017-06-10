<?php

namespace Tests\Unit;

use App\User;
use App\Sheet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Assertions about the App\Sheet class
 *
 * @codingStandardsIgnoreStart
 */
class SheetClassTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_can_be_cloned()
    {
        $sheet = factory(Sheet::class)->create();

        $newUser = factory(User::class)->create();

        $clone = $sheet->createClone($newUser);

        $this->assertNotEquals($sheet->id, $clone->id);
        $this->assertEquals($newUser->id, $clone->user_id);
        $this->assertEquals($sheet->id, $clone->original_id);
        $this->assertEquals($sheet->clone_level + 1, $clone->clone_level);
        $this->assertEquals(1, $clone->version);
    }

    /**
     * @test
     */
    public function it_can_be_patched()
    {
        $sheet = factory(Sheet::class)->make([
            'version' => 1,
            'allow_view' => true,
            'allow_clone' => false,
            'headline' => 'bob marley',
            'tables' => [
                [
                    'headline' => 'foo',
                ],
                [
                    'headline' => 'bar',
                ],
                [
                    'headline' => 'baz',
                    'columns' => [
                        [
                            'headline' => 'bing',
                        ],
                    ],
                ],
            ],
        ]);

        // ====================
        // ==== allow_view ===
        // ====================
        $sheet->applyPatch(['allow_view'], false);
        $this->assertEquals(false, $sheet->allow_view);
        $this->assertEquals(2, $sheet->version, 'Version should be 2');

        // Apply same patch again. See that version does NOT increase.
        $sheet->applyPatch(['allow_view'], false);
        $this->assertEquals(false, $sheet->allow_view);
        $this->assertEquals(2, $sheet->version);

        // ====================
        // ==== allow_clone ===
        // ====================
        $sheet->applyPatch(['allow_clone'], true);
        $this->assertEquals(true, $sheet->allow_clone);
        $this->assertEquals(3, $sheet->version);

        // Apply same patch again. See that version does NOT increase.
        $sheet->applyPatch(['allow_clone'], true);
        $this->assertEquals(true, $sheet->allow_clone);
        $this->assertEquals(3, $sheet->version);

        // ====================
        // ==== headline ======
        // ====================
        $sheet->applyPatch(['headline'], 'new headline');
        $this->assertEquals('new headline', $sheet->headline);
        $this->assertEquals(4, $sheet->version);

        // Apply same patch again. See that version does NOT increase.
        $sheet->applyPatch(['headline'], 'new headline');
        $this->assertEquals('new headline', $sheet->headline);
        $this->assertEquals(4, $sheet->version);


        // ====================
        // == tables, simple ==
        // ====================
        $sheet->applyPatch(['tables', 0, 'headline'], 'headline for first table');
        $this->assertEquals('headline for first table', $sheet->tables[0]['headline']);
        $this->assertEquals(5, $sheet->version);

        // Apply same patch again. See that version does NOT increase.
        $sheet->applyPatch(['tables', 0, 'headline'], 'headline for first table');
        $this->assertEquals('headline for first table', $sheet->tables[0]['headline']);
        $this->assertEquals(5, $sheet->version);

        // ====================
        // = tables, complex ==
        // ====================
        $sheet->applyPatch(['tables', 2, 'columns', 0, 'headline'], 'headline for first column on third table');
        $this->assertEquals('headline for first column on third table', $sheet->tables[2]['columns'][0]['headline']);
        $this->assertEquals(6, $sheet->version);

        // Apply same patch again. See that version does NOT increase.
        $sheet->applyPatch(['tables', 2, 'columns', 0, 'headline'], 'headline for first column on third table');
        $this->assertEquals('headline for first column on third table', $sheet->tables[2]['columns'][0]['headline']);
        $this->assertEquals(6, $sheet->version);
    }
}
