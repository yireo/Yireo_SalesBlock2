<?php

declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Integration\Adminhtml;

use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Data\Form\FormKey;
use Magento\TestFramework\TestCase\AbstractBackendController;
use Yireo\SalesBlock2\Block\Adminhtml\Rule\Edit\SaveButton;
use Yireo\SalesBlock2\Model\ResourceModel\Rule\CollectionFactory;

/**
 * @magentoAppArea adminhtml
 * @magentoDbIsolation enabled
 */
class SaveControllerTest extends AbstractBackendController
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->resource = 'Yireo_SalesBlock2::rules';
        $this->uri = 'backend/salesblock/rule/save';
        $this->httpMethod = HttpRequest::METHOD_POST;
    }

    /**
     * @dataProvider getPostOnlyActions
     */
    public function testGetRequestIsRejected(string $action): void
    {
        $this->getRequest()->setMethod(HttpRequest::METHOD_GET);
        $this->dispatch('backend/salesblock/rule/' . $action . '/id/1');
        $this->assertSame(404, $this->getResponse()->getHttpResponseCode());
    }

    public static function getPostOnlyActions(): array
    {
        return [['save'], ['validate'], ['delete'], ['enable'], ['disable']];
    }

    public function testPostSavesRule(): void
    {
        $this->post([
            'label' => 'Security test rule',
            'conditions' => '',
            'status' => '1',
        ]);

        $this->assertRedirect($this->stringContains('salesblock/rule/index'));
        $this->assertSame(1, $this->countRulesWithLabel('Security test rule'));
    }

    public function testPostWithInvalidConditionsIsRejected(): void
    {
        $this->post([
            'label' => 'Invalid rule',
            'conditions' => '{"broken"',
            'status' => '1',
        ]);

        $this->assertRedirect($this->stringContains('salesblock/rule/form'));
        $this->assertSame(0, $this->countRulesWithLabel('Invalid rule'));
    }

    public function testSaveButtonSubmitsTheForm(): void
    {
        $buttonData = $this->_objectManager->get(SaveButton::class)->getButtonData();
        $this->assertArrayNotHasKey('on_click', $buttonData);
        $this->assertSame('save', $buttonData['data_attribute']['form-role']);
    }

    private function post(array $data): void
    {
        $data['form_key'] = $this->_objectManager->get(FormKey::class)->getFormKey();
        $this->getRequest()->setMethod(HttpRequest::METHOD_POST);
        $this->getRequest()->setPostValue($data);
        $this->dispatch($this->uri);
    }

    private function countRulesWithLabel(string $label): int
    {
        $collection = $this->_objectManager->get(CollectionFactory::class)->create();
        $collection->addFieldToFilter('label', $label);
        return $collection->getSize();
    }
}
