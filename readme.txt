=== EDD EU VAT Compliance ===
Author URI: http://www.mokawebapps.com
Contributors: mokawebapps
Tags: edd, easydigitaldownloads, vat, eu vat, vatmoss, vat moss, european vat, eu tax, european tax, iva, iva ue, taux de TVA, TVA, MwSt,Mehrwertsteuer, digital vat, hmrc, moss, tax, eu vat compliance, vat compliance, vat rates, edd tax, edd vat, easydigitaldownloads tax
Requires at least: 3.7
Tested up to: 4.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

EDD Plugin to comply with Europe VAT regulations for digital goods: Charge your customers the VAT rate according to their location and save evidence required by EU VAT Regulations (VATMOSS)

== Description ==

Easy Digital Downloads Plugin thats let you be in compliance with EU VAT regulations (VATMOSS): To do so, the correct VAT rate (According to your visitors location) is loaded in your EDD checkout page. Also, its make sure the billing address and your visitor location match to use them as two factor validation for the applied VAT rate. 
 

= Features = 
The plugin adds the following features to your Easy Digital Downloads VAT/TAX management.

* **Identify your customers' locations**: The plugin will record evidence of your customer's location, using their billing address and their IP address (via a GeoIP lookup). This information is save in EDD Payments table and easily exported into CSV file.

* **Evidence is recorded:** The information that was used to calculate VAT and customer location (Billing Country and Geo IP Location) is displayed in the EDD order screen in the back-end.

* **Maintaining each country's VAT rates:** If enable, the plugin will download once a month the updated list of VAT Rates using SSL is possible. 


= How this plugin works = 
The plugin updates the Easy Digital Downloads checkout process and load the VAT rate according to your customer billing address from a list of EU vat rates that can be automatically updated once a month. Also, as required by EU TAX Regulations (VATMOSS), two pieces of non contradictory evidence are recorded for each sale: The Billing address and customer's Geo Located country. You can use this information as evidence for EU Digital products VAT regime.


= About GeoIP location =
Your visitor country is identify by checking your visitor IP on data from one of two places:

1. If GeoLite2 database by MaxMind is available it would be used in first place.
2. If the database is not present the following online services will be used: 
	- [hostip.info](http://www.hostip.info)
	- [Telize](http://www.telize.com)
	- [ipinfo.io](http://ipinfo.io)


= Disclaimer = 
The plugin has been designed to help you fulfil the requirements of EU VAT regulations (VATMOSS) using Easy Digital Downloads.

* Identify customers' location.
* Collect at least two non-contradictory pieces of evidence about the determined location.
* Apply the correct VAT rate.
* Ensure that VAT numbers used for B2B transactions are valid before applying VAT exemption. (PRO Version)

We cannot, however, give any legal guarantee that the features provided by this product will be sufficient for you to be fully compliant. By using this product, you declare that you understand and agree that we cannot take any responsibility for errors, omissions or any non-compliance arising from the use of this plugin, alone or together with other products, plugins, themes, extensions or services. It will be your responsibility to check the data produced by this product and file accurate VAT returns on time with your Revenue authority.

Forked from [EDD - Prevent EU Checkout](https://wordpress.org/plugins/edd-prevent-eu-checkout/)

== Installation ==

Following are the steps to install the EDD EU VAT Compliance

1. Unpack the entire contents of this plugin zip file into your wp-content/plugins/ folder locally.
2. Upload to your site.
3. Navigate to wp-admin/plugins.php on your site (your WP Admin plugin page).
4. Activate this plugin.
5. Configure the options from Downloads → Settings → Extensions.

OR you can just install it with WordPress by going to Plugins >> Add New >> and type this plugin's name

= Geo Ip Database Installation =

If you wish to used MaxMind GeoLite2 database you can download it freely from: http://dev.maxmind.com/geoip/geoip2/geolite2/ Once downloaded, please unzip the file and upload the database to the plugin dir.

The database name should be: GeoLite2-Country.mmdb

¿Why is not the database include in the module?

The license from the database make it possible to be ship with plugin and meet GPL 2.0 license.

**Requirements**

1. Easy Digital Downloads

== Frequently Asked Questions ==

= How reliable is the location detection? =
EU VAT Redirect uses the GeoLite database, created by MaxMind (http://www.maxmind.com) and another services which they state they are 99.8% accurate. No location detection will ever be 100% accurate.

= Will the country detection slow down my website? =
The country detection is only done when the user clicks on your buy link. To ensure the detection is performed as quickly as possible, the database can be download and included in the plugin folder. If the file exist, no external websites are called in order to do the country detection.

= What happens if the plugin can't determine the country the user is in? =
If the customer sets their billing address within the EU wont be able to complete the checkout and ask to get in touch.

= Could I use this plugin to block visitors in the EU from buying my product? =
No. If you wish to block your users you should considered using [EDD - Prevent EU Checkout](https://wordpress.org/plugins/edd-prevent-eu-checkout/)

= Can you add a new feature to do X? =
We're always interested in finding out what people would find useful for this plugin, and we will review all feature requests.

= Does this plugin guarantee my compliance with EU VAT? =
Nothing can be guaranteed. You will have to make your own decision as to whether you feel this method is appropriate and demonstrates a reasonable effort on your part to direct taxable sales to the most appropriate payment processor. We recommend that you document your redirection procedure for your records and the date you started to use it. Use of this plugin is at entirely your own risk.

= What if the user's country of residence is in a non-VAT country but they are using an IP address in a VAT country? =
As long as the billing address is outside EU no validation will be performed.

= Does this plugin store any customer data e.g. which IP address was directed where? = 
Yes, the plugin stores the customer country based in their IP Address

= How is this plugin tested? =
We tested the plugin using a range of IP addresses from EU and non-EU countries.

= What is the support policy for this plugin? = 

We offer this plugin free of charge, but we cannot afford to also provide free, direct support for it as we do for our paid products. Should you encounter any difficulties with this plugin, and need support, please report the issue on the Support section, above and we will reply as soon as we can. Posting the request there will also allow other users to see it, and they may be able to assist you.

== Screenshots ==

1. Plugin Settings under EDD Extensions Tab. 
2. Evidence of customer country used to VAT Calculations
3. Notice show to customer on Checkout Page when Billing Country and Ip Location do not match

== Changelog ==

= 1.0 =
* First version
* Tested up to EDD 2.3
