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

/**
 * Class Query
 *
 * @package qFW\mvc\controller\db
 */
class Query
{
    /** @var null|\PDO */
    protected $pdo = null;

    /**
     * Query constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get start from table
     *
     * @param string $tbl
     * @param string $orderby
     *
     * @return array
     */
    public function getStar(string $tbl, $orderby = ''): array
    {

        $valori = array();

        try {
            $sql = 'SELECT * FROM ' . self::formatIdentifier($tbl);

            if ($orderby != '') {
                $sql .= " ORDER BY $orderby";
            }

            $result = $this->pdo->prepare($sql);
            $result->execute();
            $valori = $result->fetchAll();
        } catch (\PDOException $e) {
            self::pdoExceptionM($e, __METHOD__);
        }
        return $valori;
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @param string $table
     * @param string $orderby
     *
     * @return array
     */
    public function fetchKeyPair(string $key, string $value, string $table, string $orderby = '2'): array
    {
        $data = array();

        try {
            $sql = 'SELECT ' . self::formatIdentifier($key) . ', ' . self::formatIdentifier($value) . ' as valore from '
                . self::formatIdentifier($table) . " ORDER BY $orderby";
            $result = $this->pdo->query($sql);
            $data = $result->fetchAll(\PDO::FETCH_KEY_PAIR);
        } catch (\PDOException $e) {
            self::pdoExceptionM($e, __METHOD__);
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

        $key = self::formatIdentifier($key);
        $value = self::formatIdentifier($value);
        $table = self::formatIdentifier($table);
        $whereColName = self::formatIdentifier($whereColName);

        try {
            $sql = "SELECT $key, $value as valore from $table WHERE $whereColName = :colValue ORDER BY $orderby";
            $result = $this->pdo->prepare($sql);
            if (is_int($whereColValue)) {
                $result->bindValue(':colValue', $whereColValue, \PDO::PARAM_INT);
            } elseif (is_string($whereColValue)) {
                $result->bindValue(':colValue', $whereColValue, \PDO::PARAM_STR);
            }
            $result->execute();

            $data = $result->fetchAll(\PDO::FETCH_KEY_PAIR);
        } catch (\PDOException $e) {
            self::pdoExceptionM($e, __METHOD__);
        }

        return $data;
    }

    /**
     * Funzione di utility per eseguire query select con codice già impostato
     *
     * @param string $tbl
     * @param string $valore
     *
     * @return array
     */
    public function getValues(string $tbl, string $valore): array
    {
        $valori = array();

        try {
            // backticks applicato su valore in fase di chiamata della funzione
            $sql = "SELECT $valore from " . self::formatIdentifier($tbl);
            $result = $this->pdo->query($sql);
            $valori = $result->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            self::pdoExceptionM($e, __METHOD__);
        }

        return $valori;
    }

    /**
     * @param string $tbl
     * @param string $select
     * @param        $whereIdName
     *
     * @return float|int|string
     */
    public function getValueWhere(string $tbl, string $select, $whereIdName)
    {
        $sql = '';

        try {
            if (is_numeric($whereIdName)) {
                $sql = 'SELECT ' . self::formatIdentifier($select) . ' from ' .
                    self::formatIdentifier($tbl) . ' WHERE `id` = :where LIMIT 1';
            } else {
                $sql = 'SELECT ' . self::formatIdentifier($select) .
                    ' from ' . self::formatIdentifier($tbl) . ' WHERE `nome` = :where LIMIT 1';
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
            self::pdoExceptionM($e, __METHOD__);
        }

        if ($str === false) {
            $str = '';
        } // se non trova riferimenti, ritorna stringa vuota

        if (is_numeric($str)) {
            $tryFloat = floatval($str);
            $tryInt = intval($str);
            if (UtString::areEqual($tryFloat, $tryInt)) {
                // preserva gli zeri iniziali se presenti tornando una stringa, altrimenti torna numero
                //    - https://www.askingbox.com/tutorial/php-get-first-digit-of-number-or-string
                $tmp = $str . '';
                if ($tmp[0] != '0') {
                    $str = intval($str);
                }
            } else {
                $str = floatval($str);
            }
        }

        return $str; // can be string, integer, null
    }

    /**
     * @param string $tbl
     * @param string $select
     * @param        $whereIdName
     * @param string $format
     *
     * @return float|int|string
     */
    public function getDataWhere(string $tbl, string $select, $whereIdName, string $format = "%d/%m/%Y")
    {
        try {
            if (is_numeric($whereIdName)) {
                $sql = 'SELECT DATE_FORMAT(' . self::formatIdentifier($select) . ',"'
                    . $format . '") from ' . self::formatIdentifier($tbl) . ' WHERE `id` = :where LIMIT 1';
            } else {
                $sql = 'SELECT DATE_FORMAT(' . self::formatIdentifier($select) . ',"' . $format . '") from '
                    . self::formatIdentifier($tbl) . ' WHERE `nome` = :where LIMIT 1';
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
            self::pdoExceptionM($e, __METHOD__);
        }

        if ($str === false) {
            $str = '';
        } // se non trova riferimenti, ritorna stringa vuota

        if (is_numeric($str)) {
            $tryFloat = floatval($str);
            $tryInt = intval($str);
            if (UtString::areEqual($tryFloat, $tryInt)) {
                $str = intval($str);
            } else {
                $str = floatval($str);
            }
        }

        return $str; // can be string, integer, null
    }

    /**
     * @param string $tbl
     * @param string $colDestinazione
     * @param        $rigaRiferimento
     * @param        $valore
     *
     * @return bool
     */
    public function updateValueWhere(string $tbl, string $colDestinazione, $rigaRiferimento, $valore): bool
    {
        $esito = false;

        try {
            if (is_numeric($rigaRiferimento)) {
                $sql = 'UPDATE ' . self::formatIdentifier($tbl)
                    . ' SET ' . self::formatIdentifier($colDestinazione)
                    . " = '$valore' WHERE `id` = :where LIMIT 1";
            } else {
                $sql = 'UPDATE ' . self::formatIdentifier($tbl)
                    . ' SET ' . self::formatIdentifier($colDestinazione)
                    . " = '$valore' WHERE `nome` = :where LIMIT 1";
            }

            $result = $this->pdo->prepare($sql);

            //TODO: penso sia una cagata questa... fare il bind in base al tipo di dato.. dovrei fare il bind a priori.
            if (is_numeric($rigaRiferimento)) {
                $result->bindValue(':where', $rigaRiferimento, \PDO::PARAM_INT);
            } else {
                $result->bindValue(':where', $rigaRiferimento, \PDO::PARAM_STR);
            }

            $result->execute();
            $esito = true;
        } catch (\PDOException $e) {
            self::pdoExceptionM($e, __METHOD__);
        }
        return $esito;
    }

    /**
     * @param string $tbl
     * @param string $orderby
     *
     * @return array
     */
    public function getStarEnabled(string $tbl, $orderby = ''): array
    {
        $valori = array();

        try {
            $sql = "SELECT  * FROM " . self::formatIdentifier($tbl) . ' WHERE `enabled` = "1"';

            if ($orderby != '') {
                $sql .= " ORDER BY $orderby";
            }


            $result = $this->pdo->prepare($sql);
            $result->execute();
            $valori = $result->fetchAll();
        } catch (\PDOException $e) {
            self::pdoExceptionM($e, __METHOD__);
        }

        return $valori;
    }

    /**
     * @param string $tbl
     * @param string $whereName
     * @param        $whereValue
     * @param string $orderby
     *
     * @return array
     */
    public function getStarWhere(string $tbl, string $whereName, $whereValue, $orderby = ''): array
    {
        $valori = array();

        try {
            $sql = 'SELECT * from ' . self::formatIdentifier($tbl)
                . ' WHERE ' . self::formatIdentifier($whereName) . " = $whereValue";

            if ($orderby != '') {
                $sql .= " ORDER BY $orderby";
            }

            $result = $this->pdo->prepare($sql);
            $result->execute();
            $valori = $result->fetchAll();
        } catch (\PDOException $e) {
            self::pdoExceptionM($e, __METHOD__);
        }
        return $valori;
    }

    /**
     * @param array $sql
     */
    public function doSql(array $sql)
    {

        foreach ($sql as $i => $query) {
            try {
                $s = $this->pdo->prepare($query);
                $s->execute();
                echo "Eseguita query $i<br>";
            } catch (\PDOException $e) {
                self::pdoExceptionM($e, __METHOD__);
            }
        }
    }

    /**
     * @param $id
     * @param $tbl
     *
     * @return bool
     */
    public function eliminaById($id, $tbl): bool
    {
        $esito = false;

        try {
            $sql = 'DELETE FROM ' . self::formatIdentifier($tbl) . '
			WHERE
			`id` = :id';

            $s = $this->pdo->prepare($sql);

            $s->bindValue(':id', $id, \PDO::PARAM_INT);

            $s->execute();
            $esito = true;
        } catch (\PDOException $e) {
            self::pdoExceptionM($e, __METHOD__);
        }
        return $esito;
    }

    /**
     * @param string $tbl
     * @param string $column
     *
     * @return int
     */
    public function sommaColonnaPrezziVarchar(string $tbl, string $column)
    {
        $sum = 0;
        $sql = 'SELECT ROUND(SUM(   cast(REPLACE('
            . self::formatIdentifier($column) . ', ",", ".") as decimal(19,2))   ),2) from '
            . self::formatIdentifier($tbl);

        try {
            $s = $this->pdo->prepare($sql);
            $s->execute();
            $sum = $s->fetch(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            self::pdoExceptionM($e, __METHOD__);
        }
        return $sum;
    }

    /**
     * Manage errors for pdo exception
     *
     * @param \PDOException $e
     * @param string        $functionName
     */
    public function pdoExceptionM(\PDOException $e, string $functionName)
    {
        $code = $e->getCode();

        if ($code == 23000) { // impossibile inserire per vincolo di chiave unica
            $_SESSION['err'] .= "$functionName() Inserimento di informazioni già presenti.";
        } else {
            $_SESSION['err'] .= $functionName . '() ' . $e->getMessage();
        }

        if ($code != 1142) { // permessi non sufficenti per operare sulla tabella
            // se in transaction, rollback. ovviamente non ha operato sulla tabella se il code fosse 1142 e il
            // rollback fallirebbe
            if ($this->pdo->inTransaction()) {
                $_SESSION['err'] .= 'Ripristino stato database eseguito.';
                $this->pdo->rollBack();
            }
        }
    }

    /**
     * @param \PDOException $e
     * @param string        $functionName
     */
    public function pdoExceptionMCli(\PDOException $e, string $functionName)
    {
        $code = $e->getCode();

        if ($code == 23000) { // impossibile inserire per vincolo di chiave unica
            echo "$functionName() Inserimento di informazioni già presenti.";
        } else {
            echo $functionName . '() ' . $e->getMessage();
        }

        if ($code != 1142) { // permessi non sufficenti per operare sulla tabella
            // se in transaction, rollback. ovviamente non ha operato sulla tabella se il code fosse 1142 e il
            // rollback fallirebbe
            if ($this->pdo->inTransaction()) {
                echo 'Ripristino stato database eseguito.';
                $this->pdo->rollBack();
            }
        }
    }

    /**
     *
     */
    public function tryCommit()
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
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
