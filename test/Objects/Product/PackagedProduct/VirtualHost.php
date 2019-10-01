<?php

namespace Kinicart\Objects\Product\PackagedProduct;


class VirtualHost extends PackagedProduct {


    /**
     * Get a list of features which this product makes available.  Features are combined
     * into packages for sale purposes.
     *
     * @return Feature[]
     */
    public function getFeatures() {
        return [
            new Feature("memory", "Memory (GB)", "The amount of memory allocated to this VM"),
            new Feature("diskSpace", "Disk Space (GB)", "The amount of disk space allocated to this VM"),
            new Feature("includedBandwidth", "Included Bandwidth (GB/month)", "The amount of included bandwidth in GB/Month"),
            new Feature("additionalBandwidth", "Additional Bandwidth (GB/month)", "Additional bandwidth per GB"),
            new Feature("excessBandwidth", "Excess Bandwidth (GB/month)", "Excess bandwidth charges - additional GBs", Feature::TYPE_EXCESS)
        ];
    }
}
