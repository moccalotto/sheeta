<?php

namespace Tests\Unit;

use App\User;
use App\Sheet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SheetCloneTest extends TestCase
{
    use DatabaseMigrations;

    public function testCanClone()
    {
        $sheet = factory(Sheet::class)->create([
            'allow_clone' => true,
        ]);

        $newUser = factory(User::class)->create();

        $clone = $sheet->createClone($newUser);

        $this->assertNotEquals($sheet->id, $clone->id);
        $this->assertEquals($newUser->id, $clone->user_id);
        $this->assertEquals($sheet->id, $clone->original_id);
        $this->assertEquals($sheet->clone_level + 1, $clone->clone_level);
        $this->assertEquals(1, $clone->version);
    }
}
