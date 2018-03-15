<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\model\api\headers\accept;

use qFW\mvc\model\api\headers\accept\AcceptHeader;
use PHPUnit\Framework\TestCase;

/**
 * Class AcceptHeaderTest
 *
 * @package qFW\tests\unit\mvc\model\api\headers\accept
 */
class AcceptHeaderTest extends TestCase
{

    /**
     * Test getSendFormat
     */
    public function testGetSendFormat1()
    {
        //simula richiesta
        $clientResponseFormatPreference='*/*;q=0.3, text/html;q=1';
        $_SERVER['HTTP_ACCEPT']=$clientResponseFormatPreference;

        // costruttore
        $serverApiSupportedFormats=array('text/html','application/xhtml+xml','text/*', 'text/html.v2');
        $acceptHeader=new AcceptHeader($serverApiSupportedFormats);

        //test scelta header
        $sendFormat=$acceptHeader->getSendFormat();
        $this->assertEquals('text/html',$sendFormat);

        //test nessun warning
        $warn=$acceptHeader->getWarning();
        $this->assertEquals('',$warn);
    }

    /**
     * Test getSendFormat
     */
    public function testGetSendFormat2()
    {
        //simula richiesta
        $clientResponseFormatPreference='text/*;q=0.3, text/html;q=0.9, */*;q=0.5, text/html.v2;level=2;q=0.4';
        $_SERVER['HTTP_ACCEPT']=$clientResponseFormatPreference;

        // costruttore
        $serverApiSupportedFormats=array('text/html','application/xhtml+xml','text/*', 'text/html.v2');
        $acceptHeader=new AcceptHeader($serverApiSupportedFormats);

        //test scelta header
        $sendFormat=$acceptHeader->getSendFormat();
        $this->assertEquals('text/html',$sendFormat);

        //test nessun warning
        $warn=$acceptHeader->getWarning();
        $this->assertEquals('',$warn);
    }

    /**
     * Test passaggio di formato non supportato, il server deve rispondere con il primo formato specificato
     *      e segnalare un warning
     */
    public function testGetSendFormat3()
    {
        //simula richiesta
        $clientResponseFormatPreference='application/xhtml+xml';
        $_SERVER['HTTP_ACCEPT']=$clientResponseFormatPreference;

        // costruttore
        $serverApiSupportedFormats=array('text/html','text/*', 'text/html.v2');
        $acceptHeader=new AcceptHeader($serverApiSupportedFormats);

        //test scelta header
        $sendFormat=$acceptHeader->getSendFormat();
        $this->assertEquals('text/html',$sendFormat);

        //test warning
        $warn=$acceptHeader->getWarning();
        $this->assertNotEquals('',$warn);
    }


    /**
     * Test passaggio di formato accettato=qualsiasi il server deve rispondere con il primo formato specificato
     *      e segnalare un warning
     */
    public function testGetSendFormat4()
    {
        //simula richiesta
        $clientResponseFormatPreference='*/*';
        $_SERVER['HTTP_ACCEPT']=$clientResponseFormatPreference;

        // costruttore
        $serverApiSupportedFormats=array('text/html','application/xhtml+xml','text/*', 'text/html.v2');
        $acceptHeader=new AcceptHeader($serverApiSupportedFormats);

        //test hscelta header
        $sendFormat=$acceptHeader->getSendFormat();
        $this->assertEquals('text/html',$sendFormat);

        //test warning
        $warn=$acceptHeader->getWarning();
        $this->assertNotEquals('',$warn);
    }

/*

    public function testGetWarning()
    {

    }*/
}
