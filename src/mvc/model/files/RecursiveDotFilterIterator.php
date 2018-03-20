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
 * Class RecursiveDotFilterIterator
 *
 * @package qFW\mvc\model\files
 */
class RecursiveDotFilterIterator extends \RecursiveFilterIterator
{
    /**
     * @return bool
     */
    public function accept()
    {
        return '.' !== substr($this->current()->getFilename(), 0, 1);
    }
}
