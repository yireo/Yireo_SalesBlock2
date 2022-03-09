<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Utils;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Quote\Api\Data\CartInterface;
use Yireo\SalesBlock2\Logger\Debugger;

/**
 * Class CurrentEmail
 * @package Yireo\SalesBlock2\Utils
 */
class CurrentEmail
{
    /**
     * @var string
     */
    private $customerEmail = '';

    /**
     * @var CartInterface
     */
    private $cart;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * CurrentEmail constructor.
     * @param CartInterface $cart
     * @param CustomerSession $customerSession
     * @param Debugger $debugger
     */
    public function __construct(
        CartInterface $cart,
        CustomerSession $customerSession,
        Debugger $debugger
    ) {
        $this->cart = $cart;
        $this->customerSession = $customerSession;
        $this->debugger = $debugger;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        $this->debugger->debug('Current email: ' . $this->customerEmail);
        if (!empty($this->customerEmail)) {
            return (string)$this->customerEmail;
        }

        // Load the customer-record
        $customer = $this->customerSession->getCustomer();
        if ($customer && $customer->getId() > 0) {
            $customerEmail = $customer->getEmail();
            if (!empty($customerEmail)) {
                $this->customerEmail = (string)$customerEmail;
                return $this->customerEmail;
            }
        }

        // Check the quote billing address
        $billingAddress = $this->cart->getBillingAddress();
        if ($billingAddress) {
            $customerEmail = $billingAddress->getEmail();
            if (!empty($customerEmail)) {
                $this->customerEmail = (string)$customerEmail;
                return $this->customerEmail;
            }
        }

        // Check the quote shipping address
        $shippingAddress = $this->cart->getShippingAddress();
        if ($shippingAddress) {
            $customerEmail = $shippingAddress->getEmail();
            if (!empty($customerEmail)) {
                $this->customerEmail = (string)$customerEmail;
                return $this->customerEmail;
            }
        }

        return '';
    }

    /**
     * @param string $customerEmail
     */
    public function setValue(string $customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }
}
