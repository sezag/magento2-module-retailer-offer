<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\RetailerOffer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOffer\Plugin;

/**
 * Ensure correct appliance of retailer data to quote when retrieving it.
 * We may have a quote with no values if retailer data are properly stored in cookies but not re-applied when switching retailer.
 *
 * @category Smile
 * @package  Smile\RetailerOffer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class CheckoutSessionPlugin
{
    /**
     * @var \Smile\StoreLocator\CustomerData\CurrentStore
     */
    private $currentStore;

    /**
     * CheckoutSessionPlugin constructor.
     *
     * @param \Smile\StoreLocator\CustomerData\CurrentStore $currentStore The current Store provider.
     */
    public function __construct(\Smile\StoreLocator\CustomerData\CurrentStore $currentStore)
    {
        $this->currentStore = $currentStore;
    }

    /**
     * Ensure proper binding of seller id and pickup date when retrieving quote from session.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) We do not need to retrieve the session.
     *
     * @param \Magento\Checkout\Model\Session $session The checkout session
     * @param \Magento\Quote\Model\Quote      $result  The quote being retrieved
     *
     * @return mixed
     */
    public function afterGetQuote(\Magento\Checkout\Model\Session $session, $result)
    {
        if (!$result->getSellerId() && $this->currentStore->getRetailer()) {
            $result->setSellerId($this->currentStore->getRetailer()->getId());
        }

        return $result;
    }
}
