<?php declare(strict_types=1);

namespace qFW\test\unit\constants\encType;

use \PHPUnit\Framework\TestCase;
use qFW\constants\encType\Multipart;

/**
 * Class TestMultipart
 *
 * @package qFW\test\unit\constants\encType
 */
class TestMultipart extends TestCase
{
    /**
     * test getMultipart()
     */
    public function testGetMultipart()
    {
        $val= new Multipart();
        $this->assertEquals('multipart/form-data',$val->getCode());
    }
}
