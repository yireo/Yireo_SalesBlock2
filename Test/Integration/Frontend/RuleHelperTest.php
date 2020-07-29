<?php

declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Frontend;

use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Helper\Rule;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;

/**
 * Class RuleHelperTest
 * @package Yireo\SalesBlock2\Test\Integration\Frontend
 */
class RuleHelperTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Rule
     */
    private $ruleHelper;

    /**
     * Setup dependencies
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = Bootstrap::getObjectManager();
        $this->ruleHelper = $this->objectManager->get(Rule::class);
    }

    /**
     * @magentoConfigFixture current_store salesblock/settings/enabled 0
     */
    public function testFindRuleWhenDisabled()
    {
        try {
            $this->ruleHelper->findMatch();
            $this->assertTrue(false);
        } catch (NotFoundException $exception) {
            $this->assertSame('SalesBlock is not enabled', $exception->getMessage());
        }
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testFindRuleWithoutAnyRules()
    {
        $this->setConfigValue('salesblock/settings/enabled', 1);

        /** @var Rule $ruleHelper */
        $ruleHelper = $this->objectManager->get(Rule::class);
        $rules = $ruleHelper->getRules();
        $this->assertEmpty($rules);

        try {
            $ruleHelper->findMatch();
            $this->assertTrue(false);
        } catch (NotFoundException $exception) {
            $this->assertSame('No rules are found', $exception->getMessage());
        }
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testFindRuleWithAtLeastOneRuleButNoMatch()
    {
        $this->setConfigValue('salesblock/settings/enabled', 1);
        $this->getRuleProvider()->createRule('ip', '127.0.0.1', true);

        /** @var Rule $ruleHelper */
        $ruleHelper = $this->objectManager->get(Rule::class);
        $rules = $ruleHelper->getRules();
        $this->assertNotEmpty($rules);

        try {
            $ruleHelper->findMatch();
            $this->assertTrue(false);
        } catch (NotFoundException $exception) {
            $this->assertSame('No rule is applicable', $exception->getMessage());
        }
    }

    /**
     * @return RuleProvider
     */
    private function getRuleProvider(): RuleProvider
    {
        return $this->objectManager->get(RuleProvider::class);
    }

    /**
     * @param string $configPath
     * @param $value
     */
    private function setConfigValue(string $configPath, $value)
    {
        $this->objectManager->get(MutableScopeConfigInterface::class)
            ->setValue($configPath, $value);
    }
}
