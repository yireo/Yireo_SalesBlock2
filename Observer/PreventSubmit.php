<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Yireo\SalesBlock2\Helper\Rule as RuleHelper;
use Yireo\SalesBlock2\Logger\Debugger;
use Yireo\SalesBlock2\Utils\DestroyQuote;

class PreventSubmit implements ObserverInterface
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
     * @var Debugger
     */
    private $debugger;
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * RemoveQuote constructor.
     * @param RuleHelper $ruleHelper
     * @param DestroyQuote $destroyQuote
     * @param Debugger $debugger
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        RuleHelper $ruleHelper,
        DestroyQuote $destroyQuote,
        Debugger $debugger,
        ManagerInterface $messageManager
    ) {
        $this->ruleHelper = $ruleHelper;
        $this->destroyQuote = $destroyQuote;
        $this->debugger = $debugger;
        $this->messageManager = $messageManager;
    }

    /**
     * @param Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        try {
            $match = $this->ruleHelper->findMatch();
        } catch (NotFoundException $e) {
            $this->debugger->debug('Observer "' . $observer->getEvent()->getName() . '": ' . $e->getMessage());
            return;
        }

        $this->messageManager->addWarningMessage($match->getMessage());
        $this->debugger->debug('Observer "' . $observer->getEvent()->getName() . '": Match found');

        $this->destroyQuote->destroy();
        throw new LocalizedException(__($match->getMessage()));
    }
}
