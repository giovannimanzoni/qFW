<?php declare(strict_types=1);

namespace qFW\tests\unit\log;

use \PHPUnit\Framework\TestCase;
use qFW\log\NoneName;

class TestNoneName extends TestCase
{

    public function testSqlName()
    {
        $val= new NoneName(123);
        $this->assertEquals('qFW\log\None\None',$val->getName());
    }

    public function testSqlNameUidInt()
    {
        $val= new NoneName(123);
        $this->assertEquals('123',$val->getUid());
    }

    public function testSqlNameUidString()
    {
        $val= new NoneName('user1');
        $this->assertEquals('user1',$val->getUid());
    }
}