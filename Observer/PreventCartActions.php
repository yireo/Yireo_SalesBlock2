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

use Magento\Checkout\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\Data\CartInterface;
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
     * PreventCartActions constructor.
     *
     * @param ModuleHelper $moduleHelper
     * @param RuleHelper $ruleHelper
     * @param Session $checkoutSession
     * @param CartInterface $cart
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(
        ModuleHelper $moduleHelper,
        RuleHelper $ruleHelper,
        Session $checkoutSession,
        CartInterface $cart,
        RequestInterface $request,
        ResponseInterface $response
    ) {
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

        $match = (int)$this->ruleHelper->getMatchId();
        if (empty($match)) {
            return $this;
        }

        $this->storeData($match);

        if ($this->modifyAjaxCall() === true) {
            return $this;
        }

        $this->resetCustomerEmailInQuote();

        try {
            $url = $this->moduleHelper->getUrl();
            if (!empty($url)) {
                $this->redirect($url);
            }
        } catch (CmsPageException $cmsPageException) {
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
        $quote->save();
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
        if (!in_array($action, array('saveFormValues', 'placeOrder'), true)) {
            return false;
        }

        $request = $this->request;
        $response = $this->response;
        $actionName = $request->getActionName();

        $this->request->setDispatched(false);

        $result = array();
        $result['success'] = false;
        $result['messages'][] = $this->moduleHelper->__('Email is incorrect.');
        $jsonData = Mage::helper('core')->jsonEncode($result);
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

        $includeControllers = array('onepage', 'multishipping');
        $includeModules = array('onestep', 'onestepcheckout');
        $excludeActions = array('saveAddress');

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
