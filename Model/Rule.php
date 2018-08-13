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

namespace Yireo\SalesBlock2\Model;

use Magento\Framework\Model\AbstractModel;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Model\ResourceModel\Rule as RuleResourceModel;

/**
 * Class Rule
 *
 * @package Yireo\SalesBlock2\Model
 */
class Rule extends AbstractModel implements RuleInterface
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(RuleResourceModel::class);
    }

    /**
     * @param string $label
     *
     * @return mixed
     */
    public function setLabel(string $label)
    {
        return $this->setData('label', trim(strip_tags($label)));
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return (string) $this->getData('label');
    }

    /**
     * @param string $emailValue
     *
     * @return mixed
     */
    public function setEmailValue(string $emailValue)
    {
        $emailValues = $this->stringToArray($emailValue);
        return $this->setData('email_value', $this->arrayToString($emailValues));
    }

    /**
     * @return string
     */
    public function getEmailValue(): string
    {
        return (string) $this->getData('email_value');
    }

    /**
     * @param string $ipValue
     *
     * @return mixed
     */
    public function setIpValue(string $ipValue)
    {
        $ipValues = $this->stringToArray($ipValue);
        return $this->setData('ip_value', $this->arrayToString($ipValues));
    }

    /**
     * @return string
     */
    public function getIpValue(): string
    {
        return (string) $this->getData('ip_value');
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus(int $status)
    {
        return $this->setData('status', (int)(bool)$status);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return (int) $this->getData('status');
    }

    /**
     * @param string $frontendLabel
     *
     * @return mixed
     */
    public function setFrontendLabel(string $frontendLabel)
    {
        return $this->setData('frontend_label', trim(strip_tags($frontendLabel)));
    }

    /**
     * @return string
     */
    public function getFrontendLabel(): string
    {
        return (string) $this->getData('frontend_label');
    }

    /**
     * @param string $frontendText
     *
     * @return mixed
     */
    public function setFrontendText(string $frontendText)
    {
        return $this->setData('frontend_text', trim($frontendText));
    }

    /**
     * @return string
     */
    public function getFrontendText(): string
    {
        return (string) $this->getData('frontend_text');
    }

    /**
     * @param string $string
     *
     * @return string[]
     */
    private function stringToArray(string $string): array
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
    private function arrayToString(array $array) : string
    {
        return implode(',', $array);
    }
}