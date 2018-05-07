<?php declare(strict_types=1);

namespace qFW\tests\unit\log;

use \PHPUnit\Framework\TestCase;
use qFW\log\NoneName;
use qFW\mvc\controller\lang\LangEn;

class TestNoneName extends TestCase
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
        $val = new NoneName(123, self::$lang);
        $this->assertEquals('qFW\log\None\None', $val->getName());
    }

    public function testSqlNameUidInt()
    {
        $val = new NoneName(123, self::$lang);
        $this->assertEquals('123', $val->getUid());
    }

    public function testSqlNameUidString()
    {
        $val = new NoneName('user1', self::$lang);
        $this->assertEquals('user1', $val->getUid());
    }
}