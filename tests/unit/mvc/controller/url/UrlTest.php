<?php declare(strict_types=1);

use qFW\mvc\controller\url\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{

    public function testGetScriptName()
    {
        $_SERVER['PHP_SELF'] = 'http://domain.com/a/b/c/page.php';
        $url = new Url();

        $val = $url->getScriptName();
        $this->assertEquals('page.php', $val);
    }

    public function testGetScriptDirName()
    {
        $_SERVER['SCRIPT_FILENAME'] = '/var/www/domain.com/www/page.php';
        $url = new Url();

        $val = $url->getScriptDirName();
        $this->assertEquals('/var/www/domain.com/www', $val);
    }

    public function testMakeUrlSSL()
    {
        $_SERVER['SERVER_PORT'] = '443';
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'domain.com';

        $url = new Url();

        $this->assertEquals('https://domain.com/page.php', $url->makeUrl('/page.php'));
    }

    public function testMakeUrlNoSSL()
    {
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['SERVER_NAME'] = 'domain.com';

        $url = new Url(false);

        $this->assertEquals('http://domain.com/page.php', $url->makeUrl('/page.php'));
    }

    public function testMakeUrlForceSSL()
    {
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['SERVER_NAME'] = 'domain.com';

        $url = new Url();

        $this->assertEquals('https://domain.com/page.php', $url->makeUrl('/page.php', true));
    }
}
