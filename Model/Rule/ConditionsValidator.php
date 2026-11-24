<?php declare(strict_types=1);

namespace Yireo\SalesBlock2\Model\Rule;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Yireo\SalesBlock2\Api\MatcherValidatorInterface;
use Yireo\SalesBlock2\Matcher\MatcherList;

class ConditionsValidator
{
    /**
     * @var MatcherList
     */
    private $matcherList;

    /**
     * @param MatcherList $matcherList
     */
    public function __construct(
        MatcherList $matcherList
    ) {
        $this->matcherList = $matcherList;
    }

    /**
     * @param mixed $conditions
     * @return string
     * @throws LocalizedException
     */
    public function validate($conditions): string
    {
        if ($conditions === null || $conditions === '') {
            return '[]';
        }

        if (!is_string($conditions)) {
            throw new LocalizedException(__('Conditions are invalid'));
        }

        $conditionsData = json_decode($conditions, true);
        if (!is_array($conditionsData)) {
            throw new LocalizedException(__('Conditions are invalid'));
        }

        $validConditions = [];
        foreach ($conditionsData as $condition) {
            $validConditions[] = $this->validateCondition($condition);
        }

        return (string)json_encode($validConditions);
    }

    /**
     * @param mixed $condition
     * @return array
     * @throws LocalizedException
     */
    private function validateCondition($condition): array
    {
        if (!is_array($condition)
            || !isset($condition['name'], $condition['value'])
            || !is_string($condition['name'])
            || !is_scalar($condition['value'])) {
            throw new LocalizedException(__('Conditions are invalid'));
        }

        $name = $condition['name'];
        $value = (string)$condition['value'];

        try {
            $matcher = $this->matcherList->getMatcherByCode($name);
        } catch (NotFoundException $exception) {
            throw new LocalizedException(__('Unknown matcher "%1"', $name));
        }

        if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $value)) {
            throw new LocalizedException(__('Condition "%1" contains invalid characters', $name));
        }

        if ($matcher instanceof MatcherValidatorInterface) {
            $matcher->validate($value);
        }

        return ['name' => $name, 'value' => $value];
    }
}
