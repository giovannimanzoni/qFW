<?php declare(strict_types=1);

namespace qFW\test\unit\constants\encType;

use \PHPUnit\Framework\TestCase;
use qFW\constants\encType\TextPlain;

/**
 * Class TestTextPlain
 *
 * @package qFW\test\unit\constants\encType
 */
class TestTextPlain extends TestCase
{
    /**
     * Test getTextPlain
     */
    public function testGetTextPlain()
    {
        $val= new TextPlain();
        $this->assertEquals('text/plain',$val->getCode());
    }
}
