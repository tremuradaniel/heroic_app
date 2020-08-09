<?php

use PHPUnit\Framework\TestCase;

class FighterTest extends TestCase
{
    public function testFighterGetsTraits(): void
    {
        $fighter = new Fighter('beast');
        foreach ($fighter->traits as $value) {
            $this->assertNotNull($value);
            $this->assertIsNumeric($value);
        }

    }

    // public function testCannotBeCreatedFromInvalidEmailAddress(): void
    // {
    //     $this->expectException(InvalidArgumentException::class);

    //     Email::fromString('invalid');
    // }

    // public function testCanBeUsedAsString(): void
    // {
    //     $this->assertEquals(
    //         'user@example.com',
    //         Email::fromString('user@example.com')
    //     );
    // }
}

?>