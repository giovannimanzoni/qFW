<?php declare(strict_types=1);

namespace qFW\tests\unit\log;

use \PHPUnit\Framework\TestCase;
use qFW\log\SqlName;
use qFW\mvc\controller\lang\LangEn;

class TestSqlName extends TestCase
{

    private static $lang;

    /**
     * Setup class (Fixtures)
     */
    public static function setUpBeforeClass()
    {
        self::$lang = new LangEn();
    }

    public function testSqlName()
    {
        $val = new SqlName(123, self::$lang);
        $this->assertEquals('qFW\log\Sql\Sql', $val->getName());
    }

    public function testSqlNameUidInt()
    {
        $val = new SqlName(123, self::$lang);
        $this->assertEquals('123', $val->getUid());
    }

    public function testSqlNameUidString()
    {
        $val = new SqlName('user1', self::$lang);
        $this->assertEquals('user1', $val->getUid());
    }
}