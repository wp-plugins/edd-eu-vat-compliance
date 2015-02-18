<?php

/**
 * TAX Calculations
 *
 * @package     EDD EU VAT COMPLIANCE
 * @subpackage  Functions/Taxes
 * @copyright   Copyright (c) 2014, Conrado Maggi
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 * @author      Conrado Maggi - VGS Global
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

if (!class_exists('VGS_EDD_EU_VAT_Taxes')) :

    class VGS_EDD_EU_VAT_Taxes {

        public function __construct() {
            $this->setup_actions();
        }

        private function setup_actions() {

            add_filter('edd_tax_rate', array($this, 'vgs_edd_eu_vat_get_tax_rate'), 100, 3);
        }

        /**
         * Calculate tax rate
         *
         * @since  1.0
         * @param mixed $rate
         * @param bool $country
         * @param bool $state
         * @return mixed|void
         */
        function vgs_edd_eu_vat_get_tax_rate($rate, $customer_country, $customer_state) {
            global $edd_options;

            if(!isset($edd_options['vgs_edd_eu_vat_usemanual']) || $edd_options['vgs_edd_eu_vat_usemanual'] != 1){
                $edd_eu_vat_rates = new VGS_EED_EU_VAT_Rates();
                            $eu_rate = $edd_eu_vat_rates->get_customer_rate($customer_country);

                            if ($eu_rate != false) {
                                $rate = $eu_rate;
                            }
            }

            return $rate;
        }

    }

    endif;

function vgs_edd_eu_vat_taxes() {
    $vgs_edd_eu_vat_taxes = new VGS_EDD_EU_VAT_Taxes();
}

add_action('plugins_loaded', 'vgs_edd_eu_vat_taxes');
?>