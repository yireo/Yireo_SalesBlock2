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

namespace Yireo\SalesBlock2\Test\Unit\Utils;

use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Utils\ArrayUtil;

class ArrayUtilTest extends TestCase
{
    /**
     * @dataProvider getStringToArrayData
     * @param string $string
     * @param string[] $expected
     */
    public function testStringToArray(string $string, array $expected)
    {
        $this->assertSame($expected, (new ArrayUtil())->stringToArray($string));
    }

    /**
     * @return array
     */
    public static function getStringToArrayData(): array
    {
        return [
            ['', []],
            ['foo', ['foo']],
            ['foo,bar', ['foo', 'bar']],
            [' foo , bar ,, ', ['foo', 'bar']],
            ["foo\nbar", ['foo', 'bar']],
            ["foo\r\nbar\n\n", ['foo', 'bar']],
            ["foo,bar\nbaz", ['foo', 'bar', 'baz']],
        ];
    }

    public function testArrayToString()
    {
        $this->assertSame('foo,bar', (new ArrayUtil())->arrayToString(['foo', 'bar']));
        $this->assertSame('', (new ArrayUtil())->arrayToString([]));
    }
}
