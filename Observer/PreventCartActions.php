<?php
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */
namespace Yireo\SalesBlock2\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class PreventCartActions
 *
 * @package Yireo\SalesBlock2\Observer
 */
class PreventCartActions implements ObserverInterface
{
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\Data\CartInterface $cart,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;
        $this->request = $request;
    }

    /**
     * @param Observer $observer
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

        $url = $this->helper->getUrl();
        if (!empty($url)) {
            $this->redirect($url);
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
        if ($this->helper->isAjax() === false) {
            return false;
        }

        $action = $this->request->getActionName();
        if (!in_array($action, array('saveFormValues', 'placeOrder'))) {
            return false;
        }

        $request = $this->request;
        $response = $this->response;
        $actionName = $request->getActionName();

        $this->setControllerActionNoDispatch($actionName);

        $result = array();
        $result['success'] = false;
        $result['messages'][] = $this->helper->__('Email is incorrect.');
        $jsonData = Mage::helper('core')->jsonEncode($result);
        $response->setBody($jsonData);

        return true;
    }

    /**
     * @param string $actionName
     */
    protected function setControllerActionNoDispatch($actionName)
    {
        $frontControllerAction = Mage::app()->getFrontController()->getAction();
        $frontControllerAction->setFlag($actionName, Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
    }

    /**
     * @return bool
     */
    protected function allowPrevention()
    {
        // Get the variables
        $module = $this->request->getModuleName();
        $controller = $this->request->getControllerName();
        $action = $this->request->getActionName();

        $includeControllers = array('onepage', 'multishipping');
        $includeModules = array('onestep', 'onestepcheckout');
        $excludeActions = array('saveAddress');

        $match = false;
        if (in_array($controller, $includeControllers)) {
            $match = true;
        } elseif (in_array($module, $includeModules)) {
            $match = true;
        }

        if (in_array($action, $excludeActions)) {
            $match = false;
        }

        if ($match == false) {
            return false;
        }

        return true;
    }
}