<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\template\content;

use qFW\log\HtmlName;
use qFW\mvc\view\form\elements\input\InputSubmit;
use qFW\mvc\view\form\elements\input\InputText;
use qFW\mvc\view\form\elements\FormInput;
use qFW\mvc\view\form\elements\input\InputHidden;
use qFW\mvc\view\form\FormObj;
use qFW\mvc\view\form\FormObjBuilder;
use qFW\mvc\view\form\FormPage;

/**
 * Class LoginTpl
 *
 * @package qFW\mvc\view\template\content
 */
class LoginTpl extends CHtml implements ITplLogin
{
    /** @var \qFW\log\HtmlName logger engine */
    private $logger;

    /**
     * LoginTpl constructor.
     *
     * @param string $htmlPwdDimenticata
     */
    public function __construct(string $htmlPwdDimenticata)
    {
        $this->logger = new HtmlName(0);

        $this->html =
            '<div class="row">
                <div class="col-xs-12">
                    <div class="login-box">
                        <h2>Login</h2>';
        $this->html .= $this->formLogin();
        $this->html .= '
                        <div class="clearfix"></div>
                        <hr>
                        <h3>Password dimenticata?</h3>
                        <p>
                            ' . $htmlPwdDimenticata . '
                        </p>
                    </div>
                </div>
            </div>';
    }

    /**
     * Login form
     *
     * @return string
     */
    private function formLogin(): string
    {
        $page = new FormPage(' ', $this->logger);
        // @codingStandardsIgnoreStart
        $page
            ->addElement((new FormInput('username', new InputText(), true))
                             ->setLabel('Username')
                             ->setPrepend('fa fa-user')
                             ->setElementRowClass('col-xs-12')
            )
            ->addElement((new FormInput('password', new InputText(), true))
                             ->setLabel('Password')
                             ->setPrepend('fa fa-eye-slash')
                             ->setElementRowClass('col-xs-12')
            )
            ->addElement((new FormInput('formLogin', new InputHidden(), true))
                             ->setValue('1')
                             ->setElementRowClass('col-xs-12')
                             ->setHidden()
            )
            ->addElement((new FormInput('login', new InputSubmit(), false))
                             ->setValue('Login')
            );
        // @codingStandardsIgnoreEnd
        $formRecipe = (new FormObjBuilder($this->logger))
            ->addPage($page)
            ->build();

        $form = new FormObj('frmLogin', $formRecipe, '/trylogin.php', $this->logger);

        return $form->show();
    }
}
