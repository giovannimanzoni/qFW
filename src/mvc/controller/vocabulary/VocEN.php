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

use qFW\mvc\controller\form\FieldLenght;

/**
 * Class VocEN
 *
 * @package qFW\mvc\controller\vocabulary
 */
class VocEN implements IVoc
{
    public function __construct()
    {
    }

    public function todayAt(): string
    {
        return 'today at ';
    }

    public function daysAnd(): string
    {
        return 'days and ';
    }

    public function dayAnd(): string
    {
        return 'day and ';
    }

    public function yesterdayAt(): string
    {
        return 'yesterday at ';
    }

    public function yesC(): string
    {
        return 'Yes';
    }

    public function noC(): string
    {
        return 'No';
    }

    public function pwdMust8Chrs(): string
    {
        return 'The password must contain at least 8 chars.';
    }

    public function pwdMustMaxChrs(): string
    {
        return 'The password must contain less than ' . FieldLenght::DIM_PWD . ' chars.';
    }

    public function pwdMustOneNumber(): string
    {
        return 'The password must contain at least one number.';
    }

    public function pwdMustUppercaseChrs(): string
    {
        return 'The password must contain at least one uppercase letter.';
    }

    public function pwdMustTinyChrs(): string
    {
        return 'The password must contain at least one lowercase letter.';
    }



    public function cfCodeIsAbsent(): string
    {
        return 'Code to analyze is absent.';
    }

    public function cfCodeLenghtError(): string
    {
        return 'The code length to be analyzed is not correct.';
    }

    public function cfCodeWrongChrs(): string
    {
        return 'The code to be analyzed contains incorrect chars.';
    }

    public function cfCodehomocodyError(): string
    {
        return 'Invalid character in homocode decoding.';
    }

    public function cfCodeNotValid(): string
    {
        return 'Incorrect Fiscal Code.';
    }


    public function dbStateRestore(): string
    {
        return 'Database state restore performed.';
    }


    public function headerAcceptNotSupported(): string
    {
        return 'Accept Header not in supported formats. You send | ';
    }

    public function headerAcceptAre(): string
    {
        return 'Accepted Headers are: | ';
    }

    public function uploadAlreadyExist(): string
    {
        return 'The file already exists.';
    }

    public function uploadSizeTooHigh(): string
    {
        return 'Sorry, the file is too large.';
    }

    public function uploadExtNotAllowed(): string
    {
        return 'Sorry, file type not allowed.';
    }

    public function uploadImageNotValid(): string
    {
        return 'Sorry, the uploaded image is not valid';
    }

    public function uploadOk(): string
    {
        return 'The file has been uploaded';
    }

    public function uploadFail(): string
    {
        return 'Sorry, there was an error loading the file';
    }


    public function queryErr23000(): string
    {
        return 'Impossible to insert by single key constraint.';
    }

    public function utFilesImpossibleAccessFile(): string
    {
        return 'Contact administration, unable to access to ';
    }

    public function utFilesCanNotOpen(): string
    {
        return 'Sorry, I can not open the file ';
    }

    public function utFilesCanNotWrite(): string
    {
        return 'Sorry, I can not open write into the file ';
    }

    public function utFilesCanNotCLose(): string
    {
        return 'Sorry, I can not close the file ';
    }


    public function curlHttpHeaderNotIMplemented(): string
    {
        return 'build(): questa modalità httpHeader non è implementata.';
    }

    public function curlVerbNotSet(): string
    {
        return 'Verb not set.';
    }

    public function curlUserAgentEmpty(): string
    {
        return 'UserAgent set empty.';
    }

    public function curlVerboseErr(): string
    {
        return 'Unable to set verbose mode.';
    }


    public function formPropertyCheckedErr(): string
    {
        return 'checked property set to an element that does not allow it.';
    }

    public function formPropertyMaxLenghtErr(): string
    {
        return 'maxLength property set non-numeric.';
    }

    public function formDefValueNotFound(): string
    {
        return 'Default value not found.';
    }

    public function formEmptyText(): string
    {
        return 'Empty text.';
    }

    public function formTextarea0Rows(): string
    {
        return 'Textarea has set 0 visible rows.';
    }

    public function formTitleOutOfRange(): string
    {
        return 'Title size out of range (1..6)';
    }

    public function formTitleEmpty(): string
    {
        return 'Title is empty';
    }

    public function formSetWithoutScript(): string
    {
        return 'Set without script.';
    }

    public function formDimensionNotSet(): string
    {
        return 'dimention not set.';
    }

    public function formRatioErr(): string
    {
        return 'conflict, ratio and horizontal parameters set both ';
    }

    public function formDimensionWrong(): string
    {
        return 'Wrong field size';
    }

    public function formSetNoHtml(): string
    {
        return 'set but without html';
    }

    public function formSetNoId(): string
    {
        return 'set but without id';
    }

    public function formDataNoUppercase(): string
    {
        return 'The attribute name data-* should not contain any uppercase letters.';
    }

    public function formDataSetEmpty(): string
    {
        return 'Value for the data-* attribute set empty.';
    }

    public function formDataSetNumeric(): string
    {
        return 'Value for the data-* attribute set as numeric type.';
    }

    public function formLangNotAllowed(): string
    {
        return 'Value for the lang attribute not allowed.';
    }

    public function formSpellcheckNotAllowed(): string
    {
        return 'Set spellcheck on item that can not have it.';
    }

    public function formDuplicatedId(): string
    {
        return 'Detected duplicate ids';
    }

    public function formOnlyOneAutofocus(): string
    {
        return 'Only one element can have autofocus enabled.';
    }

    public function formAcceskeyTooLong(): string
    {
        return 'Accesskey is longer than one character';
    }

    public function formAcceskeyDuplicated(): string
    {
        return 'Duplicated accesskey detected.';
    }

    public function formTabIndexDuplicated(): string
    {
        return 'Duplicated tab index detected.';
    }

    public function formEnctypeGetErr(): string
    {
        return 'Enctype can not be set for form with method="GET"';
    }

    public function formNext(): string
    {
        return 'Next';
    }

    public function formBack(): string
    {
        return 'Back';
    }

    public function formPageExist(): string
    {
        return 'Added page with name already present:';
    }

    public function formPageNotExist(): string
    {
        return 'There is no page called';
    }

    public function formHasNoPages(): string
    {
        return 'Form has got no pages.';
    }

    public function formTabindexNot0(): string
    {
        return 'Value of tabindex can not be 0.';
    }

    public function formTabindexNoEmpty(): string
    {
        return 'Value of title can not be empty.';
    }
}
