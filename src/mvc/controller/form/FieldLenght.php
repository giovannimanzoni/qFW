<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\controller\form;

/**
 * Class FieldLenght
 *
 * @package qFW\mvc\controller\form
 */
class FieldLenght
{
    /** @var int  */
    const DIM_ID_TBL = 11;

    /** @var int  */
    const DIM_NAME_SURNAME = 50;

    /** @var int Italian Fiscal Code */
    const DIM_CF = 16;

    /** @var int VAT number Ex.: IT.... */
    const DIM_PIVA = 13;

    /** @var int  VAT number Ex.: IT....*/
    const DIM_VAT = 13;

   /** @var int  Ex.: 0039 3929874563 + other possible numbers*/
    const DIM_TEL = 18;

    /** @var int  */
    const DIM_EMAIL = 50;

    /** @var int  */
    const DIM_CAP = 5;

    /** @var int  */
    const DIM_NAME_THING = 50;

    /** @var int  */
    const DIM_LINK = 250;

   /** @var int  */
    const DIM_LINK_TITLE = 100;

    /** @var int  */
    const DIM_LINK_TEXT = 250;

    /** @var int  */
    const DIM_IMG = 50;

    /** @var string  */
    const DIM_PRICE = '19,4'; // https://stackoverflow.com/questions/13030368/best-data-type-to-store-money-values-in-mysql

    /** @var int  */
    const DIM_DESCRIPTION = 1024;

    /** @var int  */
    const DIM_DESCRIPTION_SHORT = 100;

    /** @var int  */
    const DIM_STREET_NUMBER = 10;

    /** @var string  */
    const DIM_PERCENT = '6,3'; // ES: 25,700 ( 0,0 - 999,999 )

    /** @var int  */
    const DIM_PERC = 7;

    /** @var int  */
    const DIM_BANK_NAME = 80;

    /** @var int  */
    const DIM_IBAN = 27;

    /** @var int  */
    const DIM_ABI = 5;

    /** @var int  */
    const DIM_CAB = 5;

    /** @var int  */
    const DIM_CC = 12;

    /** @var string  */
    const TYPE_DATA = 'datetime';

    /** @var string  */
    const TYPE_PRICE = 'decimal';

    /** @var string  */
    const TYPE_CELL = 'varchar';

    /** @var string  */
    const DEFAULT_DATA = 'NULL'; // https://stackoverflow.com/questions/1691117/how-to-store-null-values-in-datetime-fields-in-mysql

    /** @var int  */
    const DIM_COMPANY_NAME = 100;

    /** @var int  */
    const DIM_ADDRESS = 100; // Street address, place...

    /** @var int  Name of the owner of bank account */
    const DIM_BANK_OWNER = 100;

    /**  This lenght is only for resonable form input check, it will be saved in database as hash, not as plain text.*/
    const DIM_PWD = 64;

    /** @var int  */
    const DIM_CAR_ID = 62;

    /** @var int FOR DD/MM/YYYY */
    const DIM_DATE1 = 10;

    /** @var int  */
    const DIM_COUNTRY = 50;
}
