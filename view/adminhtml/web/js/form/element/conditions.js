/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

define([
    'Magento_Ui/js/form/element/abstract',
    'jquery',
    'uiRegistry',
    'ko'
], function (AbstractFormElement, $, registry, ko) {
    'use strict';

    var Condition = function (matchName = '', matchValue = '') {
        this.matchName = matchName;
        this.matchValue = matchValue;
    };

    return AbstractFormElement.extend({
        initialize: function () {
            this._super();
            this.conditions = ko.observableArray();
            this.conditionData = {};
            this.parseInitialValue();
            return this;
        },

        parseInitialValue: function(value) {
            if (!this.value()) {
                return this;
            }

            var conditions = JSON.parse(this.value());
            if (!conditions) {
                return this;
            }

            $.each(conditions, (function(index, element) {
                this.conditions.push(new Condition(element.name, element.value));
            }).bind(this));
        },

        addConditionField: function () {
            this.conditions.push(new Condition());
        },

        onChangeConditions: function () {
            $('input.salesblock-rule-condition').each((function (index, element) {
                var dataIndex = $(element).attr('data-index');
                var $select = $('select.salesblock-rule-condition[data-index=' + dataIndex + ']');
                var matchName = $select.val();
                var matchValue = $(element).val();
                this.conditionData[dataIndex] = {name: matchName, value: matchValue};
            }).bind(this));

            this.value(JSON.stringify(this.conditionData));
        },

        getEntityData: function () {
            return registry.get(this.provider);
        }
    });
});
