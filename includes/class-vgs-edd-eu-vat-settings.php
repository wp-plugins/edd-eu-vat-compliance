<?php

/**
 * Settings
 *
 * @package     EDD EU VAT COMPLIANCE
 * @subpackage  Functions/Settings
 * @copyright   Copyright (c) 2014, Conrado Maggi
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 * @author      Conrado Maggi - VGS Global
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;


if (!class_exists('VGS_EDD_EU_VAT_Settings')) :

    class VGS_EDD_EU_VAT_Settings {

        public function __construct() {
            $this->setup_actions();
        }

        private function setup_actions() {

            add_filter('edd_settings_extensions', array($this, 'vgs_edd_eu_vat_settings'));

            add_filter('edd_settings_extensions_sanitize', array($this, 'vgs_edd_eu_vat_sanitize_settings'));

            add_action('update_option_edd_settings', array($this, 'vgs_edd_eu_vat_update_settings'),10,2);
        }

        function vgs_edd_eu_vat_update_settings($oldvalue, $value) {

            //If user enable GeopIp download the database and register crons to update Geo DB
            if ($value['vgs_edd_eu_vat_geoipmaxmind'] == 1 && $oldvalue['vgs_edd_eu_vat_geoipmaxmind'] == 0) {
                $vgs_edd_eu_vat_compliance_geoip = new VGS_EDD_EU_VAT_Compliance_GeoIP();
                $vgs_edd_eu_vat_compliance_geoip->vgs_edd_eu_vat_download_geodb();
                $vgs_edd_eu_vat_compliance_geoip->vgs_edd_eu_vat_addactions();
                $vgs_edd_eu_vat_compliance_geoip->vgs_edd_eu_vat_register_crons();
            }

            //If the user disable GeoIp remove the crons
            if ($oldvalue['vgs_edd_eu_vat_geoipmaxmind'] == 1 && $value['vgs_edd_eu_vat_geoipmaxmind'] == 0) {
                VGS_EDD_EU_VAT_Compliance_GeoIP::vgs_edd_eu_vat_remove_crons();
            }
        }

        function vgs_edd_eu_vat_settings($settings) {

            $country_list = VGS_EDD_EU_VAT_Compliance_Checks::vgs_edd_get_country_list();

            $edd_vat_calculator_settings = array(
                array(
                    'id' => 'vgs_edd_eu_vat_compliance_settings',
                    'name' => '<strong>' . __('EDD EU VAT Compliance Settings', 'vgs_edd_eu_vat_compliance') . '</strong>',
                    'type' => 'header'
                ),
                array(
                    'id' => 'vgs_edd_eu_vat_usemanual',
                    'name' => __('Use EDD Tax Table for VAT Rates.', 'vgs_edd_eu_vat_compliance'),
                    'desc' => __('  Check this to use EDD Tax table for EU countries. If left empty the plugin will use the VAT rates included in the file rates.json', 'vgs_edd_eu_vat_compliance'),
                    'type' => 'checkbox',
                ),
                array(
                    'id' => 'vgs_edd_eu_vat_remoteupdate',
                    'name' => __('Enable autoupdate of VAT Rates', 'vgs_edd_eu_vat_compliance'),
                    'desc' => __('  When enable the plugin will update the EU VAT rates information once a month.', 'vgs_edd_eu_vat_compliance'),
                    'type' => 'checkbox',
                ),
                array(
                    'id' => 'vgs_edd_eu_vat_remoteupdatesource',
                    'name' => __('Remote Update Sources', 'vgs_edd_eu_vat_compliance'),
                    'desc' => __('  URLs from which the VAT Rates information will be download. Separate by comma', 'vgs_edd_eu_vat_compliance'),
                    'type' => 'textarea',
                    'std' => 'https://wceuvatcompliance.s3.amazonaws.com/rates.json, https://euvatrates.com/rates.json, http://wceuvatcompliance.s3.amazonaws.com/rates.json, http://euvatrates.com/rates.json'
                ),
                array(
                    'id' => 'vgs_edd_eu_vat_geoipmaxmind',
                    'name' => __('Allow download MaxMind Geolite2 Database', 'vgs_edd_eu_vat_compliance'),
                    'desc' => __('  When enable the plugin will download MaxMind Free Geolite2 Database and use it to identify customers location.', 'vgs_edd_eu_vat_compliance'),
                    'type' => 'checkbox',
                ),
                array(
                    'id' => 'vgs_edd_eu_vat_hostinfo',
                    'name' => __('Allow download customer location information from host.info', 'vgs_edd_eu_vat_compliance'),
                    'desc' => __('  When enable the plugin will use this host.info in case GeoIP Database is not present or not enable.', 'vgs_edd_eu_vat_compliance'),
                    'type' => 'checkbox',
                )
            );

            return array_merge($settings, $edd_vat_calculator_settings);
        }

        function vgs_edd_eu_vat_sanitize_settings($input) {

            if (!isset($input['vgs_edd_eu_vat_usemanual']) || $input['vgs_edd_eu_vat_usemanual'] != '1') {
                $input['vgs_edd_eu_vat_usemanual'] = 0;
            } else {
                $input['vgs_edd_eu_vat_usemanual'] = 1;
            }


            if (!isset($input['vgs_edd_eu_vat_remoteupdate']) || $input['vgs_edd_eu_vat_remoteupdate'] != '1') {
                $input['vgs_edd_eu_vat_remoteupdate'] = 0;
            } else {
                $input['vgs_edd_eu_vat_remoteupdate'] = 1;
            }

            if (!isset($input['vgs_edd_eu_vat_hostinfo']) || $input['vgs_edd_eu_vat_hostinfo'] != '1') {
                $input['vgs_edd_eu_vat_hostinfo'] = 0;
            } else {
                $input['vgs_edd_eu_vat_hostinfo'] = 1;
            }

            if (!isset($input['vgs_edd_eu_vat_freegeoip']) || $input['vgs_edd_eu_vat_freegeoip'] != '1') {
                $input['vgs_edd_eu_vat_freegeoip'] = 0;
            } else {
                $input['vgs_edd_eu_vat_freegeoip'] = 1;
            }

            if (!isset($input['vgs_edd_eu_vat_geoipmaxmind']) || $input['vgs_edd_eu_vat_geoipmaxmind'] != '1') {
                $input['vgs_edd_eu_vat_geoipmaxmind'] = 0;
            } else {
                $input['vgs_edd_eu_vat_geoipmaxmind'] = 1;
            }

            $input['vgs_edd_eu_vat_remoteupdatesource'] = wp_kses_post($input['vgs_edd_eu_vat_remoteupdatesource']);

            return $input;
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
function vgs_edd_eu_vat_settings() {
    $vgs_edd_eu_vat_settings = new VGS_EDD_EU_VAT_Settings();
}

add_action('plugins_loaded', 'vgs_edd_eu_vat_settings');
?>