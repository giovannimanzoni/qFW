<?php declare(strict_types=1);

namespace qFW\tests\unit\log;

use \PHPUnit\Framework\TestCase;
use qFW\log\SqlName;

class TestSqlName extends TestCase
{

    public function testSqlName()
    {
        $val= new SqlName(123);
        $this->assertEquals('qFW\log\Sql\Sql',$val->getName());
    }

    public function testSqlNameUidInt()
    {
        $val= new SqlName(123);
        $this->assertEquals('123',$val->getUid());
    }

    public function testSqlNameUidString()
    {
        $val= new SqlName('user1');
        $this->assertEquals('user1',$val->getUid());
    }
}