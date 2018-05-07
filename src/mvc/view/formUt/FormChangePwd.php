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

use qFW\log\HtmlName;
use qFW\mvc\controller\lang\ILang;
use qFW\mvc\view\form\elements\FormInput;
use qFW\mvc\view\form\elements\FormText;
use qFW\mvc\view\form\elements\FormTitle;
use qFW\mvc\view\form\elements\input\InputHidden;
use qFW\mvc\view\form\elements\input\InputPassword;
use qFW\mvc\view\form\elements\input\InputSubmit;
use qFW\mvc\view\form\FormObj;
use qFW\mvc\view\form\FormObjBuilder;
use qFW\mvc\view\form\FormPage;

/**
 * Class FormChangePwd
 *
 * @package qFW\mvc\view\formUt
 */
class FormChangePwd
{
    /** @var \qFW\mvc\controller\lang\ILang */
    private $lang;

    /**
     * FormChangePwd constructor.
     *
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(ILang $lang)
    {
        $this->lang = $lang;
    }

    /**
     * @param string $actionPage
     *
     * @return string
     */
    public function frmChangePwd(string $actionPage)
    {

        $logger = new HtmlName($_SESSION['uid'], $this->lang);
        // @codingStandardsIgnoreStart
        $page = (new FormPage($logger))
            ->addElement((new FormTitle('Cambio password', 2)))
            ->addElement((new FormText('<b>Password attuale*</b>'))
                             ->setElementRowClass('col-xs-12 col-sm-4')
            )
            ->addElement((new FormInput('pwdNow', new InputPassword(), true))
                             ->setPrepend('fa fa-unlock')
                             ->setElementRowClass('col-xs-12 col-sm-8')
                             ->setMaxLength(50)
            )
            ->addElement((new FormText('<b>Nuova password*</b>'))
                             ->setElementRowClass('col-xs-12 col-sm-4')
            )
            ->addElement((new FormInput('pwdNew', new InputPassword(), true))
                             ->setPrepend('fa fa-unlock')
                             ->setElementRowClass('col-xs-12 col-sm-8')
                             ->setMaxLength(50)
            )
            ->addElement((new FormText('<b>Conferma password*</b>'))
                             ->setElementRowClass('col-xs-12 col-sm-4')
            )
            ->addElement((new FormInput('pwdNew2', new InputPassword(), true))
                             ->setPrepend('fa fa-unlock')
                             ->setElementRowClass('col-xs-12 col-sm-8')
                             ->setMaxLength(50)
            )
            ->addElement((new FormText(' '))
                             ->setElementRowClass('hidden-xs col-sm-4')
            )
            ->addElement((new FormText('<i>La password deve contenere almeno 8 caratteri, una lettera maiuscola e una lettera minuscola.</i>'))
                             ->setElementRowClass('col-xs-12 col-sm-8')
            )
            ->addElement((new FormInput('uid', new InputHidden(), true))
                             ->setValue($_SESSION['uid'])
                             ->setHidden()
            )
            ->addElement((new FormInput('frmCambiaPwd', new InputHidden(), true))
                             ->setValue('1')
                             ->setHidden()
            )
            ->addElement((new FormInput('cambiaPassword', new InputSubmit(), false))
                             ->setValue('Cambia password')
            )
        ;
        // @codingStandardsIgnoreEnd

        $formRecipe = (new FormObjBuilder($logger))
            ->addPage($page)
            ->build();

        $form1 = new FormObj(
            $formRecipe,
            $actionPage,
            $logger
        );

        return $form1->show();
    }
}
