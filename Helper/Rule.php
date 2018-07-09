<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types = 1);

namespace Yireo\SalesBlock2\Helper;

use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\ManagerInterface;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Model\Rule\Service as RuleService;

/**
 * Class Rule
 *
 * @package Yireo\SalesBlock2\Helper
 */
class Rule
{
    /**
     * @var string
     */
    private $ip = '';

    /**
     * @var string
     */
    private $customerEmail = '';

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var RuleService
     */
    private $ruleService;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * Rule constructor.
     *
     * @param Data $moduleHelper
     * @param RuleService $ruleService
     * @param Session $customerSession
     * @param CheckoutSession $checkoutSession
     * @param Cart $cart
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        Data $moduleHelper,
        RuleService $ruleService,
        Session $customerSession,
        CheckoutSession $checkoutSession,
        Cart $cart,
        ManagerInterface $eventManager
    ) {
        $this->helper = $moduleHelper;
        $this->ruleService = $ruleService;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->cart = $cart;
        $this->eventManager = $eventManager;
    }

    /**
     * Method to check whether the current visitor matches a SalesBlock rule
     *
     * @return bool
     */
    public function hasMatch()
    {
        return (bool)$this->getMatchId();
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->ruleService->getRules();
    }

    /**
     * Method to check whether the current visitor matches a SalesBlock rule
     *
     * @return int
     */
    public function getMatchId()
    {
        // Check whether the module is disabled
        if ($this->helper->enabled() === false) {
            return 0;
        }

        // Load all rules and exit if there are no rules
        $rules = $this->ruleService->getRules();

        if (count($rules) === false) {
            return 0;
        }

        // Fetch the IP
        $ip = $this->getIp();

        // Load the customer-record
        $customerEmail = $this->getCustomerEmail();

        // Loop through all rules
        foreach ($rules as $rule) {
            if ($matchId = $this->getMatchIdFromRule($rule, $ip, $customerEmail)) {
                return $matchId;
            }
        }

        return 0;
    }

    /**
     * @param $rule
     * @param $ip
     * @param $customerEmail
     *
     * @return int
     */
    private function getMatchIdFromRule($rule, $ip, $customerEmail)
    {
        /** @var RuleInterface $rule */
        $ruleIpValues = $this->helper->stringToArray($rule->getIpValue());

        // Direct IP matches
        if (in_array($ip, $ruleIpValues, true)) {
            $this->afterMatch($rule, $ip, $customerEmail);
            return $rule->getId();
        }

        // Other matches
        if (!empty($ip) && !empty($ruleIpValues)) {
            foreach ($ruleIpValues as $ruleIpValue) {
                if ($this->matchIpRange($ip, $ruleIpValue)) {
                    $this->afterMatch($rule, $ip, $customerEmail);
                    return $rule->getId();
                }
            }
        }

        // Email matches
        $ruleEmailValues = $this->helper->stringToArray($rule->getEmailValue());
        if (!empty($customerEmail) && !empty($ruleEmailValues)) {
            foreach ($ruleEmailValues as $ruleEmailValue) {
                if ($this->hasEmailMatch($customerEmail, $ruleEmailValue)) {
                    $this->afterMatch($rule, $ip, $customerEmail);
                    return $rule->getId();
                }
            }
        }

        return 0;
    }

    /**
     * Match whether a certain IP matches a certain range string
     *
     * @param $ip
     * @param $rangeString
     *
     * @return bool
     */
    public function matchIpRange($ip, $rangeString)
    {
        // Convert subnet ranges
        if (!preg_match('/([0-9\.]+)\/([0-9]+)/', $rangeString, $rangeMatch)) {
            return false;
        }

        $rip = ip2long($rangeMatch[1]);
        $ipStart = long2ip((float)$rip);
        $ipEnd = long2ip((float)($rip | (1 << (32 - $rangeMatch[2])) - 1));
        $rangeString = $ipStart . '-' . $ipEnd;

        // Check for IP-ranges
        if (!preg_match('/([0-9\.]+)-([0-9\.]+)/', $rangeString, $ipMatch)) {
            return false;
        }

        if (version_compare($ip, $ipMatch[1], '>=') && version_compare($ip, $ipMatch[2], '<=')) {
            return true;
        }

        return false;
    }

    /**
     * Check whether an email matches a pattern
     *
     * @param $email
     * @param $emailPattern
     *
     * @return bool
     */
    public function hasEmailMatch($email, $emailPattern)
    {
        if ($email == $emailPattern) {
            return true;
        }

        if (stristr($email, $emailPattern)) {
            return true;
        }

        return false;
    }

    /**
     * Return the email of the current customer
     *
     * @return mixed
     */
    private function getCustomerEmail()
    {
        if ($this->customerEmail) {
            return $this->customerEmail;
        }

        // Load the customer-record
        $customer = $this->customerSession->getCustomer();
        if ($customer->getId() > 0) {
            $customerEmail = $customer->getEmail();
            if (!empty($customerEmail)) {
                $this->customerEmail = $customerEmail;
                return $this->customerEmail;
            }
        }

        // Check the quote
        $quote = $this->cart->getQuote();
        $customerEmail = $quote->getCustomerEmail();
        if (!empty($customerEmail)) {
            $this->customerEmail = $customerEmail;
            return $this->customerEmail;
        }

        // Check for AW Onestepcheckout form values
        $data = $this->checkoutSession->getData('aw_onestepcheckout_form_values');
        if (is_array($data) && !empty($data['billing']['email'])) {
            $customerEmail = $data['billing']['email'];
        }

        $this->customerEmail = $customerEmail;
        return $this->customerEmail;
    }

    /**
     * Get the current IP address
     *
     * @return string
     */
    public function getIp()
    {
        if (!empty($this->ip)) {
            return $this->ip;
        }

        $ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        $this->ip = $ip;

        return $this->ip;
    }

    /**
     * @param $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @param $customerEmail
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    /**
     * Method to execute when a visitor is actually matched
     *
     * @param RuleInterface $rule
     * @param string $ip
     * @param string $email
     */
    public function afterMatch(RuleInterface $rule, string $ip, string $email)
    {
        $eventArguments = ['rule' => $rule, 'ip' => $ip, 'email' => $email];
        $this->eventManager->dispatch('salesblock_rule_match_after', $eventArguments);
    }
}
