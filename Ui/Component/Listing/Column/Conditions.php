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

namespace Yireo\SalesBlock2\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Yireo\SalesBlock2\Matcher\MatcherList;

/**
 * Class Conditions
 */
class Conditions extends Column
{
    /**
     * @var MatcherList
     */
    private $matcherList;

    /**
     * Constructor
     *
     * @param MatcherList $matcherList
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        MatcherList $matcherList,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->matcherList = $matcherList;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['conditions'])) {
                    $conditions = $item['conditions'];
                    $item['conditions'] = $this->renderConditions($conditions);
                }
            }
        }

        return $dataSource;
    }

    /**
     * @param string $conditions
     * @return string
     */
    private function renderConditions(string $conditions): string
    {
        $conditionsData = json_decode($conditions, true);
        if (empty($conditionsData)) {
            return $conditions;
        }

        $output = '';
        foreach ($conditionsData as $condition) {
            if (!isset($condition['name'])) {
                continue;
            }

            if (empty($condition['name'])) {
                continue;
            }

            $name = $this->getMatcherNameByCode($condition['name']);

            $output .=  $name . ' = ' . $condition['value'].'<br/>';
        }

        return $output;
    }

    /**
     * @param string $code
     * @return string
     */
    private function getMatcherNameByCode(string $code): string
    {
        $matchers = $this->matcherList->getMatchers();
        foreach ($matchers as $matcher) {
            if ($matcher->getCode() === $code) {
                return $matcher->getName();
            }
        }

        return $code;
    }
}
