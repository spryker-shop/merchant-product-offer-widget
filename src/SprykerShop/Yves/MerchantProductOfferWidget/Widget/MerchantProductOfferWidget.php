<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOfferWidget extends AbstractWidget
{
    public function __construct(ProductViewTransfer $productViewTransfer, array $context = [])
    {
        $this->addProductOfferCollection($productViewTransfer, $context);
        $this->addProductViewParameter($productViewTransfer);
    }

    public static function getName(): string
    {
        return 'MerchantProductOfferWidget';
    }

    public static function getTemplate(): string
    {
        return '@MerchantProductOfferWidget/views/merchant-product-offer-widget/merchant-product-offer-widget.twig';
    }

    protected function addProductOfferCollection(
        ProductViewTransfer $productViewTransfer,
        array $context = []
    ): void {
        $this->addParameter(
            'productOffers',
            $this->getFactory()
                ->createProductOfferReader()
                ->getProductOffers($productViewTransfer, $this->getLocale(), $context),
        );
    }

    protected function addProductViewParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter('productView', $productViewTransfer);
    }
}
