<?php

namespace Kinicart\Traits\Controller\Guest;

use Kinicart\Services\Product\ProductService;

trait Product {


    /**
     * @var ProductService
     */
    private $productService;


    /**
     * Product constructor.
     *
     * @param ProductService $productService
     */
    public function __construct($productService) {
        $this->productService = $productService;
    }

    /**
     * Run deferred product actions
     *
     * @http GET /deferred
     *
     * @return array
     */
    public function runDeferredProductActions() {
        return $this->productService->processAllDeferredProductActions();
    }

}
