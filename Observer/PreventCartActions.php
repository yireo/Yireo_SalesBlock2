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

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class PreventCartActions
 *
 * @package Yireo\SalesBlock2\Observer
 */
class PreventCartActions implements ObserverInterface
{
	/**
	 * @var \Yireo\SalesBlock2\Helper\Data
	 */
	private $moduleHelper;

	/**
	 * @var \Magento\Framework\App\RequestInterface
	 */
	private $request;

	/**
	 * @var \Magento\Framework\App\ResponseInterface
	 */
	private $response;

    public function __construct(
    	\Yireo\SalesBlock2\Helper\Data $moduleHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\Data\CartInterface $cart,
        \Magento\Framework\App\RequestInterface $request,
		\Magento\Framework\App\ResponseInterface $response
    )
    {
    	$this->moduleHelper = $moduleHelper;
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param Observer $observer
	 * @return $this
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

        $url = $this->moduleHelper->getUrl();
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
        if ($this->moduleHelper->isAjax() === false) {
            return false;
        }

        $action = $this->request->getActionName();
        if (!in_array($action, array('saveFormValues', 'placeOrder'))) {
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