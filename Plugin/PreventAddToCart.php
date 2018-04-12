<?php
namespace Yireo\SalesBlock2\Plugin;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\Data\CartInterface;
use Yireo\SalesBlock2\Exception\RuleMatchedException;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;

/**
 * Plugin PreventAddToCart
 * @package Yireo\SalesBlock2\Plugin
 */
class PreventAddToCart
{
    /**
     * @var RuleHelper
     */
    private $ruleHelper;
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        RuleHelper $ruleHelper,
        ManagerInterface $messageManager
    )
    {
        $this->ruleHelper = $ruleHelper;
        $this->messageManager = $messageManager;
    }

    /**
     * @param CartInterface $subject
     * @param Product $product
     * @param null $request
     * @param string $processMode
     * @throws RuleMatchedException
     */
    public function beforeAddProduct(CartInterface $subject, Product $product, $request = null, $processMode = AbstractType::PROCESS_MODE_FULL)
    {
        if ($this->hasMatch()) {
            $message = __('You are not allowed to purchase any products from your IP'). ': '.$this->ruleHelper->getIp();
            $exception = new RuleMatchedException($message);
            $this->messageManager->addException($exception, $message);
            throw $exception;
        }
    }

    /**
     * @return bool
     */
    private function hasMatch(): bool
    {
        return $this->ruleHelper->hasMatch();
    }
}