<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Functional;

use Magento\Framework\Exception\NotFoundException;

class RuleHelperTest extends AbstractTestCase
{
    public function testWithValidEmailAndNoRules()
    {
        $this->expectException(NotFoundException::class);
        $this->getRuleService()->reset();
        $this->getRuleHelper()->findMatch();
    }
}
