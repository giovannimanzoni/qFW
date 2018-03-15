<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\model\files;

/**
 * Class UtFiles
 */
class UtFiles
{

const DOMAIN_FOLDER_PERMISSION = '0775';
const DOMAIN_FILES_PERMISSION = '0664';


    /**
     * Count files in directory
     *
     * @param string $path
     *
     * @return int
     */
    public static function countFiles(string $path): int
    {
        $objects = new \RecursiveIteratorIterator(
            new RecursiveDotFilterIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
            )
        );
        return iterator_count($objects);
    }


    public static function isvalidjpeg($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return is_resource($finfo) &&
            (finfo_file($finfo, $file) === 'image/jpeg') &&
            finfo_close($finfo);
    }


    // http://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php

    /**
     *
     * ->  http://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php
     *
     * @param string $src
     * @param string $dst
     */
    public static function recurse_copy(string $src, string $dst)
    {

        $oldmask = umask(0);
        $dir = opendir($src);
        @mkdir($dst,octdec(self::DOMAIN_FOLDER_PERMISSION),true); // @ sopprime worning se cartella esiste già
        chmod ($dst ,octdec(self::DOMAIN_FOLDER_PERMISSION)); // fix mkdir non crea cartella con permessi corretti

        umask($oldmask);

        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    self::recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    //echo $src . '/' . $file ."   in   ".$dst . '/' . $file . "<br>";
                    copy($src . '/' . $file,$dst . '/' . $file);
                    $oldmask = umask(0);
                    //echo "faccio chmod 664 $dst/$file <br>";
                    chmod ($dst . '/' . $file,octdec(self::DOMAIN_FILES_PERMISSION));
                    umask($oldmask);
                }
            }
        }
        closedir($dir);
    }


// per ora non usata
    function manageUploadJpg($folder, string $fileName)
    {


        $baseName=basename($_FILES[$fileName]['name']);

        if($baseName != '') {

            $uniqId = uniqid();
            $target_dir = "{$_SERVER['DOCUMENT_ROOT']}/tmp/$folder$uniqId/";
            //make dir
            mkdir($target_dir, 0700);


            $target_file = $target_dir . $uniqId . $baseName;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if (isset($_POST['submit'])) {
                $check = getimagesize($_FILES[$fileName]['tmp_name']);
                if ($check !== false) {
                    $_SESSION['mex'] .= 'Il file ' . $_FILES[$fileName]['tmp_name'] . ' è un\' immagine ' . $check['mime'] . '. Perfetto.<br>';
                    $uploadOk = 1;
                } else {
                    $_SESSION['err'] .= 'Spiacente, il file caricato non è un\' un immagine valida.<br>';
                    $uploadOk = 0;
                }
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                //echo 'Sorry, file already exists.';
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES[$fileName]['size'] > 5000000) {
                $_SESSION['err'] .= 'Spiacente, il file ' . $_FILES[$fileName]['name'] . ' ha una dimensione troppo elevata.<br>';
                $uploadOk = 0;
            }
            // Allow certain file formats
            if ($imageFileType != 'jpg' && $imageFileType != 'jpeg') {
                $_SESSION['err'] .= 'Spiacente, sono ammesse solo immagini di tipo JPG e JPEG . Il file ' . $_FILES[$fileName]['name'] . ' non è valido.<br>';
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $_SESSION['err'] .= 'Spiacente, il file ' . $_FILES[$fileName]['name'] . ' non è stato caricato.<br>';
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES[$fileName]['tmp_name'], $target_file)) {


                    if (self::isvalidjpeg($target_file)) {
                        $newIm = @imagecreatefromjpeg($target_file);

                        // ruota x exif
                        $exif = exif_read_data($target_file);
                        if ($newIm && $exif && isset($exif['Orientation'])) {
                            $ort = $exif['Orientation'];

                            if ($ort == 6 || $ort == 5)
                                $newIm = imagerotate($newIm, 270, null);
                            if ($ort == 3 || $ort == 4)
                                $newIm = imagerotate($newIm, 180, null);
                            if ($ort == 8 || $ort == 7)
                                $newIm = imagerotate($newIm, 90, null);

                            if ($ort == 5 || $ort == 4 || $ort == 7)
                                imageflip($newIm, IMG_FLIP_HORIZONTAL);
                        }

                        self::recurse_copy($target_dir, "{$_SERVER['DOCUMENT_ROOT']}/uploads/$folder");

                        //elimina file di origine
                        unlink($target_file);


                        $_SESSION['mex'] .= 'Il file ' . basename($_FILES[$fileName]['name']) . ' è stato caricato.<br>';
                    } else {

                        $_SESSION['err'] .= 'Il file ' . basename($_FILES[$fileName]['name']) . ' non è stato caricato.<br>';
                    }

                } else {
                    $_SESSION['err'] .= 'Spiacente, c\'è stato un errore nel caricamento del file ' . basename($_FILES[$fileName]['name']) . '.<br>';
                }

                // clean uploaded folder
                rmdir($target_dir);
            }
        }
    }

    /**
     * read file
     *
     * @param string $path
     *
     * @return string
     */
    public static function readMyFile(string $path): string
    {
        if (!$p_path = fopen ($path, 'r')) {
            echo "Contattare amministrazione, impossibile accedere a $path";
            exit;
        }

        $str = file_get_contents($path);
        fclose($p_path);
        return $str;
    }

    /**
     * Append to a file
     *
     * @param string $path
     * @param string $str
     */
    public static function appendMyFile(string $path, string $str)
    {
        if (!$p_page = fopen ($path, 'a')) {
            echo "Spiacente non posso aprire il file $path";
            exit;
        }
        fputs($p_page, $str);
        fclose($p_page);
    }


    /**
     * Scrive del contenuto in un file su disco
     *
     * @param string $path
     * @param string $str
     *
     * @return bool
     * @throws \Exception
     */
    public static function writeMyFile(string $path, string $str): bool
    {

        $ret=true;
        if (!$p_page = @fopen ($path, 'x')) { // prova a crearlo e aprirlo in scrittura

            // non ci riesce, potrebbe esistere già
            if (!$p_page = fopen ($path, 'w')) {
                echo "Spiacente non posso aprire il file $path";
                throw new \Exception("Impossibile aprire il file $path");
            }
        }

        if (!fputs($p_page, $str)) {
            $ret = false;
            $_SESSION['err'].="impossibile scirvere nel file $path.";
        }
        if (!fclose($p_page)) {
            $ret=false;
            $_SESSION['err'].="Impossibile chiudere il file $path.";
        }

        return $ret;
    }



}
