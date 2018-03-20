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
    /**  */
    const DIM_ID_TBL = 11;

    /**  */
    const DIM_NOME_COGNOME = 50;

    /**  */
    const DIM_CF = 16;

    /**  */
    const DIM_PIVA = 13; // IT....

    /**  */
    const DIM_TEL = 18; // 0039 3929874563 + ALTRE 4 POSSIBILI CIFRE..

    /**  */
    const DIM_EMAIL = 50;

    /**  */
    const DIM_CAP = 5;

    /**  */
    const DIM_NAME_THING = 50;

    /**  */
    const DIM_LINK = 250;

    /**  */
    const DIM_LINK_TITLE = 100;

    /**  */
    const DIM_LINK_TEXT = 250;

    /**  */
    const DIM_IMG = 50;

    /**  */
    const DIM_PRICE = '19,4'; // https://stackoverflow.com/questions/13030368/best-data-type-to-store-money-values-in-mysql

    /**  */
    const DIM_DESCRIPTION = 1024;

    /**  */
    const DIM_DESCRIPTION_SHORT = 100;

    /**  */
    const DIM_CIVICO = 10;

    /**  */
    const DIM_PERCENTUALE = '6,3'; // ES: 25,700 ( 0,0 - 999,999 )

    /**  */
    const DIM_PERC = 7;

    /**  */
    const DIM_BANCA_APPOGGIO = 80;

    /**  */
    const DIM_IBAN = 27;

    /**  */
    const DIM_ABI = 5;

    /**  */
    const DIM_CAB = 5;

    /**  */
    const DIM_CC = 12;

    /**  */
    const TYPE_DATA = 'datetime';

    /**  */
    const TYPE_PRICE = 'decimal';

    /**  */
    const TYPE_CELL = 'varchar';

    /**  */
    const DEFAULT_DATA = 'NULL'; // https://stackoverflow.com/questions/1691117/how-to-store-null-values-in-datetime-fields-in-mysql

    /** ragione sociale */
    const DIM_RAG_SOC = 100;

    /**  */
    const DIM_INDIRIZZO = 250;

    /**  */
    const DIM_BANCA_BENEFICIARIO = 100;

    /**  */
    const DIM_PWD = 64; // @fixme con max lunghezza x libsodium

    /**  */
    const DIM_TARGA = 62;
}
