<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addParameter(
            'productOfferCollection',
            $this->getFactory()->createProductOfferReader()->getProductOfferCollection($productViewTransfer, $this->getLocale())
        );
        $this->addParameter(
            'productView',
            $productViewTransfer
        );
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
}
