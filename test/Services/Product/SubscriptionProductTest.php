<?php


namespace Kinicart\Services\Product;


use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Cart\SimpleSubscriptionCartItem;
use Kinicart\Objects\Subscription\Subscription;
use Kinicart\TestBase;
use Kinicart\Types\Recurrence;
use Kinikit\Core\DependencyInjection\Container;

class SubscriptionProductTest extends TestBase {

    public function testProcessCartItemCallsActivationMethodAndSavesSubscriptionUsingRelatedId() {


        $newSubCartItem = new SimpleSubscriptionCartItem("virtual-host");
        $account = Account::fetch(1);

        $subscriptionProduct = Container::instance()->get(SimpleSubscriptionProduct::class);
        $subscriptionId = $subscriptionProduct->processCartItem($account, $newSubCartItem);


        /**
         * @var Subscription $reSub
         */
        $reSub = Subscription::fetch($subscriptionId);

        $this->assertEquals("virtual-host", $reSub->getProductIdentifier());
        $this->assertEquals("Test Subscription", $reSub->getDescription());
        $this->assertEquals(Recurrence::MONTHLY, $reSub->getRecurrenceType());
        $this->assertEquals(1, $reSub->getRecurrence());
        $this->assertEquals(10, $reSub->getLastPaymentAmount());
        $this->assertEquals("EUR", $reSub->getLastPaymentCurrency());
        $this->assertEquals(Subscription::STATUS_ACTIVE, $reSub->getStatus());

    }

}
