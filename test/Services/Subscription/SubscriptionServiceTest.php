<?php

namespace Kinicart\Services\Subscription;


use Kinicart\Objects\Cart\SimpleSubscriptionCartItem;
use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Objects\Subscription\Subscription;
use Kinicart\TestBase;
use Kinicart\Types\Recurrence;
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


        $newCartItem = new SimpleSubscriptionCartItem("virtual-host");

        $id = $this->subscriptionService->createNewSubscription(1, $newCartItem, 1);

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


        $newCartItem = new SimpleSubscriptionCartItem("virtual-host", Recurrence::ANNUAL, 2);

        $id = $this->subscriptionService->createNewSubscription(1, $newCartItem, 1);

        $subscription = Subscription::fetch($id);

        $this->assertEquals(2, $subscription->getId());
        $this->assertEquals("Test Subscription", $subscription->getDescription());
        $this->assertEquals("virtual-host", $subscription->getProductIdentifier());
        $this->assertEquals(1, $subscription->getRelatedObjectId());
        $this->assertEquals("ANNUAL", $subscription->getRecurrenceType());
        $this->assertEquals(2, $subscription->getRecurrence());
        $this->assertEquals(date("d/m/Y"), $subscription->getStartDate()->format("d/m/Y"));

        $now = new \DateTime();
        $now->add(new \DateInterval("P2Y"));
        $this->assertEquals($now->format("d/m/Y"), $subscription->getNextRenewalDate()->format("d/m/Y"));


    }

}
