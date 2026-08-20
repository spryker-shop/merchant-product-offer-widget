<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPageExtension\Dependency\Plugin\ReplacementCartItemExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOfferReplacementCartItemExpanderPlugin extends AbstractPlugin implements ReplacementCartItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if the replaced cart item has no product offer.
     * - Finds the product offer of the replaced cart item's merchant for the new product concrete.
     * - Sets the found product offer reference and the merchant reference to the item transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $replacedItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandReplacementCartItem(ItemTransfer $itemTransfer, ItemTransfer $replacedItemTransfer): ItemTransfer
    {
        return $this->getFactory()
            ->createMerchantProductOfferExpander()
            ->expandItemTransferWithReplacedItemProductOffer($itemTransfer, $replacedItemTransfer);
    }
}
