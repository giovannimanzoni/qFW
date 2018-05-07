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

use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\controller\lang\ILang;

/**
 * Class Query
 *
 * @package qFW\mvc\controller\db
 */
class Query
{
    /** @var null|\PDO */
    protected $pdo = null;

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    /** @var \qFW\mvc\controller\dataTypes\UtString */
    private $utStr;

    /**
     * Query constructor.
     *
     * @param \PDO                           $pdo
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(\PDO $pdo, ILang $lang)
    {
        $this->pdo = $pdo;
        $vocLang = 'qFW\mvc\controller\vocabulary\Voc' . $lang->getLang();
        $this->voc = new $vocLang();
        $this->utStr = new UtString($lang);
    }

    /**
     * Get start from table
     *
     * @param string $tbl
     * @param string $orderby
     *
     * @return array
     * @throws \Exception
     */
    public function getStar(string $tbl, $orderby = ''): array
    {
        $res = array();

        try {
            $sql = 'SELECT * FROM ' . $this->formatIdentifier($tbl);

            if ($orderby != '') {
                $sql .= " ORDER BY $orderby";
            } else {
                /*Ok*/
            }

            $result = $this->pdo->prepare($sql);
            $result->execute();
            $res = $result->fetchAll();
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }
        return $res;
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $table
     * @param string $orderby
     *
     * @return array
     * @throws \Exception
     */
    public function fetchKeyPair(string $key, string $value, string $table, string $orderby = '2'): array
    {
        $data = array();

        $sql = 'SELECT ' . $this->formatIdentifier($key) . ', ' . $this->formatIdentifier($value)
            . ' as value from '
            . $this->formatIdentifier($table) . " ORDER BY $orderby";

        try {
            $result = $this->pdo->query($sql);
            $data = $result->fetchAll(\PDO::FETCH_KEY_PAIR);
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }

        return $data;
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $table
     * @param string $orderby
     * @param string $whereColName
     * @param        $whereColValue
     *
     * @return array
     * @throws \Exception
     */
    public function fetchKeyPairWhere(
        string $key,
        string $value,
        string $table,
        string $orderby,
        string $whereColName,
        $whereColValue
    ): array {
        $data = array();

        $key = $this->formatIdentifier($key);
        $value = $this->formatIdentifier($value);
        $table = $this->formatIdentifier($table);
        $whereColName = $this->formatIdentifier($whereColName);

        try {
            $sql = "SELECT $key, $value as value from $table WHERE $whereColName = :colValue ORDER BY $orderby";
            $result = $this->pdo->prepare($sql);
            if (is_int($whereColValue)) {
                $result->bindValue(':colValue', $whereColValue, \PDO::PARAM_INT);
            } elseif (is_string($whereColValue)) {
                $result->bindValue(':colValue', $whereColValue, \PDO::PARAM_STR);
            } else {
                throw new \Exception('Where value is not string nor int type.');
            }
            $result->execute();

            $data = $result->fetchAll(\PDO::FETCH_KEY_PAIR);
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }

        return $data;
    }

    /**
     * Run select query with pre prepared sql code
     *
     * @param string $tbl
     * @param string $value
     *
     * @return array
     * @throws \Exception
     */
    public function getValues(string $tbl, string $value): array
    {
        $res = array();

        try {
            // Backticks applied on value during the function call
            $sql = "SELECT $value from " . $this->formatIdentifier($tbl);
            $result = $this->pdo->query($sql);
            $res = $result->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }

        return $res;
    }

    /**
     * @param string $tbl
     * @param string $select
     * @param        $whereIdName
     *
     * @return float|int|mixed|string
     * @throws \Exception
     */
    public function getValueWhere(string $tbl, string $select, $whereIdName)
    {
        $sql = '';

        try {
            if (is_numeric($whereIdName)) {
                $sql = 'SELECT ' . $this->formatIdentifier($select) . ' from ' .
                    $this->formatIdentifier($tbl) . ' WHERE `id` = :where LIMIT 1';
            } else {
                $sql = 'SELECT ' . $this->formatIdentifier($select) .
                    ' from ' . $this->formatIdentifier($tbl) . ' WHERE `c_name` = :where LIMIT 1';
            }

            $result = $this->pdo->prepare($sql);

            if (is_numeric($whereIdName)) {
                $result->bindValue(':where', $whereIdName, \PDO::PARAM_INT);
            } else {
                $result->bindValue(':where', $whereIdName, \PDO::PARAM_STR);
            }
            $result->execute();
            $str = $result->fetchColumn();
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }

        if ($str === false) {
            $str = ''; // If it does not find references, it returns an empty string
        } else {
            /*Ok*/
        }

        if (is_numeric($str)) {
            $tryFloat = floatval($str);
            $tryInt = intval($str);
            if ($this->utStr->areEqual($tryFloat, $tryInt)) {
                // It preserves the initial zeroes if present returning a string, otherwise it returns number
                //    - https://www.askingbox.com/tutorial/php-get-first-digit-of-number-or-string
                $tmp = $str . '';
                if ($tmp[0] != '0') {
                    $str = intval($str);
                } else {
                    /*Ok*/
                }
            } else {
                $str = floatval($str);
            }
        }

        return $str; // Can be string, integer or null
    }

    /**
     * @param string $tbl
     * @param string $select
     * @param        $whereIdName
     * @param string $format
     *
     * @return float|int|mixed|string
     * @throws \Exception
     */
    public function getDataWhere(string $tbl, string $select, $whereIdName, string $format = "%d/%m/%Y")
    {
        try {
            if (is_numeric($whereIdName)) {
                $sql = 'SELECT DATE_FORMAT(' . $this->formatIdentifier($select) . ',"'
                    . $format . '") from ' . $this->formatIdentifier($tbl) . ' WHERE `id` = :where LIMIT 1';
            } elseif (is_string($whereIdName)) {
                $sql = 'SELECT DATE_FORMAT(' . $this->formatIdentifier($select) . ',"' . $format . '") from '
                    . $this->formatIdentifier($tbl) . ' WHERE `c_name` = :where LIMIT 1';
            } else {
                throw new \Exception('$whereIdName not int nor string type.');
            }

            $result = $this->pdo->prepare($sql);

            if (is_numeric($whereIdName)) {
                $result->bindValue(':where', $whereIdName, \PDO::PARAM_INT);
            } else {
                $result->bindValue(':where', $whereIdName, \PDO::PARAM_STR);
            }
            $result->execute();
            $str = $result->fetchColumn();
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }

        if ($str === false) {
            $str = ''; // If it does not find references, it returns an empty string
        } elseif (is_numeric($str)) {
            $tryFloat = floatval($str);
            $tryInt = intval($str);
            if ($this->utStr->areEqual($tryFloat, $tryInt)) {
                $str = intval($str);
            } else {
                $str = floatval($str);
            }
        } else {
            /*Ok*/
        }

        return $str; // Can be string, integer or null
    }

    /**
     * @param string $tbl
     * @param string $colDestinazione
     * @param        $rigaRiferimento
     * @param        $valore
     *
     * @return bool
     * @throws \Exception
     */
    public function updateValueWhere(string $tbl, string $colDestinazione, $rigaRiferimento, $valore): bool
    {
        $res = false;

        try {
            if (is_numeric($rigaRiferimento)) {
                $sql = 'UPDATE ' . $this->formatIdentifier($tbl)
                    . ' SET ' . $this->formatIdentifier($colDestinazione)
                    . " = '$valore' WHERE `id` = :where LIMIT 1";
            } elseif (is_string($rigaRiferimento)) {
                $sql = 'UPDATE ' . $this->formatIdentifier($tbl)
                    . ' SET ' . $this->formatIdentifier($colDestinazione)
                    . " = '$valore' WHERE `c_name` = :where LIMIT 1";
            } else {
                throw new \Exception('$rigaRiferimento not int nor string type.');
            }

            $result = $this->pdo->prepare($sql);

            if (is_numeric($rigaRiferimento)) {
                $result->bindValue(':where', $rigaRiferimento, \PDO::PARAM_INT);
            } else {
                $result->bindValue(':where', $rigaRiferimento, \PDO::PARAM_STR);
            }

            $result->execute();
            $res = true;
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }
        return $res;
    }

    /**
     * @param string $tbl
     * @param string $orderby
     *
     * @return array
     * @throws \Exception
     */
    public function getStarEnabled(string $tbl, $orderby = ''): array
    {
        $res = array();

        try {
            $sql = "SELECT  * FROM " . $this->formatIdentifier($tbl) . ' WHERE `enabled` = "1"';

            if ($orderby != '') {
                $sql .= " ORDER BY $orderby";
            } else {
                /*Ok*/
            }

            $result = $this->pdo->prepare($sql);
            $result->execute();
            $res = $result->fetchAll();
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }

        return $res;
    }

    /**
     * @param string $tbl
     * @param string $whereName
     * @param        $whereValue
     * @param string $orderby
     *
     * @return array
     * @throws \Exception
     */
    public function getStarWhere(string $tbl, string $whereName, $whereValue, $orderby = ''): array
    {
        $res = array();

        try {
            $sql = 'SELECT * from ' . $this->formatIdentifier($tbl)
                . ' WHERE ' . $this->formatIdentifier($whereName) . " = $whereValue";

            if ($orderby != '') {
                $sql .= " ORDER BY $orderby";
            } else {
                /*Ok*/
            }

            $result = $this->pdo->prepare($sql);
            $result->execute();
            $res = $result->fetchAll();
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }
        return $res;
    }

    /**
     * @param array $sql
     *
     * @throws \Exception
     */
    public function doSql(array $sql)
    {

        foreach ($sql as $i => $query) {
            try {
                $s = $this->pdo->prepare($query);
                $s->execute();
                echo "Performed query $i<br>";
            } catch (\PDOException $e) {
                $this->pdoExceptionM($e, __METHOD__);
            }
        }
    }

    /**
     * @param $id
     * @param $tbl
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteById($id, $tbl): bool
    {
        $res = false;

        try {
            $sql = 'DELETE FROM ' . $this->formatIdentifier($tbl) . '
			WHERE
			`id` = :id';

            $s = $this->pdo->prepare($sql);

            $s->bindValue(':id', $id, \PDO::PARAM_INT);

            $s->execute();
            $res = true;
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }
        return $res;
    }

    /**
     * @param string $tbl
     * @param string $column
     *
     * @return int|mixed
     * @throws \Exception
     */
    public function sumColumnPricesVarchar(string $tbl, string $column)
    {
        $sum = 0;
        $sql = 'SELECT ROUND(SUM(   cast(REPLACE('
            . $this->formatIdentifier($column) . ', ",", ".") as decimal(19,2))   ),2) from '
            . $this->formatIdentifier($tbl);

        try {
            $s = $this->pdo->prepare($sql);
            $s->execute();
            $sum = $s->fetch(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            $this->pdoExceptionM($e, __METHOD__);
        }
        return $sum;
    }

    /**
     * Manage errors for pdo exception
     *
     * @param \PDOException $e
     * @param string        $functionName
     *
     * @throws \Exception
     */
    public function pdoExceptionM(\PDOException $e, string $functionName)
    {
        $errMex = '';
        $code = $e->getCode();

        if ($code == 23000) {
            $errMex = "$functionName() impossible to insert by single key constraint.";
        } else {
            $errMex = "$functionName() {$e->getMessage()}";
        }

        if ($code != 1142) {
            // Permits not sufficient to operate on the table.
            // If in transaction, rollback. obviously it did not work on the table if the code was 1142 and the
            //      rollback would fail
            if ($this->pdo->inTransaction()) {
                $errMex .= $this->voc->dbStateRestore();
                $this->pdo->rollBack();
            }
        }

        throw new \Exception($errMex);
    }

    /**
     * @param \PDOException $e
     * @param string        $functionName
     */
    public function pdoExceptionMCli(\PDOException $e, string $functionName)
    {
        $code = $e->getCode();

        if ($code == 23000) {
            echo "$functionName() " . $this->voc->queryErr23000();
        } else {
            echo $functionName . '() ' . $e->getMessage();
        }

        if ($code != 1142) {
            // Permits not sufficient to operate on the table.
            // If in transaction, rollback. obviously it did not work on the table if the code was 1142 and the
            //      rollback would fail
            if ($this->pdo->inTransaction()) {
                echo $this->voc->dbStateRestore();
                $this->pdo->rollBack();
            }
        } else {
            /*Ok*/
        }
    }

    /**
     *
     */
    public function tryCommit()
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        } else {
            /*Ok*/
        }
    }

    /**
     * @param string $identifier
     *
     * @return string
     */
    private function formatIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
}
