<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\model\cf;

use PHPUnit\Framework\TestCase;
use qFW\mvc\controller\lang\LangEn;
use qFW\mvc\model\cf\CodiceFiscale;

/**
 * Class CodiceFiscaleTest
 *
 * @package qFW\tests\unit\mvc\model\cf
 */
class CodiceFiscaleTest extends TestCase
{

    /**
     * Test getSex for M
     */
    public function testGetSessoM()
    {
        $lang = new LangEn();

        $cf = new CodiceFiscale($lang);

        $cf->setCF('MNZGNN87D07L388G');

        $this->assertEquals('M', $cf->getSex());
    }

    /**
     * Test getSex for F
     */
    public function testGetSessoF()
    {
        $lang = new LangEn();

        $cf = new CodiceFiscale($lang);

        $cf->setCF('GTTRTI80A41A794Q');
        $this->assertEquals('F', $cf->getSex());

        $cf->setCF('FLRMLD87L54Z129N');
        $this->assertEquals('F', $cf->getSex());
    }
}
