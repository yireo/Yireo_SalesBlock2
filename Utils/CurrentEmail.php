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

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Quote\Model\Quote;
use Yireo\SalesBlock2\Logger\Debugger;

class CurrentEmail
{
    /**
     * @var string
     */
    private $customerEmail = '';

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * @param CheckoutSession $checkoutSession
     * @param CustomerSession $customerSession
     * @param Debugger $debugger
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        Debugger $debugger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->debugger = $debugger;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        if (!empty($this->customerEmail)) {
            return $this->customerEmail;
        }

        $customerEmail = $this->resolveEmail();
        $this->debugger->debug('Current email: ' . $customerEmail);
        $this->customerEmail = $customerEmail;

        return $customerEmail;
    }

    /**
     * @param string $customerEmail
     */
    public function setValue(string $customerEmail)
    {
        $this->customerEmail = trim($customerEmail, " \f\n\r\t\v\x00");
    }

    private function resolveEmail(): string
    {
        $customer = $this->customerSession->getCustomer();
        if ($customer && $customer->getId() > 0 && !empty($customer->getEmail())) {
            return trim((string)$customer->getEmail(), " \f\n\r\t\v\x00");
        }

        $quote = $this->getQuote();
        if (!$quote) {
            return '';
        }

        $candidates = [
            $quote->getCustomerEmail(),
            $quote->getBillingAddress() ? $quote->getBillingAddress()->getEmail() : null,
            $quote->getShippingAddress() ? $quote->getShippingAddress()->getEmail() : null,
        ];

        foreach ($candidates as $candidate) {
            if (!empty($candidate)) {
                return trim((string)$candidate, " \f\n\r\t\v\x00");
            }
        }

        return '';
    }

    private function getQuote(): ?Quote
    {
        try {
            return $this->checkoutSession->getQuote();
        } catch (\Exception $exception) {
            return null;
        }
    }
}
