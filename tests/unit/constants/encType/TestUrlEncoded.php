<?php declare(strict_types=1);

namespace qFW\tests\unit\constants\encType;

use \PHPUnit\Framework\TestCase;
use qFW\constants\encType\UrlEncoded;

/**
 * Class TestUrlEncoded
 *
 * @package qFW\tests\unit\constants\encType
 */
class TestUrlEncoded extends TestCase
{
    /**
     * Test
     */
    public function testUrlEncoded()
    {
        $val = new UrlEncoded();
        $this->assertEquals('application/x-www-form-urlencoded', $val->getCode());
    }
}
