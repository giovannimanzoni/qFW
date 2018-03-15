<?php declare(strict_types=1);

namespace qFW\tests\unit\log;

use \PHPUnit\Framework\TestCase;
use qFW\log\ConsolleName;

class TestConsolleName extends TestCase
{

    public function testSqlName()
    {
        $val= new ConsolleName(123);
        $this->assertEquals('qFW\log\Consolle\Consolle',$val->getName());
    }

    public function testSqlNameUidInt()
    {
        $val= new ConsolleName(123);
        $this->assertEquals('123',$val->getUid());
    }

    public function testSqlNameUidString()
    {
        $val= new ConsolleName('user1');
        $this->assertEquals('user1',$val->getUid());
    }
}