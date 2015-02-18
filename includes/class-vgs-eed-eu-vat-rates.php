<?php

/**
 * EU Vat Rates download and cache
 *
 * @package     EDD EU VAT COMPLIANCE
 * @subpackage  Functions/EU VAT Rates
 * @copyright   Copyright (c) 2014, Conrado Maggi
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 * @author      Conrado Maggi - VGS Global
 */
if (!defined('ABSPATH')) exit;

if (!class_exists('VGS_EED_EU_VAT_Rates')):
    
    class VGS_EED_EU_VAT_Rates
    {
        
        private $which_rate = 'standard_rate';
        private $rates_time_live = 30;
         //How long should we cache the rates
        
        function edd_eu_vat_update_rates() {
            global $edd_options;
            
            if ($edd_options['vgs_edd_eu_vat_remoteupdate'] == 1) {
                $new_rates = false;
                $sources = explode(',', $edd_options['vgs_edd_eu_vat_remoteupdatesource']);
                
                foreach ($sources as $url) {
                    $get = wp_remote_get($url, array('timeout' => 5,));
                    if (is_wp_error($get) || !is_array($get)) continue;
                    if (!isset($get['response']) || !isset($get['response']['code'])) continue;
                    if ($get['response']['code'] >= 300 || $get['response']['code'] < 200 || empty($get['body'])) continue;
                    $rates = json_decode($get['body'], true);
                    if (empty($rates) || !isset($rates['rates'])) continue;
                    $new_rates = $rates['rates'];
                    set_transient('edd_eu_vat_rates', $new_rates, $this->rates_time_live * DAY_IN_SECONDS);
                    break;
                }
            } else {
                
                $new_rates = false;
                $rates = json_decode(file_get_contents(VGS_EDD_EU_VAT_COMPLIANCE_PLUGIN_DIR . '/rates.json'), true);
                $new_rates = $rates['rates'];
                set_transient('edd_eu_vat_rates', $new_rates, $this->rates_time_live * DAY_IN_SECONDS);
            }
            
            return $new_rates;
        }
        
        function get_customer_rate($customer_country) {
            
            if (false === ($eu_rates = get_transient('edd_eu_vat_rates'))) {
                $eu_rates = $this->edd_eu_vat_update_rates();
            }
            
            if (array_key_exists($customer_country, $eu_rates)) {
                
                $eu_vat = $eu_rates[$customer_country][$this->which_rate] / 100;
                return $eu_vat;
            } else {
                
                return false;
            }
        }
    }
endif;
