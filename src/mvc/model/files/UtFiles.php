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

use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\vocabulary\Voc;

/**
 * Class UtFiles
 */
class UtFiles
{
    const DOMAIN_FOLDER_PERMISSION = '0775';
    const DOMAIN_FILES_PERMISSION = '0664';

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    /** @var \qFW\mvc\controller\lang\ILang */
    private $lang;

    /**
     * UtFiles constructor.
     *
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(ILang $lang)
    {
        $this->lang = $lang;
        $this->voc = new Voc();
    }

    /**
     * Count files in directory
     *
     * @param string $path
     *
     * @return int
     */
    public function countFiles(string $path): int
    {
        $objects = new \RecursiveIteratorIterator(
            new RecursiveDotFilterIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
            )
        );
        return iterator_count($objects);
    }

    /**
     * @todo Change in isValidImage and handle array scan of image type
     *
     * @param $file
     *
     * @return bool
     */
    public function isValidJpeg($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return is_resource($finfo) &&
            (finfo_file($finfo, $file) === 'image/jpeg') &&
            finfo_close($finfo);
    }

    /**
     *
     * ->  http://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php
     *
     * @param string $src
     * @param string $dst
     */
    public function recurseCopy(string $src, string $dst)
    {

        $oldmask = umask(0);
        $dir = opendir($src);
        // @ Suppress worning if folder already exists
        @mkdir($dst, octdec(self::DOMAIN_FOLDER_PERMISSION), true);
        // Fix mkdir  do not create folder with right permission
        chmod($dst, octdec(self::DOMAIN_FOLDER_PERMISSION));

        umask($oldmask);

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                    $oldmask = umask(0);
                    chmod($dst . '/' . $file, octdec(self::DOMAIN_FILES_PERMISSION));
                    umask($oldmask);
                }
            } else {
                /*Ok*/
            }
        }
        closedir($dir);
    }

    /**
     * @todo Rewrite this function/ split in more functions.
     *
     *  GD library is required.
     *
     * @param        $folder
     * @param string $fileName
     * @param bool   $readExif default true
     */
    public function manageUploadJpg($folder, string $fileName, bool $readExif = true)
    {
        $baseName = basename($_FILES[$fileName]['name']);

        if ($baseName != '') {
            $uniqId = uniqid();
            $target_dir = "{$_SERVER['DOCUMENT_ROOT']}/tmp/$folder$uniqId/";
            mkdir($target_dir, 0700);

            $target_file = $target_dir . $uniqId . $baseName;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if (isset($_POST['submit'])) {
                $check = getimagesize($_FILES[$fileName]['tmp_name']);
                if ($check !== false) {
                    $_SESSION['mex'] .= 'Il file ' . $_FILES[$fileName]['tmp_name'] . ' è un\' immagine '
                        . $check['mime'] . '. Perfetto.<br>';
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
                $_SESSION['err'] .= 'Spiacente, il file ' . $_FILES[$fileName]['name']
                    . ' ha una dimensione troppo elevata.<br>';
                $uploadOk = 0;
            }
            // Allow certain file formats
            if ($imageFileType != 'jpg' && $imageFileType != 'jpeg') {
                $_SESSION['err'] .= 'Spiacente, sono ammesse solo immagini di tipo JPG e JPEG . Il file '
                    . $_FILES[$fileName]['name'] . ' non è valido.<br>';
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $_SESSION['err'] .= 'Spiacente, il file ' . $_FILES[$fileName]['name'] . ' non è stato caricato.<br>';
                // If everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES[$fileName]['tmp_name'], $target_file)) {
                    if (self::isValidJpeg($target_file)) {
                        $newIm = @imagecreatefromjpeg($target_file);

                        // ruota x exif
                        if ($readExif) {
                            $exif = exif_read_data($target_file);
                            if ($newIm && $exif && isset($exif['Orientation'])) {
                                $ort = $exif['Orientation'];

                                if ($ort == 6 || $ort == 5) {
                                    $newIm = imagerotate($newIm, 270, null);
                                }
                                if ($ort == 3 || $ort == 4) {
                                    $newIm = imagerotate($newIm, 180, null);
                                }
                                if ($ort == 8 || $ort == 7) {
                                    $newIm = imagerotate($newIm, 90, null);
                                }

                                if ($ort == 5 || $ort == 4 || $ort == 7) {
                                    imageflip($newIm, IMG_FLIP_HORIZONTAL);
                                }
                            }
                        }
                        self::recurseCopy($target_dir, "{$_SERVER['DOCUMENT_ROOT']}/uploads/$folder");

                        // Delete source files
                        unlink($target_file);

                        $_SESSION['mex'] .= 'Il file ' . basename($_FILES[$fileName]['name'])
                            . ' è stato caricato.<br>';
                    } else {
                        $_SESSION['err'] .= 'Il file ' . basename($_FILES[$fileName]['name'])
                            . ' non è stato caricato.<br>';
                    }
                } else {
                    $_SESSION['err'] .= 'Spiacente, c\'è stato un errore nel caricamento del file '
                        . basename($_FILES[$fileName]['name']) . '.<br>';
                }

                // Clean uploaded folder
                rmdir($target_dir);
            }
        }
    }

    /**
     * @param string $path
     *
     * @return string
     * @throws \Exception
     */
    public function readMyFile(string $path): string
    {
        if (!$p_path = fopen($path, 'r')) {
            throw new \Exception($this->voc->utFilesImpossibleAccessFils() . " $path");
        } else {
            /*Ok*/
        }

        $str = file_get_contents($path);
        fclose($p_path);
        return $str;
    }

    /**
     * Append text to a file
     *
     * @param string $path
     * @param string $str
     *
     * @throws \Exception
     */
    public function appendMyFile(string $path, string $str)
    {
        if (!$p_page = fopen($path, 'a')) {
            throw new \Exception($this->voc->utFilesCanNotOpen() . " $path");
        } else {
            fputs($p_page, $str);
            fclose($p_page);
        }
    }

    /**
     * Write file on disk
     *
     * @param string $path
     * @param string $str
     *
     * @return bool
     * @throws \Exception
     */
    public function writeMyFile(string $path, string $str): bool
    {
        $ret = true;
        if (!$p_page = @fopen($path, 'x')) { // Try to create it and open it in writing
            // PHP can not OPEN, maybe exist already
            if (!$p_page = fopen($path, 'w')) {
                throw new \Exception($this->voc->utFilesCanNotOpen() . " $path");
            } else {
                /*Ok*/
            }
        } elseif (!fputs($p_page, $str)) {
            throw new \Exception($this->voc->utFilesCanNotWrite() . " $path");
        } elseif (!fclose($p_page)) {
            throw new \Exception($this->voc->utFilesCanNotCLose() . " $path");
        } else {
            /*Ok*/
        }

        return $ret;
    }
}
