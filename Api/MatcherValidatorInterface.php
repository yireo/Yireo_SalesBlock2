<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Api;

use Magento\Framework\Exception\LocalizedException;

interface MatcherValidatorInterface
{
    /**
     * @param string $value
     * @return void
     * @throws LocalizedException
     */
    public function validate(string $value): void;
}
