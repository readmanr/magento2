/**
 * Adminhtml client side validation rules
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an e-mail
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Adminhtml
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
(function ($) {
    $.validator.addMethod('validate-rating', function() {
        var ratings = $('#detailed_rating').find('.field-rating');
        var inputs;
        var error = 1;

        ratings.each(function(i, rating) {
            if (i > 0) {
                inputs = $(rating).find('input');

                inputs.each(function(j, input) {
                    if ($(input).is(':checked')) {
                        error = 0;
                    }
                });

                if (error == 1) {
                    return false;
                }
            }
        });
        return !error;
    }, 'Please select one of each ratings above.');
})(jQuery);