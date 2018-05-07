<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\payments\bancasellaGestpay;

use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\controller\lang\ILang;

/**
 *
 * Class BancaSellaGestPay
 *
 * @todo make abstract and test
 *
 * @package qFW\mvc\model\payments\bancasellaGestpay
 */
class BancaSellaGestPay
{
    /** @var string login */
    protected $shopLogin = '';

    /** @var int  transaction money value. Check http://api.gestpay.it/#currency-codes for the right one */
    protected $currency = 0;

    /** @var string  Transaction name */
    protected $shopTransactionID = '';

    /** @var float|int */
    protected $amount = 0.0;

    /** @var string Connection string */
    protected $wsdl = '';

    /** @var string Connection string */
    protected $action_pagamento = '';

    /** @var array For make connection string */
    protected $param = array();

    /** @var null  SoapClient() */
    protected $client = null;

    /** @var null */
    protected $objectResult = null;

    /** @var string Encoded secure string */
    protected $encString = '';

    /** @var string */
    protected $result = '';

    /**
     * BancaSellaGestPay constructor.
     *
     * @param string                         $shopLogin
     * @param string                         $shopTransactionID
     * @param float                          $amount
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(string $shopLogin, string $shopTransactionID, float $amount, ILang $lang)
    {
        $this->shopLogin = $shopLogin;
        $this->shopTransactionID = $shopTransactionID;
        $this->amount = $amount;
        $this->lang=$lang;

        // Default values
        $this->setEuroCurrency();
    }

    /**
     *
     */
    public function initPayment()
    {
        $this->makeUrlReq();
        $this->newInstance();
        $this->call();
        $this->checkForErrors();
        $this->createEncString();
    }

    /**
     * @todo make abstract
     *
     * @return string
     * @throws \Exception
     */
    public function show(): string
    {
        $utStr= new UtString($this->lang);
        $html = "
        La transazione sara registrata sul tuo estratto conto con id {$this->shopLogin} ed importo: "
            . $utStr->formatPrice($this->amount) . " &euro;
        <!--hidden form, with cyphered data to start the payment process -->
        <form
            name='pagamento'
            method='post'
            id='fpagam'
            action='{$this->action_pagamento}'>
            <input name='a' type='hidden' value='{$this->shopLogin}' />
            <input name='b' type='hidden' value='{$this->encString}' />
            <input style='width:90px;height:70px' type='submit' name='Pay' Value='Paga ora' />
        </form>";
        return $html;
    }

    /**
     * @return $this
     */
    public function setEuroCurrency()
    {
        $this->currency = '242';
        return $this;
    }

    /**
     * @return $this
     */
    public function setTestEnv()
    {
        $this->wsdl = "https://sandbox.gestpay.net/gestpay/gestpayws/WSCryptDecrypt.asmx?WSDL"; //TESTCODES
        $this->action_pagamento = "https://sandbox.gestpay.net/pagam/pagam.aspx";
        return $this;
    }

    /**
     * @return $this
     */
    public function setProductionTest()
    {
        $this->wsdl = "https://ecomms2s.sella.it/gestpay/gestpayws/WSCryptDecrypt.asmx?WSDL"; //PRODUCTION
        $this->action_pagamento = "https://ecomm.sella.it/pagam/pagam.aspx";

        return $this;
    }

    /**
     *
     */
    protected function makeUrlReq()
    {
        // Create the payment object array
        $this->param = array(
            'shopLogin'           => $this->shopLogin
            , 'uicCode'           => $this->currency
            , 'amount'            => $this->amount
            , 'shopTransactionId' => $this->shopTransactionID
        );
    }

    /**
     *
     */
    protected function newInstance()
    {
        // Instantiate a SoapClient from Gestpay Wsdl
        $this->client = new \SoapClient($this->wsdl);
        $this->objectResult = null;
    }

    /**
     *
     */
    protected function call()
    {

        // Do the call to Encrypt method
        try {
            $this->objectResult = $this->client->Encrypt($this->param);
        } // Catch SOAP exceptions
        catch (\SoapFault $fault) {
            trigger_error(
                "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})",
                E_USER_ERROR
            );
        }
    }

    /**
     *
     */
    protected function checkForErrors()
    {

        // Parse the XML result
        $this->result = simplexml_load_string($this->objectResult->EncryptResult->any);

        // If there is an error trying to contact Gestpay Server
        // (e.g. your IP address is not recognized, or the shopLogin is invalid) you'll see it here.

        $errCode = $this->result->ErrorCode;
        $errDesc = $this->result->ErrorDescription;
        if ($errCode != 0) {
            echo "<h2>Error: $errCode - $errDesc</h2>";
            echo '<h3>check the error in the <a href="http://api.gestpay.it/#errors">API</a></h3>';
            die();
        }
    }

    /**
     *
     */
    protected function createEncString()
    {

        // Finally, we will define the variable $encString that will contain a string to pass to Gestpay upon payment.
        // See the form below how it is used.
        $this->encString = $this->result->CryptDecryptString;
    }
}
