<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\model\db;

use PHPUnit\Framework\TestCase;


/**
 * Class DatabaseTest
 *
 * @package qFW\tests\unit\mvc\model\db
 */
class DatabaseTest extends TestCase
{
    /** @var */
    protected static $dbh;

    /**
     * Example for setup db connection test in class
     */
    public static function setUpBeforeClass()
    {
        self::$dbh = new \PDO('sqlite::memory:');
    }

    /**
     * Called after this class tests
     */
    public static function tearDownAfterClass()
    {
        self::$dbh = null;
    }

    /**
     * method for pass phpunit without unit test
     */
    public function testOne()
    {
        $this->assertTrue(true);
    }
}
