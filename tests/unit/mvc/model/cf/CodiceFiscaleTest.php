<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\model\cf;

use PHPUnit\Framework\TestCase;
use qFW\mvc\model\cf\CodiceFiscale;

/**
 * Class CodiceFiscaleTest
 *
 * @package qFW\tests\unit\mvc\model\cf
 */
class CodiceFiscaleTest extends TestCase
{

    /**
     * Test getSesso for M
     */
    public function testGetSessoM()
    {
        $cf=new CodiceFiscale();

        $cf->SetCF('MNZGNN87D07L388G');

        $this->assertEquals('M',$cf->GetSesso());
    }

    /**
     * Test getSesso for F
     */
    public function testGetSessoF()
    {
        $cf=new CodiceFiscale();

        $cf->SetCF('GTTRTI80A41A794Q');
        $this->assertEquals('F',$cf->GetSesso());

        $cf->SetCF('FLRMLD87L54Z129N');
        $this->assertEquals('F',$cf->GetSesso());


    }

}
