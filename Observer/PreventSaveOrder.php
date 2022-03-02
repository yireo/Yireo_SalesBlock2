<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Sales\Api\Data\OrderInterface;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;
use Yireo\SalesBlock2\Logger\Debugger;
use Yireo\SalesBlock2\Utils\DestroyQuote;
use Yireo\SalesBlock2\Utils\CurrentEmail;

class PreventSaveOrder implements ObserverInterface
{
    /**
     * @var RuleHelper
     */
    private $ruleHelper;

    /**
     * @var DestroyQuote
     */
    private $destroyQuote;

    /**
     * @var CurrentEmail
     */
    private $currentEmail;

    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * RemoveQuote constructor.
     * @param RuleHelper $ruleHelper
     * @param DestroyQuote $destroyQuote
     * @param CurrentEmail $currentEmail
     * @param Debugger $debugger
     */
    public function __construct(
        RuleHelper $ruleHelper,
        DestroyQuote $destroyQuote,
        CurrentEmail $currentEmail,
        Debugger $debugger
    ) {
        $this->ruleHelper = $ruleHelper;
        $this->destroyQuote = $destroyQuote;
        $this->currentEmail = $currentEmail;
        $this->debugger = $debugger;
    }

    /**
     * @param Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getEvent()->getOrder();
        if (!$order) {
            return;
        }

        $this->currentEmail->setValue((string)$order->getCustomerEmail());

        try {
            $match = $this->ruleHelper->findMatch();
        } catch (NotFoundException $e) {
            $this->debugger->debug('Observer "' . $observer->getEvent()->getName() . '": ' . $e->getMessage());
            return;
        }

        $this->debugger->debug('Observer "' . $observer->getEvent()->getName() . '": Match found');

        $this->destroyQuote->destroy();
        throw new LocalizedException(__($match->getMessage()));
    }
}
