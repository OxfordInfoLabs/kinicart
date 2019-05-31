<?php


namespace Kinicart\Test\Objects\Application;


use Kinicart\Objects\Application\Authenticator;
use Kinicart\Objects\Application\Bootstrap;
use Kinicart\Objects\Application\Session;
use Kinicart\Test\TestBase;

include_once __DIR__ . "/../../autoloader.php";

class BootstrapTest extends TestBase {


    public function testSessionReferrerAndParentAccountIsCorrectlySetAccordingToTheReferer() {

        $_SERVER["HTTP_REFERER"] = "https://www.google.com/hello/123";

        Bootstrap::instance()->run();

        $this->assertEquals("www.google.com", Session::instance()->getReferringURL());
        $this->assertEquals(0, Session::instance()->getActiveParentAccountId());


        $_SERVER["HTTP_REFERER"] = "http://apps.hello.org/mark";

        Bootstrap::instance()->run();

        $this->assertEquals("apps.hello.org", Session::instance()->getReferringURL());
        $this->assertEquals(0, Session::instance()->getActiveParentAccountId());


        $_SERVER["HTTP_REFERER"] = "http://samdavis.org/mark";

        Bootstrap::instance()->run();

        $this->assertEquals("samdavis.org", Session::instance()->getReferringURL());
        $this->assertEquals(1, Session::instance()->getActiveParentAccountId());

    }

    public function testIfLoggedInAndParentAccountChangesUserIsLoggedOutForSecurity() {

        $_SERVER["HTTP_REFERER"] = "https://www.google.com/hello/123";

        Bootstrap::instance()->run();

        Authenticator::instance()->logIn("simon@peterjonescarwash.com", "password");

        $this->assertNotNull(Session::instance()->getLoggedInUser());
        $this->assertNotNull(Session::instance()->getLoggedInAccount());

        $_SERVER["HTTP_REFERER"] = "http://samdavis.org/mark";

        Bootstrap::instance()->run();

        $this->assertNull(Session::instance()->getLoggedInUser());
        $this->assertNull(Session::instance()->getLoggedInAccount());




    }

}
