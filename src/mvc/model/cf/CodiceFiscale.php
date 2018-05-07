<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\cf;

//
// ----------------------------------------------------
//
//
//
// Metodi "setter":
//  SetCF(CodiceFiscaleDaAnalizzare)
//
// Metodi "getter":
//  GetCodiceValido()  TRUE/FALSE
//  GetErrore()         Motivo dell'errore nel caso in cui GetCodiceValido() dovesse ritornare false, altrimenti
//                          stringa vuota;
//  GetSesso()          Sesso (M o F)
//  GetComuneNascita()  Codice comune di nascita secondo la codifica catastale (1 carattere alfabetico + 3 caratteri
//                          numerici es. H501 per Roma)
//  GetAANascita()      Anno di nascita (ATTENZIONE: solo 2 caratteri!!! Non potremo mai sapere con sicurezza in che
//                          secolo è nato l'intestatario del CF!!!)
//  GetAAAANascita()    Anno di nascita in 4 cifre, + gestione persona ultracentenaria
//  GetMMNascita()      Mese di nascita
//  GetGGNascita()      Giorno di nascita
//
// - Vengono gestiti correttamente anche i casi di OMOCODIA
//
use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\vocabulary\Voc;

/**
 * https://softwarearo.blogspot.it/2013/09/gestione-e-verifica-del-codice-fiscale.html
 *
 * Class CodiceFiscale
 *
 * Control Fiscal code, used in Italy
 * Even cases of homocody are handled correctly
 *
 * 2014-01-13 - Modify from original user how to calculate day of birthday for female peoples
 * 2018-02-01 - Change to OOP and add getAnno in 4 digits
 * 2018-03-30 - Add translations and change methods name in english
 *
 * @package qFW\mvc\model\cf
 */
class CodiceFiscale implements ICodiceFiscale
{
    private $codiceValido = false;
    private $sesso = '';
    private $comuneNascita = null;
    private $ggNascita = '';
    private $mmNascita = '';
    private $aaNascita = '';
    private $errore = '';
    private $TabDecOmocodia = array();
    private $TabSostOmocodia = array();
    private $TabCaratteriPari = array();
    private $TabCaratteriDispari = array();
    private $TabCodiceControllo = array();
    private $TabDecMesi = array();
    private $TabErrori = array();

    /** @var \qFW\mvc\controller\dataTypes\UtString  */
    private $utStr;

    /** @var \qFW\mvc\controller\vocabulary\Voc  */
    private $voc;

