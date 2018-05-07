<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\datatables;

/**
 * Class Datatables
 *
 * Template design pattern
 *
 * @package qFW\mvc\view\datatables
 */
abstract class Datatables
{
    /** @var array hold table columns */
    private $tblColumnName = array();

    /** @var string hold tabel id */
    private $tblId = '';

    /** @var string table body */
    protected $body = '';


    /**
     * Datatables constructor.
     *
     * @param array  $tblColumnName
     * @param string $tblId
     */
    public function __construct(array $tblColumnName, string $tblId)
    {
        $this->tblColumnName = $tblColumnName;
        $this->tblId = $tblId;
    }

    /**
     *
     * @return string
     */
    public function show()
    {
        return
            $this->tableHead() .
            $this->body .
            $this->closeTable();
    }

    /**
     * Generate javascript code for the table
     *
     * Put this given code at the end of the page
     *
     * @param string $ajax
     * @param int    $orderColumn
     * @param string $ordermode
     * @param string $addCode
     *
     * @return string
     */
    public function getTableJquery(
        string $ajax = '',
        int $orderColumn = 0,
        string $ordermode = 'desc',
        string $addCode = ''
    ): string {
        $html = '	
                <script>
                    $(document).ready(function() {
                        $("#' . $this->tblId . '").DataTable( {';
        if ($ajax != '') {
            $html .= '"ajax": \'' . $ajax . '\',';
        } else {
            /*Ok*/
        }
        if ($orderColumn > 0) {
            $html .= '"order": [[ ' . $orderColumn . ', "' . $ordermode . '" ]],';
        } else {
            /*Ok*/
        }

        $html .= $addCode . '
                            select: true
                        } );
                    } );
                </script> ';

        return $html;
    }

    /**
     * Set protected $body
     *
     * @param array $tblRows
     *
     * @return mixed
     */
    abstract protected function setDatatableBody(array $tblRows);

    /**
     * Generate head of the tables
     *
     * @return string
     */
    private function tableHead(): string
    {
        $html = "				<table id='{$this->tblId}' class='table table-striped table-bordered' width='100%' 
        cellspacing='0'><thead><tr>";

        foreach ($this->tblColumnName as $name) {
            $html .= "<th>$name</th>";
        }

        $html .= '</tr></thead><tbody>';

        return $html;
    }

    /**
     * Close the table
     *
     * @return string
     */
    private function closeTable(): string
    {
        return '</tbody></table>';
    }
}
