<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types=1);

namespace Yireo\SalesBlock2\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Quote\Api\Data\CartInterface;
use Yireo\SalesBlock2\Configuration\Configuration;
use Yireo\SalesBlock2\Exception\CmsPageException;
use Yireo\SalesBlock2\Helper\Data as ModuleHelper;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;

/**
 * Class PreventCartActions
 *
 * @package Yireo\SalesBlock2\Observer
 */
class PreventCartActions implements ObserverInterface
{
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var RuleHelper
     */
    private $ruleHelper;

    /**
     * @var CartInterface
     */
    private $cart;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * PreventCartActions constructor.
     *
     * @param Configuration $configuration
     * @param ModuleHelper $moduleHelper
     * @param RuleHelper $ruleHelper
     * @param CheckoutSession $checkoutSession
     * @param CartInterface $cart
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(
        Configuration $configuration,
        ModuleHelper $moduleHelper,
        RuleHelper $ruleHelper,
        CheckoutSession $checkoutSession,
        CartInterface $cart,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $this->configuration = $configuration;
        $this->moduleHelper = $moduleHelper;
        $this->ruleHelper = $ruleHelper;
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws CmsPageException
     */
    public function execute(Observer $observer)
    {
        if ($this->allowPrevention() === false) {
            return $this;
        }

        try {
            $match = $this->ruleHelper->findMatch();
        } catch (NotFoundException $exception) {
            return $this;
        }

        $this->storeData($match);

        if ($this->modifyAjaxCall() === true) {
            return $this;
        }

        $this->resetCustomerEmailInQuote();

        try {
            $url = $this->configuration->getUrl();
            if (!empty($url)) {
                $this->redirect($url);
            }
        } catch (CmsPageException $cmsPageException) {
            return $this;
        }

        return $this;
    }

    /**
     * Reset the customer email in the current quote
     */
    protected function resetCustomerEmailInQuote()
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->cart->getQuote();
        $quote->setCustomerEmail('');
        $quote->save(); // @todo: Does this work?
    }

    /**
     * @return bool
     */
    protected function modifyAjaxCall()
    {
        if ($this->moduleHelper->isAjax() === false) {
            return false;
        }

        $action = $this->request->getActionName();
        if (!in_array($action, ['saveFormValues', 'placeOrder'], true)) {
            return false;
        }

        $request = $this->request;
        $response = $this->response;
        $actionName = $request->getActionName();

        $this->request->setDispatched(false);

        $result = [];
        $result['success'] = false;
        $result['messages'][] = __('Email is incorrect.');
        $jsonData = json_encode($result);
        $response->setBody($jsonData);

        return true;
    }

    /**
     * @return bool
     */
    protected function allowPrevention()
    {
        // Get the variables
        $module = $this->request->getModuleName();
        //$controller = $this->request->getActionPath(); // @todo: Not working
        $controller = '';
        $action = $this->request->getActionName();

        $includeControllers = ['onepage', 'multishipping'];
        $includeModules = ['onestep', 'onestepcheckout'];
        $excludeActions = ['saveAddress'];

        $match = false;
        if (in_array($controller, $includeControllers, true)) {
            $match = true;
        } elseif (in_array($module, $includeModules, true)) {
            $match = true;
        }

        if (in_array($action, $excludeActions, true)) {
            $match = false;
        }

        if ($match == false) {
            return false;
        }

        return true;
    }
}
