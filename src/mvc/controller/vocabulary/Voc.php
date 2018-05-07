<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\controller\vocabulary;

/**
 * Class Voc
 *
 * @todo : allow override with custom Voc class.. switch to array and array_merge ?
 *
 * @package qFW\mvc\controller\vocabulary
 */
class Voc implements IVoc
{

    /**
     * Voc constructor.
     */
    public function __construct()
    {
    }

    public function todayAt(): string
    {
        return __FUNCTION__;
    }

    public function daysAnd(): string
    {
        return __FUNCTION__;
    }

    public function dayAnd(): string
    {
        return __FUNCTION__;
    }

    public function yesterdayAt(): string
    {
        return __FUNCTION__;
    }

    public function yesC(): string
    {
        return __FUNCTION__;
    }

    public function noC(): string
    {
        return __FUNCTION__;
    }

    public function pwdMust8Chrs(): string
    {
        return __FUNCTION__;
    }

    public function pwdMustMaxChrs(): string
    {
        return __FUNCTION__;
    }

    public function pwdMustOneNumber(): string
    {
        return __FUNCTION__;
    }

    public function pwdMustUppercaseChrs(): string
    {
        return __FUNCTION__;
    }

    public function pwdMustTinyChrs(): string
    {
        return __FUNCTION__;
    }

    public function cfCodeIsAbsent(): string
    {
        return __FUNCTION__;
    }

    public function cfCodeLenghtError(): string
    {
        return __FUNCTION__;
    }

    public function cfCodeWrongChrs(): string
    {
        return __FUNCTION__;
    }

    public function cfCodehomocodyError(): string
    {
        return __FUNCTION__;
    }

    public function cfCodeNotValid(): string
    {
        return __FUNCTION__;
    }

    public function dbStateRestore(): string
    {
        return __FUNCTION__;
    }

    public function headerAcceptNotSupported(): string
    {
        return __FUNCTION__;
    }

    public function headerAcceptAre(): string
    {
        return __FUNCTION__;
    }



    public function uploadSizeTooHigh(): string
    {
        return __FUNCTION__;
    }

    public function uploadAlreadyExist(): string
    {
        return __FUNCTION__;
    }

    public function uploadExtNotAllowed(): string
    {
        return __FUNCTION__;
    }

    public function uploadImageNotValid(): string
    {
        return __FUNCTION__;
    }

    public function uploadOk(): string
    {
        return __FUNCTION__;
    }

    public function uploadFail(): string
    {
        return __FUNCTION__;
    }

    public function queryErr23000(): string
    {
        return __FUNCTION__;
    }



    public function utFilesImpossibleAccessFile(): string
    {
        return __FUNCTION__;
    }

    public function utFilesCanNotOpen(): string
    {
        return __FUNCTION__;
    }

    public function utFilesCanNotWrite(): string
    {
        return __FUNCTION__;
    }

    public function utFilesCanNotCLose(): string
    {
        return __FUNCTION__;
    }


    public function curlHttpHeaderNotIMplemented(): string
    {
        return __FUNCTION__;
    }

    public function curlVerbNotSet(): string
    {
        return __FUNCTION__;
    }

    public function curlUserAgentEmpty(): string
    {
        return __FUNCTION__;
    }

    public function curlVerboseErr(): string
    {
        return __FUNCTION__;
    }



    public function formPropertyCheckedErr(): string
    {
        return __FUNCTION__;
    }

    public function formPropertyMaxLenghtErr(): string
    {
        return __FUNCTION__;
    }

    public function formDefValueNotFound(): string
    {
        return __FUNCTION__;
    }

    public function formEmptyText(): string
    {
        return __FUNCTION__;
    }

    public function formTextarea0Rows(): string
    {
        return __FUNCTION__;
    }

    public function formTitleOutOfRange(): string
    {
        return __FUNCTION__;
    }

    public function formTitleEmpty(): string
    {
        return __FUNCTION__;
    }

    public function formSetWithoutScript(): string
    {
        return __FUNCTION__;
    }

    public function formDimensionNotSet(): string
    {
        return __FUNCTION__;
    }

    public function formRatioErr(): string
    {
        return __FUNCTION__;
    }

    public function formDimensionWrong(): string
    {
        return __FUNCTION__;
    }

    public function formSetNoHtml(): string
    {
        return __FUNCTION__;
    }

    public function formSetNoId(): string
    {
        return __FUNCTION__;
    }

    public function formDataNoUppercase(): string
    {
        return __FUNCTION__;
    }

    public function formDataSetEmpty(): string
    {
        return __FUNCTION__;
    }

    public function formDataSetNumeric(): string
    {
        return __FUNCTION__;
    }

    public function formLangNotAllowed(): string
    {
        return __FUNCTION__;
    }

    public function formSpellcheckNotAllowed(): string
    {
        return __FUNCTION__;
    }

    public function formDuplicatedId(): string
    {
        return __FUNCTION__;
    }

    public function formOnlyOneAutofocus(): string
    {
        return __FUNCTION__;
    }

    public function formAcceskeyTooLong(): string
    {
        return __FUNCTION__;
    }

    public function formAcceskeyDuplicated(): string
    {
        return __FUNCTION__;
    }

    public function formTabIndexDuplicated(): string
    {
        return __FUNCTION__;
    }

    public function formEnctypeGetErr(): string
    {
        return __FUNCTION__;
    }

    public function formNext(): string
    {
        return __FUNCTION__;
    }

    public function formBack(): string
    {
        return __FUNCTION__;
    }

    public function formPageExist(): string
    {
        return __FUNCTION__;
    }

    public function formPageNotExist(): string
    {
        return __FUNCTION__;
    }

    public function formHasNoPages(): string
    {
        return __FUNCTION__;
    }

    public function formTabindexNot0(): string
    {
        return __FUNCTION__;
    }

    public function formTabindexNoEmpty(): string
    {
        return __FUNCTION__;
    }
}