    /**
     * CodiceFiscale constructor.
     *
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(ILang $lang)
    {
        // Tabella sostituzioni per omocodia
        $this->TabDecOmocodia = array(
            'A' => '!', 'B' => '!', 'C' => '!', 'D' => '!', 'E' => '!', 'F' => '!', '
        G' => '!', 'H' => '!', 'I' => '!', 'J' => '!', 'K' => '!', 'L' => '0', 'M' => '1', 'N' => '2', 'O' => '!',
            'P' => '3', 'Q' => '4', 'R' => '5', 'S' => '6', 'T' => '7', 'U' => '8',
            'V' => '9', 'W' => '!', 'X' => '!', 'Y' => '!', 'Z' => '!',
        );

        // Posizioni caratteri interessati ad alterazione di codifica in caso di omocodia
        $this->TabSostOmocodia = array(6, 7, 9, 10, 12, 13, 14);

        // Tabella peso caratteri PARI
        $this->TabCaratteriPari = array(
            '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6,
            '7' => 7, '8' => 8, '9' => 9, 'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3,
            'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7, 'I' => 8, 'J' => 9, 'K' => 10,
            'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17,
            'S' => 18, 'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24,
            'Z' => 25
        );

        // Tabella peso caratteri DISPARI
        $this->TabCaratteriDispari = array(
            '0' => 1, '1' => 0, '2' => 5, '3' => 7, '4' => 9, '5' => 13, '6' => 15,
            '7' => 17, '8' => 19, '9' => 21, 'A' => 1, 'B' => 0, 'C' => 5, 'D' => 7,
            'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21, 'K' => 2,
            'L' => 4, 'M' => 18, 'N' => 20, 'O' => 11, 'P' => 3, 'Q' => 6, 'R' => 8,
            'S' => 12, 'T' => 14, 'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24,
            'Z' => 23
        );

        // Tabella calcolo codice CONTOLLO (carattere 16)
        $this->TabCodiceControllo = array(
            0  => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G',
            7  => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N',
            14 => 'O', 15 => 'P', 16 => 'Q', 17 => 'R', 18 => 'S', 19 => 'T', 20 => 'U',
            21 => 'V', 22 => 'W', 23 => 'X', 24 => 'Y', 25 => 'Z'
        );

        // Array per il calcolo del mese
        $this->TabDecMesi = array(
            'A' => '01', 'B' => '02', 'C' => '03', 'D' => '04', 'E' => '05', 'H' => '06',
            'L' => '07', 'M' => '08', 'P' => '09', 'R' => '10', 'S' => '11', 'T' => '12'
        );

        $this->utStr = new UtString($lang);
        $this->voc= new Voc();

        // Tabella messaggi di errore
        $this->TabErrori = array(
            0 => $this->voc->cfCodeIsAbsent(),
            1 => $this->voc->cfCodeLenghtError(),
            2 => $this->voc->cfCodeWrongChrs(),
            3 => $this->voc->cfCodehomocodyError(),
            4 => $this->voc->cfCodeNotValid(),
        );
    }

    /**
     * @param string $cf
     *
     * @return bool
     * @throws \Exception
     */
    public function setCF(string $cf)
    {
        // Azzero le property
        $this->codiceValido = false;
        $this->sesso = '';
        $this->comuneNascita = '';
        $this->ggNascita = '';
        $this->mmNascita = '';
        $this->aaNascita = '';
        $this->errore = '';

        // Verifica esistenza codice passato
        if ($cf === '') {
            $this->codiceValido = false;
            $this->errore = $this->TabErrori[0];
            return false;
        } else {
            /*Ok*/
        }

        // Verifica lunghezza codice passato:
        // 16 caratteri per CF standard
        // (non gestisco i CF provvisori da 11 caratteri...)
        if (strlen($cf) !== 16) {
            $this->codiceValido = false;
            $this->errore = $this->TabErrori[1];
            return false;
        } else {
            /*Ok*/
        }

        // Converto in maiuscolo
        $cf = strtoupper($cf);

        // Verifica presenza di caratteri non validi
        // nel codice passato
        // if( ! ereg("^[A-Z0-9]+$", $cf) ) {
        // ******* Funzione deprecata e, come
        // ******* suggerito da Gabriele,
        // ******* sostituita con preg_match
        if (!preg_match("/^[A-Z0-9]+$/", $cf)) {
            $this->codiceValido = false;
            $this->errore = $this->TabErrori[2];
            return false;
        } else {
            /*Ok*/
        }

        // Converto la stringa in array
        $cfArray = str_split($cf);

        // Verifica correttezza alterazioni per omocodia
        // (al posto dei numeri sono accettabili solo le
        // lettere da "L" a "V", "O" esclusa, che
        // sostituiscono i numeri da 0 a 9)
        for ($i = 0; $i < count($this->TabSostOmocodia); $i++) {
            if (!is_numeric($cfArray[$this->TabSostOmocodia[$i]])) {
                if ($this->TabDecOmocodia[$cfArray[$this->TabSostOmocodia[$i]]] === '!') {
                    $this->codiceValido = false;
                    $this->errore = $this->TabErrori[3];
                    return false;
                } else {
                    /*Ok*/
                }
            } else {
                /*Ok*/
            }
        }

        // Tutti i controlli formali sono superati.
        // Inizio la fase di verifica vera e propria del CF
        $pari = 0;
        // Calcolo subito l'ultima cifra dispari (pos. 15) per comodita'...
        $dispari = $this->TabCaratteriDispari[$cfArray[14]];

        // Loop sui primi 14 elementi
        // a passo di due caratteri alla volta
        for ($i = 0; $i < 13; $i += 2) {
            $dispari = $dispari + $this->TabCaratteriDispari[$cfArray[$i]];
            $pari = $pari + $this->TabCaratteriPari[$cfArray[$i + 1]];
        }

        // Verifica congruenza dei valori calcolati
        // sui primi 15 caratteri con il codice di
        // controllo (carattere 16)
        if (!($this->TabCodiceControllo[($pari + $dispari) % 26] === $cfArray[15])) {
            $this->codiceValido = false;
            $this->errore = $this->TabErrori[4];
            return false;
        } else {
            // Opero la sostituzione se necessario
            // utilizzando la tabella $this->TabDecOmocodia
            // (per risolvere eventuali omocodie)
            for ($i = 0; $i < count($this->TabSostOmocodia); $i++) {
                if (!is_numeric($cfArray[$this->TabSostOmocodia[$i]])) {
                    $cfArray[$this->TabSostOmocodia[$i]] = $this->TabDecOmocodia[$cfArray[$this->TabSostOmocodia[$i]]];
                } else {
                    /*Ok*/
                }
            }

            // Converto l'array di nuovo in stringa
            $CodiceFiscaleAdattato = implode($cfArray);

            // Comunico che il codice è valido...
            $this->codiceValido = true;
            $this->errore = '';

            // ...ed estraggo i dati dal codice verificato
            $this->sesso = (substr($CodiceFiscaleAdattato, 9, 2) > '40' ? 'F' : 'M');
            $this->comuneNascita = substr($CodiceFiscaleAdattato, 11, 4);
            $this->aaNascita = substr($CodiceFiscaleAdattato, 6, 2);
            $this->mmNascita = $this->TabDecMesi[substr($CodiceFiscaleAdattato, 8, 1)];

            // 2014-01-13 Modifica per corretto recupero giorno di nascita se sesso=F
            $this->ggNascita = substr($CodiceFiscaleAdattato, 9, 2);
            if ($this->sesso === 'F') {
                $this->ggNascita = $this->ggNascita - 40;
                if (strlen($this->utStr->getCleanString($this->ggNascita)) === 1) {
                    $this->ggNascita = "0{$this ->ggNascita}";
                } else {
                    /*Ok*/
                }
            } else {
                /*Ok*/
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function getValidCode(): bool
    {
        return $this->codiceValido;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->errore;
    }

    /**
     * @return string
     */
    public function getSex(): string
    {
        return $this->sesso;
    }

    /**
     * @return null
     */
    public function getPlaceBirth()
    {
        return $this->comuneNascita;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getYYBirth(): string
    {
        return $this->utStr->getCleanString($this->aaNascita);
    }

    /**
     * Return year in 4 digit
     *
     * presa dai commenti, @fixme gestire ultracentenario
     *
     * @param bool $ultraCent
     *
     * @return string
     * @throws \Exception
     */
    public function getYYYYBirth(bool $ultraCent = false): string
    {
        $annoN4 = '0';

        if (!$ultraCent) {
            $AnnoOggi = date('y');
            $diff = $AnnoOggi - $this->aaNascita;

            if ($diff > 0) {
                $annoN4 = "20{$this->aaNascita}";
            } elseif ($diff < 0) {
                $annoN4 = "19{$this->aaNascita}";
            } else {
                /*Ok*/
            }
        } else {
            throw new \Exception('fixme');
            /*@fixme*/
        }
        return $this->utStr->getCleanString($annoN4);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getMMBirth(): string
    {
        return $this->utStr->getCleanString($this->mmNascita);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getDDBirth(): string
    {
        return $this->utStr->getCleanString($this->ggNascita);
    }
}
