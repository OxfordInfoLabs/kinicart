<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinikit\Core\Logging\Logger;
use Kinikit\Core\Util\ObjectArrayUtils;
use Kinikit\Persistence\ORM\Interceptor\DefaultORMInterceptor;

/**
 * Class PackagedProductFeatureInterceptor
 * @package Kinicart\Objects\Product\PackagedProduct
 *
 */
class PackagedProductFeatureInterceptor extends DefaultORMInterceptor {

    /**
     * @var PackagedProductService
     */
    private $packagedProductService;


    /**
     * Inject the packaged product service.
     *
     * PackageInterceptor constructor.
     * @param PackagedProductService $packagedProductService
     */
    public function __construct($packagedProductService) {
        $this->packagedProductService = $packagedProductService;
    }

    /**
     * Attach feature information to packages
     *
     * @param PackagedProductFeature $object
     * @return bool
     */
    public function postMap($object) {

         // Get the list of code defined features
        $features = $this->packagedProductService->getPackagedProduct($object->getProductIdentifier())->getFeatures() ?? [];
        $indexedFeatures = ObjectArrayUtils::indexArrayOfObjectsByMember("identifier", $features);

        if (isset($indexedFeatures[$object->getFeatureIdentifier()])) {
            $object->setFeature($indexedFeatures[$object->getFeatureIdentifier()]);
        }

        return true;

    }


}
