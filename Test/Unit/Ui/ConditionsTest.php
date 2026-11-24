<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Unit\Ui;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Api\MatcherInterface;
use Yireo\SalesBlock2\Matcher\MatcherList;
use Yireo\SalesBlock2\Ui\Component\Listing\Column\Conditions;

class ConditionsTest extends TestCase
{
    public function testConditionValuesAreEscaped(): void
    {
        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->method('getCode')->willReturn('email');
        $matcher->method('getName')->willReturn('Email <b>address</b>');

        $column = new Conditions(
            new MatcherList([$matcher]),
            new Escaper(),
            $this->createMock(ContextInterface::class),
            $this->createMock(UiComponentFactory::class)
        );

        $conditions = json_encode([
            ['name' => 'email', 'value' => '<img src=x onerror=alert(1)>'],
            ['name' => '<script>', 'value' => 'foo'],
        ]);

        $dataSource = $column->prepareDataSource(['data' => ['items' => [['conditions' => $conditions]]]]);
        $output = $dataSource['data']['items'][0]['conditions'];

        $this->assertStringNotContainsString('<img', $output);
        $this->assertStringNotContainsString('<script>', $output);
        $this->assertStringNotContainsString('<b>', $output);
        $this->assertStringContainsString('&lt;img src=x onerror=alert(1)&gt;', $output);
        $this->assertStringContainsString('<br/>', $output);
    }

    public function testInvalidJsonIsEscaped(): void
    {
        $column = new Conditions(
            new MatcherList([]),
            new Escaper(),
            $this->createMock(ContextInterface::class),
            $this->createMock(UiComponentFactory::class)
        );

        $dataSource = $column->prepareDataSource(['data' => ['items' => [['conditions' => '<svg onload=x>']]]]);
        $this->assertSame('&lt;svg onload=x&gt;', $dataSource['data']['items'][0]['conditions']);
    }
}
