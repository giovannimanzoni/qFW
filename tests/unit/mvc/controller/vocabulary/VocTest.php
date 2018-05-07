<?php declare(strict_types=1);

use qFW\mvc\controller\vocabulary\Voc;
use PHPUnit\Framework\TestCase;

class VocTest extends TestCase
{

    /**
     * @FIXME
     */
    public function testVoc()
    {

        /********************************************************
         * Check not empty and exist in every vocabulary
         */

        /*************************
         * EN
         */

        $langEN = new \qFW\mvc\controller\lang\LangEn();

        $vocEN = new Voc();

        $methodsEN = get_class_methods($vocEN);

        foreach ($methodsEN as $met) {
            if (($met <=> '__construct') !== 0) {
                $this->assertNotEmpty($vocEN->$met());
            }
        }

        /*************************
         * IT
         */

        $langIT = new \qFW\mvc\controller\lang\LangIt();
        $vocIT = new Voc();

        $methodsIT = get_class_methods($vocIT);

        foreach ($methodsIT as $met) {
            if (($met <=> '__construct') !== 0) {
                $this->assertNotEmpty($vocIT->$met());
            }
        }


        /********************************************************
         * Check qty integrity
         */

        $methodsVocEN = get_class_methods(new \qFW\mvc\controller\vocabulary\VocEN());
        $methodsVocIT = get_class_methods(new \qFW\mvc\controller\vocabulary\VocIT());
        $methodsVoc = get_class_methods($vocEN);

        $this->assertEquals(count($methodsVocEN), count($methodsVocIT));
        $this->assertEquals(count($methodsVocEN), count($methodsVoc));


        /********************************************************
         * Check unique values
         */

        $textEN = array();
        foreach ($methodsEN as $met) {
            if (($met <=> '__construct') !== 0) {
                $textEN[] = $vocEN->{$met}(); // http://php.net/manual/en/function.call-user-func.php
            }
        }

        $textIT = array();
        foreach ($methodsIT as $met) {
            if (($met <=> '__construct') !== 0) {
                $textIT[] = $vocIT->{$met}(); // http://php.net/manual/en/function.call-user-func.php
            }
        }

        $this->assertFalse(count(array_unique($textEN)) < count($textEN));
        $this->assertFalse(count(array_unique($textIT)) < count($textIT));


        /********************************************************
         * Check name integrity
         */

        sort($methodsVocEN);
        sort($methodsVocIT);
        foreach ($methodsVocEN as $key => $ame) {
            $this->assertEquals($methodsVocEN[$key], $methodsVocIT[$key]);
        }
    }
}
