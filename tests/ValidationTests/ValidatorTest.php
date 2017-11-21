<?php

namespace Tests\ValidationTests;

use Core\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testBasic() {
        $this->assertSame(true, true);
    }
}
