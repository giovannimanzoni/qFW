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

use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\vocabulary\Voc;

/**
 * Class Upload
 *
 * @package App\external\qFW\src\mvc\model\files
 */
class Upload
{
    /** @var int Max attachment file siza */
    private $maxFileSize = 0;

    /** @var array Array with supported extensions */
    private $extensions = array();

    /** @var array */
    private $fileUploadedInfo = array();

    /** @var string */
    private $chmodUploadFolder = '0700';

    /** @var bool Check if uploaded file is valid image or not */
    private $checkImage = false;

    /** @var string Uploaded folder */
    private $uploadFolder = '';

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    /** @var \qFW\mvc\controller\dataTypes\UtString */
    private $utStr;

    /**
     * Upload constructor.
     *
     * @param string                         $uploadFolder
     * @param int                            $maxFileSize
     * @param array                          $extensions
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(string $uploadFolder, int $maxFileSize, array $extensions, ILang $lang)
    {
        $this->maxFileSize = $maxFileSize;
        $this->extensions = $extensions;
        $this->uploadFolder = $uploadFolder;
        $this->voc = new Voc();
        $this->utStr = new UtString($lang);
    }

    /**
     * @param string $chmod Ex.:'0775', default '0700'
     *
     * @return $this
     */
    public function setChmodUploadFolder(string $chmod)
    {
        $this->chmodUploadFolder = $chmod;
        return $this;
    }

    /**
     * Check if uploaded file is a valid image
     *
     * @return $this
     */
    public function checkIfValidImage()
    {
        $this->checkImage = true;
        return $this;
    }

    /**
     * Get uploaded files info
     *
     * @return array
     */
    public function getUploadInfo(): array
    {
        return $this->fileUploadedInfo;
    }


    /**
     * Upload files
     *
     * @param string $fileName
     * @param string $overrideFileName
     *
     * @return bool
     * @throws \Exception
     */
    public function manageUpload(
        string $fileName,
        string $overrideFileName = ''
    ): bool {
        $ret = false;

        if ($fileName != '') { // If file has been chosen
            $baseName = basename($_FILES[$fileName]['name']); // MyFile.txt
            $fileData = array();
            $fileData['name'] = $baseName;
            $folder = $this->uploadFolder;

            /**********************************
             * Init variables
             */
            $uniqId = uniqid();
            $destinationDir = "{$_SERVER['DOCUMENT_ROOT']}/$folder$uniqId/";

            // Override uploaded file name ?
            if ($overrideFileName != '') {
                $target_file = $destinationDir . $overrideFileName;
            } else {
                $target_file = $destinationDir . $uniqId . $baseName;
            }

            /**********************************
             *  Check if file already exists
             */
            if (file_exists($target_file)) {
                throw new \Exception($this->voc->uploadAlreadyExist());
            } else {
                /*Ok*/
            }

            /**********************************
             * Check file size
             */
            if ($_FILES[$fileName]['size'] > $this->maxFileSize) {
                throw new \Exception($this->voc->uploadSizeTooHigh() . " ($baseName");
            } else {
                /*Ok*/
            }

            /**********************************
             * Check allowed file formats
             */
            $fileExt = strtolower(pathinfo($baseName, PATHINFO_EXTENSION));
            $extFound = false;
            foreach ($this->extensions as $ext) {
                if ($this->utStr->areEqual($fileExt, $ext)) {
                    $extFound = true;
                    break;
                } else {
                    /*Ok*/
                }
            }
            if (!$extFound) {
                throw new \Exception($this->voc->uploadExtNotAllowed() . " ($baseName)");
            } else {
                /*Ok*/
            }

            /**********************************
             * Ok, upload
             */
            $dgbState = mkdir($destinationDir, octdec($this->chmodUploadFolder), true);

            if (move_uploaded_file($_FILES[$fileName]['tmp_name'], $target_file)) {
                /**********************************
                 * Check (if enabled) if file is a valid image
                 */
                if ($this->checkImage) {
                    $valid = $this->checkIfIsImage($target_file);
                    if (!$valid) {
                        //delete the file
                        unlink($target_file);
                        throw new \Exception($this->voc->uploadImageNotValid() . " ($baseName)");
                    } else {
                        /*Ok*/
                    }
                } else {
                    /*Ok*/
                }

                $fileData['path'] = $destinationDir;
                $this->fileUploadedInfo[] = $fileData;

                $_SESSION['mex'] .= $this->voc->uploadOk() . " ($baseName)<br>";
                $ret = true;
            } else {
                throw new \Exception($this->voc->uploadFail() . " ($baseName)");
            }
        } else {
            /*Ok*/
        }

        return $ret;
    }

    /**
     * Check if file is valid image
     *
     * @param string $fileName
     *
     * @return bool
     * @throws \Exception
     */
    private function checkIfIsImage(string $fileName): bool
    {
        $check = getimagesize($_FILES[$fileName]['tmp_name']);
        if ($check !== false) {
            $ret = true;
        } else {
            throw new \Exception($this->voc->uploadImageNotValid());
        }

        return $ret;
    }
}
