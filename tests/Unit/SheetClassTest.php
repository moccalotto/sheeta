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
}
