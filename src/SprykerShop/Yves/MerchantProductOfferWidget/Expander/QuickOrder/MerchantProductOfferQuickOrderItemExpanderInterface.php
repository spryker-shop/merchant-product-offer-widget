<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Expander\QuickOrder;

use Generated\Shared\Transfer\ItemTransfer;

interface MerchantProductOfferQuickOrderItemExpanderInterface
{
    public function expandItem(ItemTransfer $itemTransfer): ItemTransfer;
}
