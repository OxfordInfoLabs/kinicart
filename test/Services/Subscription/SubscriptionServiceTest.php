<?php

namespace Kinicart\Services\Subscription;


use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Objects\Subscription\Subscription;
use Kinicart\TestBase;
use Kinikit\Core\DependencyInjection\Container;

include_once __DIR__ . "/../../autoloader.php";

class SubscriptionServiceTest extends TestBase {

    /**
     * @var SubscriptionService
     */
    private $subscriptionService;


    /**
     * Setup function
     */
    public function setUp(): void {
        $this->subscriptionService = Container::instance()->get(SubscriptionService::class);
    }

    public function testCanCreateNewSubscriptionsForAccountHolder() {

        $id = $this->subscriptionService->createNewSubscription(1, "Test Subscription", "virtual-host", 1);

        $subscription = Subscription::fetch($id);

        $this->assertEquals(1, $subscription->getId());
        $this->assertEquals("Test Subscription", $subscription->getDescription());
        $this->assertEquals("virtual-host", $subscription->getProductIdentifier());
        $this->assertEquals(1, $subscription->getRelatedObjectId());
        $this->assertEquals("MONTHLY", $subscription->getRecurrenceType());
        $this->assertEquals(1, $subscription->getRecurrence());
        $this->assertEquals(date("d/m/Y"), $subscription->getStartDate()->format("d/m/Y"));
        $this->assertNull($subscription->getNumberOfRenewals());

        $now = new \DateTime();
        $now->add(new \DateInterval("P1M"));
        $this->assertEquals($now->format("d/m/Y"), $subscription->getNextRenewalDate()->format("d/m/Y"));


        $id = $this->subscriptionService->createNewSubscription(1, "Test Subscription2", "virtual-host", 1, ProductBasePrice::RECURRENCE_ANNUAL, 2, 5);

        $subscription = Subscription::fetch($id);

        $this->assertEquals(2, $subscription->getId());
        $this->assertEquals("Test Subscription2", $subscription->getDescription());
        $this->assertEquals("virtual-host", $subscription->getProductIdentifier());
        $this->assertEquals(1, $subscription->getRelatedObjectId());
        $this->assertEquals("ANNUAL", $subscription->getRecurrenceType());
        $this->assertEquals(2, $subscription->getRecurrence());
        $this->assertEquals(date("d/m/Y"), $subscription->getStartDate()->format("d/m/Y"));
        $this->assertEquals(5, $subscription->getNumberOfRenewals());

        $now = new \DateTime();
        $now->add(new \DateInterval("P2Y"));
        $this->assertEquals($now->format("d/m/Y"), $subscription->getNextRenewalDate()->format("d/m/Y"));


    }

}
