<?php
/**
 * Compliance with VAT MOSS related functions
 *
 * @package     EDD EU VAT COMPLIANCE
 * @subpackage  Functions/Compliance
 * @copyright   Copyright (c) 2014, Conrado Maggi
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 * @author      Conrado Maggi - VGS Global
 */
if (!defined('ABSPATH'))
    exit;

if (!class_exists('VGS_EDD_EU_VAT_Compliance_Checks')):

    class VGS_EDD_EU_VAT_Compliance_Checks {

        public function __construct() {
            $this->setup_actions();
        }

        private function setup_actions() {

            //Store the GEOIP Country code
            add_filter('edd_payment_meta', array($this, 'vgs_edd_store_goeip_information'));

            //Validate that billing country and geoip do match
            add_action('edd_checkout_error_checks', array($this, 'vgs_edd_validate_billing_country'), 10, 2);

            // Display the country compliance information in payment view
            add_action('edd_view_order_details_billing_after', array($this, 'vgs_edd_view_order_vat_details'), 10, 2);
        }

        function vgs_edd_validate_billing_country($valid_data, $data) {

            $vgs_geoip = new VGS_EDD_EU_VAT_Compliance_GeoIP();

            if (!$this->vgs_edd_in_europe($data['billing_country'])) {
                return true;
            }

            if ($data['billing_country'] != $vgs_geoip->vgs_edd_get_customer_ip_country()) {
                edd_set_error('country_not_match', __('Your IP Address does not match your billing country. Please contact us to complete your order', 'vgs_edd_eu_vat_compliance'));
            }
        }

        function vgs_edd_in_europe($country_iso_code) {
            global $edd_options;

            $countries = $this->vgs_edd_get_country_list();
            if (array_key_exists($country_iso_code, $countries) && $edd_options['vgs_edd_eu_vat_exclude'] != $country_iso_code) {
                return true;
            } else {
                return false;
            }
        }

              
        public static function vgs_edd_get_country_list() {
            $countries = array('AT' => 'Austria', 'BE' => 'Belgium', 'BG' => 'Bulgaria', 'CY' => 'Republic of Cyprus', 'CZ' => 'Czech Republic', 'DE' => 'Germany', 'DK' => 'Denmark', 'EE' => 'Estonia', 'EL' => 'Greece', 
                'ES' => 'Spain', 'FI' => 'Finland', 'FR' => 'France', 'GB' => 'United Kingdom', 'GR' => 'Greece', 'HR' => 'Croatia', 'HU' => 'Hungary', 'IE' => 'Ireland', 'IT' => 'Italy', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'LV' => 'Latvia', 'MT' => 'Malta', 'NL' => 'Netherlands', 'PL' => 'Poland', 'PT' => 'Portugal', 'RO' => 'Romania', 'SE' => 'Sweden', 'SI' => 'Slovenia', 'SK' => 'Slovakia');
            return $countries;
        }

        function vgs_edd_store_goeip_information($payment_meta) {
            $vgs_geoip = new VGS_EDD_EU_VAT_Compliance_GeoIP();

            $payment_meta['user_geoip_country'] = $vgs_geoip->vgs_edd_get_customer_ip_country();
            return $payment_meta;
        }

        function vgs_edd_view_order_vat_details($paymemt_id) {

            $payment_meta = get_post_meta($paymemt_id, '_edd_payment_meta', true);
            $edd_get_country_list = edd_get_country_list();

            $geoip_country = isset($payment_meta['user_geoip_country']) ? $edd_get_country_list[$payment_meta['user_geoip_country']] : __('Country not Registered', 'vgs_edd_eu_vat_compliance');
            $billing_country = isset($payment_meta['user_info']['address']['country']) ? $edd_get_country_list[$payment_meta['user_info']['address']['country']] : 'none';
            ?>
            <div id="edd-payment-notes" class="postbox">
                <h3 class="hndle"><span><?php _e('EU VAT Compliance Information', 'vgs_edd_eu_vat_compliance'); ?></span></h3>
                <div class="inside">
                    <div class="column-container">
                        <div class="column">
                            <strong><?php _e('Customer Country by IP Address', 'vgs_edd_eu_vat_compliance'); ?></strong>
                            <input type="text" name="edd_phone" value="<?php esc_attr_e($geoip_country); ?>" class="medium-text" readonly=""/>
                            <p class="description"><?php _e('Customer Country determine by Maxmind GEOIP database', 'vgs_edd_eu_vat_compliance'); ?></p>
                        </div>

                        <div class="column">
                            <strong><?php _e('Customer Billing Country', 'edd_eu_vat_compliance'); ?></strong>
                            <input type="text" name="edd_phone" value="<?php esc_attr_e($billing_country); ?>" class="medium-text" readonly=""/>
                            <p class="description"><?php _e('Customer Country determine by Billing Country', 'edd_eu_vat_compliance'); ?></p>
                        </div>
                    </div>
                </div></div>
            <?php
        }

    }

    endif;

/**
 * Get everything running
 *
 * @since 1.0
 *
 * @access private
 * @return void
 */
function vgs_edd_eu_vat_compliance_check() {
    $vgs_edd_vat_compliance_check = new VGS_EDD_EU_VAT_Compliance_Checks();
}

add_action('plugins_loaded', 'vgs_edd_eu_vat_compliance_check');

