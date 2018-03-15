<?php declare(strict_types=1);

namespace qFW\tests\unit\log;

use \PHPUnit\Framework\TestCase;
use qFW\log\HtmlName;

class TestHtmlName extends TestCase
{

    public function testSqlName()
    {
        $val= new HtmlName(123);
        $this->assertEquals('qFW\log\Html\Html',$val->getName());
    }

    public function testSqlNameUidInt()
    {
        $val= new HtmlName(123);
        $this->assertEquals('123',$val->getUid());
    }

    public function testSqlNameUidString()
    {
        $val= new HtmlName('user1');
        $this->assertEquals('user1',$val->getUid());
    }
}