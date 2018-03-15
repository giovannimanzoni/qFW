<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\view\formUt;

use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\view\form\elements\FormInput;
use qFW\mvc\view\form\elements\FormTitle;
use qFW\mvc\view\form\elements\input\InputHidden;
use qFW\mvc\view\form\elements\input\InputPassword;
use qFW\mvc\view\form\elements\input\InputSubmit;
use qFW\log\HtmlName;
use qFW\mvc\view\form\FormObj;
use qFW\mvc\view\form\FormObjBuilder;
use qFW\mvc\view\form\FormPage;

/**
 * Class FormUt
 *
 * @package qFW\mvc\view\formUt
 */
class FormElimina
{

    /**
     * @param string $tbl
     * @param int    $id
     * @param string $action
     *
     * @return string
     * @throws \Exception
     */
    public static function formElimina(string $tbl, int $id, string $action=''): string
    {

        $logger= new HtmlName($_SESSION['uid']);

        $page=(new FormPage(' ', $logger))
            ->addElement((new FormTitle('Vuoi eliminare questo record dal database ?',2))
            )
            ->addElement((new FormInput('risolvoPin',new InputPassword(),true))
                             ->setLabel('Inserisci il pin')
                             ->setPrepend('fas fa-user-secret fa-lg')
                             ->setElementRowClass('col-xs-12 col-sm-4')
            )
            ->addElement((new FormInput('frmElimina',new InputHidden(),true))
                             ->setValue('1')
            )
            ->addElement((new FormInput('idElimina',new InputHidden(),true))
                             ->setValue(UtString::getCleanString($id))
            )
            ->addElement((new FormInput('tblElimina',new InputHidden(),true))
                             ->setValue(UtString::getCleanString($tbl))
            )
            ->addElement((new FormInput('Elimina',new InputSubmit(), false))
                             ->setValue('Elimina')
            )
        ;

        $formRecipe=(new FormObjBuilder($logger))
            ->addPage($page)
            ->build()
        ;

        $form1= new FormObj('frmEliminaById',$formRecipe,$action,$logger);

        return $form1->show();
    }
}
