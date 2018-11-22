/**
 * Yireo SalesBlock2 for Magento
 *
 * @package     Yireo_SalesBlock2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * Component to handle parsing conditions per SalesBlock rule
 */
define([
    'Magento_Ui/js/form/element/abstract',
    'jquery',
    'uiRegistry',
    'ko'
], function (AbstractFormElement, $, registry, ko) {
    'use strict';

    /**
     * Subclass to represent a specific condition within a rule
     *
     * @param matchName
     * @param matchValue
     * @constructor
     */
    var Condition = function (matchName = '', matchValue = '') {
        this.matchName = ko.observable(matchName);
        this.matchValue = ko.observable(matchValue);
        this.matchers = {};
    };

    return AbstractFormElement.extend({
        /**
         * Temporary data storage between KO observable and JSON value
         */
        conditionData: {},

        /**
         * Initialize this UiComponent
         *
         * @returns {exports}
         */
        initialize: function () {
            this._super();

            this.conditions = ko.observableArray();
            this.conditions.subscribe(function (conditions) {
                this.onChangeConditions(conditions);
            }, this);

            this.addInitialConditions();
            return this;
        },

        /**
         * When loading this component, bring alive all saved conditions in the form
         *
         * @param value
         * @returns {exports}
         */
        addInitialConditions: function (value) {
            if (!this.value()) {
                return this;
            }

            var conditions = JSON.parse(this.value());
            if (!conditions) {
                return this;
            }

            // Loop through the saved data and push them into the observable array
            $.each(conditions, (function (index, element) {
                var condition = new Condition(element.name, element.value);
                condition.matcherOptions = this.getMatcherOptions();
                condition.matchName.subscribe(this.parseConditionValues.bind(this));
                condition.matchValue.subscribe(this.parseConditionValues.bind(this));
                this.conditions.push(condition);
            }).bind(this));
        },

        /**
         * Push a new empty condition to the observable array
         */
        addConditionField: function () {
            var condition = new Condition();
            condition.matcherOptions = this.getMatcherOptions();
            condition.matchName.subscribe(this.parseConditionValues.bind(this));
            condition.matchValue.subscribe(this.parseConditionValues.bind(this));

            this.conditions.push(condition);
        },

        /**
         * Remove an existing condition from the observable array
         *
         * @param conditionIndex
         */
        removeConditionField: function (conditionIndex) {
            this.conditions.splice(conditionIndex, 1);
        },

        /**
         * Helper method to add options for the matcher select-box
         *
         * @returns {Array}
         */
        getMatcherOptions: function () {
            this.matcherOptions = [];
            $.each(this.matchers, (function (name, value) {
                this.matcherOptions.push({name: name, value: value});
            }).bind(this));

            return this.matcherOptions;
        },

        /**
         *
         * @param conditions
         */
        onChangeConditions: function (conditions) {
            this.conditionData = [];
            $.each(conditions, (function (index, condition) {
                this.conditionData[index] = {name: condition.matchName(), value: condition.matchValue()};
            }).bind(this));

            this.value(JSON.stringify(this.conditionData));
            return true;
        },

        /**
         * Convert all HTML input into JSON, but with a delay to await the KO bubble
         *
         * @returns {boolean}
         */
        parseConditionValues: function () {
            this.conditionData = [];
            $.each(this.conditions(), (function (index, condition) {
                this.conditionData[index] = {name: condition.matchName(), value: condition.matchValue()};
            }).bind(this));

            this.value(JSON.stringify(this.conditionData));
            return true;
        },

        /**
         * Get the current data used in this form
         *
         * @returns {*}
         * @deprecated
         */
        getEntityData: function () {
            return registry.get(this.provider);
        }
    });
});
