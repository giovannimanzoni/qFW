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
 * Interface IVoc
 *
 * @package qFW\mvc\controller\vocabulary
 */
interface IVoc
{
    public function __construct();

    public function todayAt(): string;
    public function daysAnd(): string;
    public function dayAnd(): string;
    public function yesterdayAt(): string;
    public function yesC(): string;
    public function noC(): string;
    public function pwdMust8Chrs(): string;
    public function pwdMustMaxChrs(): string;
    public function pwdMustOneNumber(): string;
    public function pwdMustUppercaseChrs(): string;
    public function pwdMustTinyChrs(): string;

    public function cfCodeIsAbsent(): string;
    public function cfCodeLenghtError(): string;
    public function cfCodeWrongChrs(): string;
    public function cfCodehomocodyError(): string;
    public function cfCodeNotValid(): string;

    public function dbStateRestore(): string;

    public function headerAcceptNotSupported(): string;
    public function headerAcceptAre(): string;

    public function uploadSizeTooHigh(): string;
    public function uploadAlreadyExist(): string;
    public function uploadExtNotAllowed(): string;
    public function uploadImageNotValid(): string;
    public function uploadOk(): string;
    public function uploadFail(): string;

    public function queryErr23000(): string;

    public function utFilesImpossibleAccessFile(): string;
    public function utFilesCanNotOpen(): string;
    public function utFilesCanNotWrite(): string;
    public function utFilesCanNotCLose(): string;

    public function curlHttpHeaderNotIMplemented(): string;
    public function curlVerbNotSet(): string;
    public function curlUserAgentEmpty(): string;
    public function curlVerboseErr(): string;

    public function formPropertyCheckedErr(): string;
    public function formPropertyMaxLenghtErr(): string;
    public function formDefValueNotFound(): string;
    public function formEmptyText(): string;
    public function formTextarea0Rows(): string;
    public function formTitleOutOfRange(): string;
    public function formTitleEmpty(): string;
    public function formSetWithoutScript(): string;
    public function formDimensionNotSet(): string;
    public function formRatioErr(): string;
    public function formDimensionWrong(): string;
    public function formSetNoHtml(): string;
    public function formSetNoId(): string;
    public function formDataNoUppercase(): string;
    public function formDataSetEmpty(): string;
    public function formDataSetNumeric(): string;
    public function formLangNotAllowed(): string;
    public function formSpellcheckNotAllowed(): string;
    public function formDuplicatedId(): string;
    public function formOnlyOneAutofocus(): string;
    public function formAcceskeyTooLong(): string;
    public function formAcceskeyDuplicated(): string;
    public function formTabIndexDuplicated(): string;
    public function formEnctypeGetErr(): string;
    public function formNext(): string;
    public function formBack(): string;
    public function formPageExist(): string;
    public function formPageNotExist(): string;
    public function formHasNoPages(): string;
    public function formTabindexNot0(): string;
    public function formTabindexNoEmpty(): string;
}
