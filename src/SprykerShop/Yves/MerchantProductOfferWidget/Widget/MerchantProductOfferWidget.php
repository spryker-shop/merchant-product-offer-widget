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
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $context
     */
    public function __construct(ProductViewTransfer $productViewTransfer, array $context = [])
    {
        $this->addProductOfferCollection($productViewTransfer, $context);
        $this->addProductViewParameter($productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantProductOfferWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantProductOfferWidget/views/merchant-product-offer-widget/merchant-product-offer-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $context
     *
     * @return void
     */
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

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addProductViewParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter('productView', $productViewTransfer);
    }
}
