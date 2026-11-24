<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Test\Unit\Model;

use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Api\MatcherInterface;
use Yireo\SalesBlock2\Api\MatcherValidatorInterface;
use Yireo\SalesBlock2\Matcher\MatcherList;
use Yireo\SalesBlock2\Model\Rule\ConditionsValidator;

class ConditionsValidatorTest extends TestCase
{
    public function testEmptyConditionsBecomeEmptyArray(): void
    {
        $this->assertSame('[]', $this->getValidator()->validate(null));
        $this->assertSame('[]', $this->getValidator()->validate(''));
    }

    public function testValidConditionsAreNormalized(): void
    {
        $conditions = json_encode([['name' => 'plain', 'value' => "foo\nbar", 'extra' => 'x']]);
        $this->assertSame(
            json_encode([['name' => 'plain', 'value' => "foo\nbar"]]),
            $this->getValidator()->validate($conditions)
        );
    }

    /**
     * @dataProvider getInvalidConditions
     */
    public function testInvalidConditionsAreRejected($conditions): void
    {
        $this->expectException(LocalizedException::class);
        $this->getValidator()->validate($conditions);
    }

    public static function getInvalidConditions(): array
    {
        return [
            ['not json'],
            ['"string"'],
            [['array']],
            [json_encode(['foo'])],
            [json_encode([['name' => 'plain']])],
            [json_encode([['name' => 'plain', 'value' => ['nested']]])],
            [json_encode([['name' => 'unknown', 'value' => 'foo']])],
            [json_encode([['name' => 'plain', 'value' => "foo\x00bar"]])],
            [json_encode([['name' => 'strict', 'value' => 'bad']])],
        ];
    }

    private function getValidator(): ConditionsValidator
    {
        $plain = $this->createMock(MatcherInterface::class);
        $plain->method('getCode')->willReturn('plain');

        $strict = $this->createMockForIntersectionOfInterfaces([
            MatcherInterface::class,
            MatcherValidatorInterface::class,
        ]);
        $strict->method('getCode')->willReturn('strict');
        $strict->method('validate')->willThrowException(new LocalizedException(__('Invalid')));

        return new ConditionsValidator(new MatcherList([$plain, $strict]));
    }
}
