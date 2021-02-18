<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Functional;

use Magento\Framework\Exception\NotFoundException;

class RuleHelperWithEmailTest extends AbstractTestCase
{
    public function testWithEmailButNoMatchingRules()
    {
        $this->getCurrentEmail()->setValue('john@example.com');

        $rule = $this->createRule('Dummy', ['name' => 'email', 'value' => '@example2.com']);
        $this->getRuleService()->addRule($rule);

        $this->expectException(NotFoundException::class);
        $this->getRuleHelper()->findMatch();
    }

    /**
     * @throws NotFoundException
     */
    public function testWithEmailAndMatchingRules()
    {
        $this->getCurrentEmail()->setValue('john@example.com');

        $rule = $this->createRule('Dummy', ['name' => 'email', 'value' => '@example.com']);
        $this->getRuleService()->addRule($rule);

        $match = $this->getRuleHelper()->findMatch();
        $this->assertNotFalse($match);
        $this->assertEquals('Dummy', $match->getRule()->getLabel());
    }
}
