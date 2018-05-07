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
use qFW\mvc\controller\lang\ILang;
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
 * Class FormDelete
 *
 * @package qFW\mvc\view\formUt
 */
class FormDelete
{
    /** @var \qFW\mvc\controller\lang\ILang */
    private $lang;

    /** @var \qFW\mvc\controller\dataTypes\UtString */
    private $utStr;

    /**
     * FormDelete constructor.
     *
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(ILang $lang)
    {
        $this->lang = $lang;
        $this->utStr = new UtString($lang);
    }

    /**
     * @param string $tbl
     * @param int    $id
     * @param string $action
     *
     * @return string
     * @throws \Exception
     */
    public function formElimina(string $tbl, int $id, string $action = ''): string
    {
        $logger = new HtmlName($_SESSION['uid'], $this->lang);
        // @codingStandardsIgnoreStart
        $page = (new FormPage($logger))
            ->addElement((new FormTitle('Vuoi eliminare questo record dal database ?', 2))
            )
            ->addElement((new FormInput('risolvoPin', new InputPassword(), true))
                             ->setLabel('Inserisci il pin')
                             ->setPrepend('fas fa-user-secret fa-lg')
                             ->setElementRowClass('col-xs-12 col-sm-4')
            )
            ->addElement((new FormInput('frmElimina', new InputHidden(), true))
                             ->setValue('1')
            )
            ->addElement((new FormInput('idElimina', new InputHidden(), true))
                             ->setValue($this->utStr->getCleanString($id))
            )
            ->addElement((new FormInput('tblElimina', new InputHidden(), true))
                             ->setValue($this->utStr->getCleanString($tbl))
            )
            ->addElement((new FormInput('Elimina', new InputSubmit(), false))
                             ->setValue('Elimina')
            );
        // @codingStandardsIgnoreEnd

        $formRecipe = (new FormObjBuilder($logger))
            ->addPage($page)
            ->build();

        $form1 = new FormObj(
            $formRecipe,
            $action,
            $logger
        );

        return $form1->show();
    }
}
