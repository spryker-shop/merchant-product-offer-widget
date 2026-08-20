<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\MerchantProductOfferWidget\Plugin\CartPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ShopContextTransfer;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Expander\MerchantProductOfferExpander;
use SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory;
use SprykerShop\Yves\MerchantProductOfferWidget\Plugin\CartPage\MerchantProductOfferReplacementCartItemExpanderPlugin;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReader;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface;

/**
 * @group SprykerShop
 * @group Yves
 * @group MerchantProductOfferWidget
 * @group Plugin
 * @group CartPage
 * @group MerchantProductOfferReplacementCartItemExpanderPluginTest
 */
class MerchantProductOfferReplacementCartItemExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_SKU_REPLACED = 'product-concrete-sku-replaced';

    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_SKU_REPLACEMENT = 'product-concrete-sku-replacement';

    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE = 'merchant-reference';

    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE_OTHER = 'merchant-reference-other';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_REPLACED = 'product-offer-reference-replaced';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_REPLACEMENT = 'product-offer-reference-replacement';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_OTHER = 'product-offer-reference-other';

    public function testExpandReplacementCartItemSetsProductOfferOfReplacedItemMerchant(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer($this->createProductOfferStorageTransfer(
                static::PRODUCT_OFFER_REFERENCE_OTHER,
                static::MERCHANT_REFERENCE_OTHER,
            ))
            ->addProductOffer($this->createProductOfferStorageTransfer(
                static::PRODUCT_OFFER_REFERENCE_REPLACEMENT,
                static::MERCHANT_REFERENCE,
            ));

        $plugin = $this->createPlugin($productOfferStorageCollectionTransfer);

        // Act
        $itemTransfer = $plugin->expandReplacementCartItem(
            $this->createReplacementItemTransfer(),
            $this->createReplacedItemTransfer(static::PRODUCT_OFFER_REFERENCE_REPLACED, static::MERCHANT_REFERENCE),
        );

        // Assert
        $this->assertSame(
            static::PRODUCT_OFFER_REFERENCE_REPLACEMENT,
            $itemTransfer->getProductOfferReference(),
            'Expected that the product offer of the replaced item merchant is set to the replacement item.',
        );
        $this->assertSame(
            static::MERCHANT_REFERENCE,
            $itemTransfer->getMerchantReference(),
            'Expected that the merchant of the replaced item is kept for the replacement item.',
        );
    }

    public function testExpandReplacementCartItemDoesNothingWhenReplacedItemHasNoProductOffer(): void
    {
        // Arrange
        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock->expects($this->never())
            ->method('getProductOfferStoragesBySkus');

        $plugin = $this->createPluginWithProductOfferStorageClient($productOfferStorageClientMock);

        // Act
        $itemTransfer = $plugin->expandReplacementCartItem(
            $this->createReplacementItemTransfer(),
            $this->createReplacedItemTransfer(null, null),
        );

        // Assert
        $this->assertNull(
            $itemTransfer->getProductOfferReference(),
            'Expected that the replacement item has no product offer when the replaced item was not sold by a merchant.',
        );
        $this->assertNull(
            $itemTransfer->getMerchantReference(),
            'Expected that the replacement item has no merchant when the replaced item was not sold by a merchant.',
        );
    }

    public function testExpandReplacementCartItemDoesNothingWhenMerchantHasNoProductOfferForReplacementProduct(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer($this->createProductOfferStorageTransfer(
                static::PRODUCT_OFFER_REFERENCE_OTHER,
                static::MERCHANT_REFERENCE_OTHER,
            ));

        $plugin = $this->createPlugin($productOfferStorageCollectionTransfer);

        // Act
        $itemTransfer = $plugin->expandReplacementCartItem(
            $this->createReplacementItemTransfer(),
            $this->createReplacedItemTransfer(static::PRODUCT_OFFER_REFERENCE_REPLACED, static::MERCHANT_REFERENCE),
        );

        // Assert
        $this->assertNull(
            $itemTransfer->getProductOfferReference(),
            'Expected that no product offer is set when the merchant of the replaced item does not sell the replacement product.',
        );
        $this->assertNull(
            $itemTransfer->getMerchantReference(),
            'Expected that no merchant is set when the merchant of the replaced item does not sell the replacement product.',
        );
    }

    protected function createPlugin(
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
    ): MerchantProductOfferReplacementCartItemExpanderPlugin {
        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock->method('getProductOfferStoragesBySkus')
            ->willReturn($productOfferStorageCollectionTransfer);

        return $this->createPluginWithProductOfferStorageClient($productOfferStorageClientMock);
    }

    protected function createPluginWithProductOfferStorageClient(
        MerchantProductOfferWidgetToProductOfferStorageClientInterface $productOfferStorageClient
    ): MerchantProductOfferReplacementCartItemExpanderPlugin {
        $merchantProductOfferReader = new MerchantProductOfferReader(
            $productOfferStorageClient,
            $this->createShopContextResolverMock(),
            $this->createMock(MerchantProductOfferWidgetToMerchantStorageClientInterface::class),
        );

        $factoryMock = $this->createMock(MerchantProductOfferWidgetFactory::class);
        $factoryMock->method('createMerchantProductOfferExpander')
            ->willReturn(new MerchantProductOfferExpander($merchantProductOfferReader));

        $plugin = new MerchantProductOfferReplacementCartItemExpanderPlugin();
        $plugin->setFactory($factoryMock);

        return $plugin;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface
     */
    protected function createProductOfferStorageClientMock(): MerchantProductOfferWidgetToProductOfferStorageClientInterface
    {
        return $this->createMock(MerchantProductOfferWidgetToProductOfferStorageClientInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface
     */
    protected function createShopContextResolverMock(): ShopContextResolverInterface
    {
        $shopContextResolverMock = $this->createMock(ShopContextResolverInterface::class);
        $shopContextResolverMock->method('resolve')
            ->willReturn(new ShopContextTransfer());

        return $shopContextResolverMock;
    }

    protected function createReplacementItemTransfer(): ItemTransfer
    {
        return (new ItemTransfer())->setSku(static::PRODUCT_CONCRETE_SKU_REPLACEMENT);
    }

    protected function createReplacedItemTransfer(?string $productOfferReference, ?string $merchantReference): ItemTransfer
    {
        return (new ItemTransfer())
            ->setSku(static::PRODUCT_CONCRETE_SKU_REPLACED)
            ->setProductOfferReference($productOfferReference)
            ->setMerchantReference($merchantReference);
    }

    protected function createProductOfferStorageTransfer(
        string $productOfferReference,
        string $merchantReference
    ): ProductOfferStorageTransfer {
        return (new ProductOfferStorageTransfer())
            ->setProductOfferReference($productOfferReference)
            ->setMerchantReference($merchantReference);
    }
}
