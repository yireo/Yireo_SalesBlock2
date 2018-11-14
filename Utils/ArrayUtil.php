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

namespace Yireo\SalesBlock2\Utils;

/**
 * Class ArrayUtil
 * @package Yireo\SalesBlock2\Utils
 */
class ArrayUtil
{
    /**
     * @param string $string
     *
     * @return string[]
     */
    public function stringToArray(string $string): array
    {
        $values = explode(',', $string);
        if (empty($values)) {
            $values = explode("\n", $string);
        }

        $newValues = [];
        foreach ($values as $value) {
            $value = trim($value);

            if (!empty($value)) {
                $newValues[] = $value;
            }
        }

        return $newValues;
    }

    /**
     * @param string[] $array
     *
     * @return string
     */
    public function arrayToString(array $array) : string
    {
        return implode(',', $array);
    }
}
