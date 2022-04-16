<?php declare(strict_types=1);
/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2\Match;

use Yireo\SalesBlock2\Api\Data\RuleInterface;

/**
 * Class RuleMatch
 * @package Yireo\SalesBlock2\Matcher
 */
class RuleMatch
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var array
     */
    private $variables = [];

    /**
     * Match constructor.
     * @param string $message
     */
    public function __construct(
        string $message = ''
    ) {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        $ruleMessage = $this->getRule()->getFrontendLabel();
        if ($ruleMessage) {
            $message = $ruleMessage;
        }

        if (empty($message)) {
            $message = $this->message;
        }

        foreach ($this->variables as $variableName => $variableValue) {
            $message = str_replace('%'.$variableName.'%', $variableValue, $message);
        }

        return $message;
    }

    /**
     * @return RuleInterface
     */
    public function getRule(): RuleInterface
    {
        return $this->rule;
    }

    /**
     * @param RuleInterface $rule
     * @return RuleMatch
     */
    public function setRule(RuleInterface $rule): RuleMatch
    {
        $this->rule = $rule;
        return $this;
    }

    /**
     * @param array $variables
     * @return mixed
     */
    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }
}
