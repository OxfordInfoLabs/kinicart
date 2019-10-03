<?php


namespace Kinicart\Objects\Product\PackagedProduct;


use Kinicart\Services\Product\PackagedProduct\PackagedProductService;
use Kinikit\Core\Util\ObjectArrayUtils;
use Kinikit\Persistence\ORM\Interceptor\DefaultORMInterceptor;

/**
 * Class PackageInterceptor
 * @package Kinicart\Objects\Product\PackagedProduct
 *
 * @noGenerate
 */
class PackageInterceptor extends DefaultORMInterceptor {

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
     * @param Package $object
     * @return bool
     */
    public function postMap($object) {

        // Get the list of code defined features
        $features = $this->packagedProductService->getPackagedProduct($object->getProductIdentifier())->getFeatures() ?? [];
        $indexedFeatures = ObjectArrayUtils::indexArrayOfObjectsByMember("identifier", $features);

        if ($object->getFeatures()) {
            foreach ($object->getFeatures() as $packageFeature) {
                if (isset($indexedFeatures[$packageFeature->getFeatureIdentifier()])) {
                    $packageFeature->setFeature($indexedFeatures[$packageFeature->getFeatureIdentifier()]);
                }
            }
        }

        return true;

    }


}
