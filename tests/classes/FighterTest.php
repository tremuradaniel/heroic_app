<?php

use PHPUnit\Framework\TestCase;

class FighterTest extends TestCase
{
    public function testFighter(): void
    {
        $fighter = new Fighter('beast');
        foreach ($fighter->traits as $value) {
            $this->assertNotNull($value);
            $this->assertIsNumeric($value);
        }

    }
}

?>