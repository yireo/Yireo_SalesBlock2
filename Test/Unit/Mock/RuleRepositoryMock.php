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

namespace Yireo\SalesBlock2\Test\Unit\Mock;

use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;

/**
 * Trait RuleRepositoryMock
 *
 * @package Yireo\SalesBlock2\Test\Unit\Mock
 */
trait RuleRepositoryMock
{
    /**
     * @return RuleRepositoryInterface
     */
    protected function getRuleRepositoryMock()
    {
        /** @var RuleRepositoryInterface $ruleRepository */
        $ruleRepository = $this->getMockBuilder(RuleRepositoryInterface::class)
            ->getMockForAbstractClass();

        $ruleRepository->expects($this->any())
            ->method('create')
            ->will($this->returnValue($this->getRuleMock())
            );

        $ruleRepository->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->getRuleMock())
            );

        $ruleRepository->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true)
            );

        return $ruleRepository;
    }

    /**
     * @return RuleInterface
     */
    protected function getRuleMock()
    {
        /** @var RuleInterface $rule */
        $rule = $this->getMockBuilder(RuleInterface::class)
            ->getMockForAbstractClass();

        return $rule;
    }
}