<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Adminhtml;

use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Message\MessageInterface;
use Magento\TestFramework\TestCase\AbstractBackendController;
use Yireo\SalesBlock2\Api\RuleRepositoryInterface;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;

/**
 * @magentoAppArea adminhtml
 * @magentoDbIsolation enabled
 */
class MassactionTest extends AbstractBackendController
{
    public function testSelectAllEnablesEveryRule(): void
    {
        $ruleProvider = $this->_objectManager->get(RuleProvider::class);
        $ruleIds = [
            (int)$ruleProvider->createRule('test_always', 'a', false)->getId(),
            (int)$ruleProvider->createRule('test_always', 'b', false)->getId(),
        ];

        $this->post('enable', ['namespace' => 'salesblock_rule_grid', 'excluded' => 'false']);

        $this->assertRedirect($this->stringContains('salesblock/rule/index'));
        $ruleRepository = $this->_objectManager->create(RuleRepositoryInterface::class);
        foreach ($ruleIds as $ruleId) {
            $this->assertSame(1, $ruleRepository->get($ruleId)->getStatus());
        }
    }

    public function testSelectedRuleIsDisabled(): void
    {
        $rule = $this->_objectManager->get(RuleProvider::class)->createRule('test_always', 'a', true);
        $this->post('disable', ['namespace' => 'salesblock_rule_grid', 'selected' => [$rule->getId()]]);

        $ruleRepository = $this->_objectManager->create(RuleRepositoryInterface::class);
        $this->assertSame(0, $ruleRepository->get((int)$rule->getId())->getStatus());
    }

    public function testUnknownRuleShowsError(): void
    {
        $this->post('delete', ['id' => 999999]);

        $this->assertRedirect($this->stringContains('salesblock/rule/index'));
        $this->assertSessionMessages($this->logicalNot($this->isEmpty()), MessageInterface::TYPE_ERROR);
    }

    private function post(string $action, array $data): void
    {
        $data['form_key'] = $this->_objectManager->get(FormKey::class)->getFormKey();
        $this->getRequest()->setMethod(HttpRequest::METHOD_POST);
        $this->getRequest()->setPostValue($data);
        $this->dispatch('backend/salesblock/rule/' . $action);
    }
}
