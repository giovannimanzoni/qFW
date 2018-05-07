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
 * Class VocIT
 *
 * @package qFW\mvc\controller\vocabulary
 */
class VocIT implements IVoc
{
    public function __construct()
    {
    }

    public function todayAt(): string
    {
        return 'oggi alle ';
    }

    public function daysAnd(): string
    {
        return 'giorni e ';
    }

    public function dayAnd(): string
    {
        return 'giorno e ';
    }

    public function yesterdayAt(): string
    {
        return 'ieri alle ';
    }

    public function yesC(): string
    {
        return 'Si';
    }

    public function noC(): string
    {
        return 'No';
    }

    public function pwdMust8Chrs(): string
    {
        return 'La password deve contenere almeno 8 caratteri.';
    }

    public function pwdMustMaxChrs(): string
    {
        return 'La password deve contenere meno di ' . FieldLenght::DIM_PWD . ' caratteri.';
    }

    public function pwdMustOneNumber(): string
    {
        return 'La password deve contenere almeno un numero.';
    }

    public function pwdMustUppercaseChrs(): string
    {
        return 'La password deve contenere almeno una lettera maiuscola.';
    }

    public function pwdMustTinyChrs(): string
    {
        return 'La password deve contenere almeno una lettera minuscola.';
    }


    public function cfCodeIsAbsent(): string
    {
        return 'Codice da analizzare assente.';
    }

    public function cfCodeLenghtError(): string
    {
        return 'Lunghezza codice da analizzare non corretta.';
    }

    public function cfCodeWrongChrs(): string
    {
        return 'Il codice da analizzare contiene caratteri non corretti.';
    }

    public function cfCodehomocodyError(): string
    {
        return 'Carattere non valido in decodifica omocodia.';
    }

    public function cfCodeNotValid(): string
    {
        return 'Codice fiscale non corretto.';
    }


    public function dbStateRestore(): string
    {
        return 'Ripristino del database eseguito.';
    }


    public function headerAcceptNotSupported(): string
    {
        return 'Accept Header non nei formati supportati. Hai inviato | ';
    }

    public function headerAcceptAre(): string
    {
        return 'Accepted Headers sono: | ';
    }


    public function uploadAlreadyExist(): string
    {
        return 'Il file esiste già.';
    }

    public function uploadSizeTooHigh(): string
    {
        return 'Spiacente, il file ha una dimensione troppo elevata';
    }

    public function uploadExtNotAllowed(): string
    {
        return 'Spiacente, tipologia di file non ammessa.';
    }

    public function uploadImageNotValid(): string
    {
        return 'Spiacente, l\'immagine caricata non è valida';
    }

    public function uploadOk(): string
    {
        return 'Il file è stato caricato';
    }

    public function uploadFail(): string
    {
        return 'Spiacente, c\'è stato un errore nel caricamento del file';
    }


    public function queryErr23000(): string
    {
        return 'Impossibile inserire per vincolo di chiave unica.';
    }


    public function utFilesImpossibleAccessFile(): string
    {
        return 'Contattare amministrazione, impossibile accedere a ';
    }

    public function utFilesCanNotOpen(): string
    {
        return 'Spiacente non posso aprire il file ';
    }

    public function utFilesCanNotWrite(): string
    {
        return 'Spiacente non posso scrivere nel file ';
    }

    public function utFilesCanNotCLose(): string
    {
        return 'Sorry, I can not chiudere il file ';
    }


    public function curlHttpHeaderNotIMplemented(): string
    {
        return 'build(): questa modalità httpHeader non è implementata.';
    }

    public function curlVerbNotSet(): string
    {
        return 'Verbo non impostato.';
    }

    public function curlUserAgentEmpty(): string
    {
        return 'UserAgent impostato vuoto.';
    }

    public function curlVerboseErr(): string
    {
        return 'Impossibile impostare modalità verbose.';
    }


    public function formPropertyCheckedErr(): string
    {
        return 'proprietà checked impostata su elemento che non lo ammette.';
    }

    public function formPropertyMaxLenghtErr(): string
    {
        return 'proprietà maxLength impostata di tipo non numerico.';
    }

    public function formDefValueNotFound(): string
    {
        return 'Valore di default non trovato.';
    }

    public function formEmptyText(): string
    {
        return 'Testo vuoto.';
    }

    public function formTextarea0Rows(): string
    {
        return 'Textarea ha impostato 0 righe visibili.';
    }

    public function formTitleOutOfRange(): string
    {
        return 'Dimensione del titolo fuori range (1..6)';
    }

    public function formTitleEmpty(): string
    {
        return 'Il tiolo è vuoto';
    }

    public function formSetWithoutScript(): string
    {
        return 'Impostato senza script.';
    }

    public function formDimensionNotSet(): string
    {
        return 'dimentione non impostata.';
    }

    public function formRatioErr(): string
    {
        return 'Conflitto, parametri ratio e orizzontale impostati entrambi ';
    }

    public function formDimensionWrong(): string
    {
        return 'Dimensione campo errate';
    }

    public function formSetNoHtml(): string
    {
        return 'impostato ma senza html';
    }

    public function formSetNoId(): string
    {
        return 'impostato ma senza id';
    }

    public function formDataNoUppercase(): string
    {
        return 'Il nome attributo data-* non deve contenere lettere maiuscole.';
    }

    public function formDataSetEmpty(): string
    {
        return 'Valore per l\'attributo data-* impostato vuoto.';
    }

    public function formDataSetNumeric(): string
    {
        return 'Valore per l\'attributo data-* impostato di tipo numerico.';
    }

    public function formLangNotAllowed(): string
    {
        return 'Valore per l\'attributo lang non consentito';
    }

    public function formSpellcheckNotAllowed(): string
    {
        return 'Impostato spellcheck su elemento che non lo può avere.';
    }

    public function formDuplicatedId(): string
    {
        return 'Rilevati id duplicati';
    }

    public function formOnlyOneAutofocus(): string
    {
        return 'Solo un elemento può avere l\'autofocus abilitato.';
    }

    public function formAcceskeyTooLong(): string
    {
        return 'Accesskey più lunga di un carattere';
    }

    public function formAcceskeyDuplicated(): string
    {
        return 'Rilevati accesskey duplicate.';
    }

    public function formTabIndexDuplicated(): string
    {
        return 'Rilevati tab index duplicati.';
    }

    public function formEnctypeGetErr(): string
    {
        return 'Enctype non può essere impostato per form con method="GET"';
    }

    public function formNext(): string
    {
        return 'Avanti';
    }

    public function formBack(): string
    {
        return 'Indietro';
    }

    public function formPageExist(): string
    {
        return 'Aggiunta pagina con nome già presente:';
    }

    public function formPageNotExist(): string
    {
        return 'Non esiste la pagina chiamata';
    }

    public function formHasNoPages(): string
    {
        return 'Il form non ha pagine.';
    }

    public function formTabindexNot0(): string
    {
        return 'Valore di tabindex non può essere 0.';
    }

    public function formTabindexNoEmpty(): string
    {
        return 'Valore di title non può essere vuoto.';
    }
}
