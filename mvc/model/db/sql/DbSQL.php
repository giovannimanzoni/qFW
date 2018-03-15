<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\model\db\sql;

/**
 * Class DbSQL
 *
 */
abstract class DbSQL
{
    /** @var \PDO hold connection */
    private static $pdo;

    /**
     * @return \PDO
     */
    public function getConn()
    {
        return self::$pdo;
    }

    /**
     * load and connect to db
     *
     * @param string $file
     */
    protected static function loadAndConnect(string $file)
    {
        $dbMysqlHost='';
        $port='';
        $dbName='';
        $dbUser='';
        $dbPwd='';
        require_once "$file";
        self::connect($dbMysqlHost, $port, $dbName, $dbUser, $dbPwd);
    }

    /**
     * Close connection
     *
     * @return bool
     */
    protected function close()
    {
        if ( self::$pdo ) {
            self::$pdo->close();
        }
        return true;
    }

    /**
     *
     * DbSQL constructor.
     */
    protected function __construct(){}

    /**
     * Magic method clone is empty to prevent duplication of connection
     */
    protected function __clone() {
        trigger_error('Cloning <em>DbSQL</em> is prohibited.', E_USER_ERROR);
    }

    /**
     * Connect to the db
     *
     * @param $dbMysqlHost
     * @param $port
     * @param $dbName
     * @param $dbUser
     * @param $dbPwd
     */
    private static function connect($dbMysqlHost,$port,$dbName,$dbUser,$dbPwd) {

        $dsn="mysql:host=$dbMysqlHost;port=$port;dbname=$dbName;charset=utf8";

        try {
            $opt = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            self::$pdo = new \PDO($dsn, $dbUser, $dbPwd, $opt);
        }
        catch (\PDOException $e) {


            if ($e->getCode() == 1045) {
                //@fixme
                die('Accesso non autorizzato. Controlla le credenziali nel file credenziali.php');
            } else {
                //@fixme
                die($e->getMessage());
            }
        }
    }
}
