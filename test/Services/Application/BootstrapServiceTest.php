<?php


namespace Kinicart\Test\Services\Application;


use Kinicart\Objects\Application\Authenticator;
use Kinicart\Objects\Application\BootstrapService;
use Kinicart\Objects\Application\Session;
use Kinicart\Test\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

class BootstrapServiceTest extends TestBase {

    private $bootstrapService;
    private $authenticationService;

    public function setUp() {
        $this->bootstrapService = Container::instance()->createInstance("Kinicart\Services\Application\BootstrapService");
        $this->authenticationService = Container::instance()->createInstance("Kinicart\Services\Application\AuthenticationService");
    }

    public function testSessionReferrerAndParentAccountIsCorrectlySetAccordingToTheReferer() {

        $_SERVER["HTTP_REFERER"] = "https://www.google.com/hello/123";

        $this->bootstrapService->run();

        $this->assertEquals("www.google.com", Session::instance()->getReferringURL());
        $this->assertEquals(0, Session::instance()->getActiveParentAccountId());


        $_SERVER["HTTP_REFERER"] = "http://apps.hello.org/mark";

        $this->bootstrapService->run();

        $this->assertEquals("apps.hello.org", Session::instance()->getReferringURL());
        $this->assertEquals(0, Session::instance()->getActiveParentAccountId());


        $_SERVER["HTTP_REFERER"] = "http://samdavis.org/mark";

        $this->bootstrapService->run();

        $this->assertEquals("samdavis.org", Session::instance()->getReferringURL());
        $this->assertEquals(1, Session::instance()->getActiveParentAccountId());

    }

    public function testIfLoggedInAndParentAccountChangesUserIsLoggedOutForSecurity() {

        $_SERVER["HTTP_REFERER"] = "https://www.google.com/hello/123";

        $this->bootstrapService->run();

        $this->authenticationService->logIn("simon@peterjonescarwash.com", "password");

        $this->assertNotNull(Session::instance()->getLoggedInUser());
        $this->assertNotNull(Session::instance()->getLoggedInAccount());

        $_SERVER["HTTP_REFERER"] = "http://samdavis.org/mark";

        $this->bootstrapService->run();

        $this->assertNull(Session::instance()->getLoggedInUser());
        $this->assertNull(Session::instance()->getLoggedInAccount());


    }

}
