<?php declare(strict_types=1);

namespace App\mvc\datatables;

use qFW\mvc\view\datatables\Datatables;

/**
 * Class TableDemo
 *
 * @package App
 */
class TableDemo extends Datatables
{

    /**
     * TableDemo constructor.
     *
     */
    public function __construct()
    {
        $tblColumnName = array('Row', 'Name', 'Qty', 'Description', 'SKU', 'User');

        parent::__construct($tblColumnName, 'tableDemo');
    }

    /**
     * @param array $tblRows
     *
     * @return mixed|void
     */
    public function setDatatableBody(array $tblRows)
    {
        $html='';

        foreach ($tblRows as $row) {
            $html .= "  
                    <tr>
                    <td>{$row['row']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['qty']}</td>
                    <td>{$row['desc']}</td>
                    <td>{$row['sku']}</td>
                    <td>{$row['user']}</td>
                    </tr>";
        }

        $this->body=$html;
    }
}
