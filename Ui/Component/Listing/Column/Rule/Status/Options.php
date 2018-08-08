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

namespace Yireo\SalesBlock2\Ui\Component\Listing\Column\Rule\Status;

use Magento\Framework\Data\OptionSourceInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;

/**
 * Class Options
 */
class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var RuleRepositoryInterface
     */
    protected $ruleRepository;

    /**
     * Constructor
     *
     * @param RuleRepositoryInterface $ruleRepository
     */
    public function __construct(RuleRepositoryInterface $ruleRepository)
    {
        $this->ruleRepository = $ruleRepository;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];

            $this->options[] = [
                'value' => 0,
                'label' => __('Disabled')
            ];

            $this->options[] = [
                'value' => 1,
                'label' => __('Enabled')
            ];
        }

        return $this->options;
    }
}
