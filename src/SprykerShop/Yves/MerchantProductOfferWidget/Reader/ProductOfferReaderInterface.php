<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Reader;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductOfferReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function getProductOfferCollection(ProductViewTransfer $productViewTransfer, string $localeName): array;
}
