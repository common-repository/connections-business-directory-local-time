=== Connections Business Directory Local Time ===
Contributors: shazahm1@hotmail.com
Donate link: https://connections-pro.com/
Tags: business directory, local time, clock
Requires at least: 5.1
Tested up to: 5.7
Requires PHP: 5.6.20
Stable tag: 1.2.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An extension for Connections Business Directory which adds the ability to show the local time of a business or an individual based on their address.

== Description ==

Connections Business Directory Local Time extension is a remix of the fantastic [jClocksGMT World Clocks plugin](https://wordpress.org/plugins/jclocksgmt-wp/)
that has been custom tailored for use with Connections Business Directory.

How does it work?

The local time zone is calculated from the latitude/longitude of either the first or preferred address from the entry in Connections Business Directory.
The latitude/longitude is then used to determine the time zone and the UTC offset in which the address is located using the Google Maps Time Zone API.

With that information determined automagically, a clock is displayed in the the theme's sidebar showing the local time of the
current entry being viewed.

Features:

* Automagically determines time zone and UTC offset using the latitude/longitude of either the first or preferred address from the entry being viewed.
* Customizable widget.
* Analog clock with 5 skin variations.
* Digital clock with custom time formatting.
* Display the local date with custom date formatting.

Here are some other great **free extensions** (with more on the way) that enhance your experience with Connections Business Directory:

**Utility**

* [Toolbar](https://wordpress.org/plugins/connections-toolbar/) :: Provides quick links to the admin pages from the admin bar.
* [Login](https://wordpress.org/plugins/connections-business-directory-login/) :: Provides a simple to use login shortcode and widget.

**Custom Fields**

* [Business Open Hours](https://wordpress.org/plugins/connections-business-directory-hours/) :: Add business open hours.
* [Income Level](https://wordpress.org/plugins/connections-business-directory-income-levels/) :: Add an income level.
* [Education Level](https://wordpress.org/plugins/connections-business-directory-education-levels/) :: Add an education level.
* [Languages](https://wordpress.org/plugins/connections-business-directory-languages/) :: Add languages spoken.
* [Hobbies](https://wordpress.org/plugins/connections-business-directory-hobbies/) :: Add hobbies.

**Misc**

* [Face Detect](https://wordpress.org/plugins/connections-business-directory-face-detect/) :: Applies face detection before cropping an image.


== Installation ==

[Complete installation instructions can be found here.](https://connections-pro.com/documentation/local-time/#Installation)

Using the WordPress Plugin Search

1. Navigate to the `Add New` sub-page under the Plugins admin page.
2. Search for `connections business directory local time`.
3. The plugin should be listed first in the search results.
4. Click the `Install Now` link.
5. Lastly click the `Activate Plugin` link to activate the plugin.

Uploading in WordPress Admin

1. [Download the plugin zip file](https://wordpress.org/plugins/connections-business-directory-local-time/) and save it to your computer.
2. Navigate to the `Add New` sub-page under the Plugins admin page.
3. Click the `Upload` link.
4. Select Connections Business Directory Local Time zip file from where you saved the zip file on your computer.
5. Click the `Install Now` button.
6. Lastly click the `Activate Plugin` link to activate the plugin.

Using FTP

1. [Download the plugin zip file](https://wordpress.org/plugins/connections-business-directory-local-time/) and save it to your computer.
2. Extract the Connections Business Directory Local Time zip file.
3. Create a new directory named `connections-local-time` directory in the `../wp-content/plugins/` directory.
4. Upload the files from the folder extracted in Step 2.
4. Activate the plugin on the Plugins admin page.

== Frequently Asked Questions ==

None yet....

== Screenshots ==

[Screenshots can be found here.](https://connections-pro.com/add-on/local-time/)

== Changelog ==

= 1.2.1 05/04/2020 =
* TWEAK: Remove use of `create_function()`.
* DEV: Correct code alignment.
* DEV: Update plugin header.
* DEV: Update README.txt plugin header.

= 1.2 09/14/2020 =
* TWEAK: Check for instance of `cnAddress` and `cnTimezone` before attempting access to their properties.
* OTHER: Update "Tested up to:" to version 5.5.
* OTHER: Bump "Requires at least: 5.0" to version 5.0.

= 1.1 07/21/2020 =
* TWEAK: Add CSS classes and styles for clearing float and hiding time zone.
* BUG: Fragment cache should use the Entry ID, not the User ID.
* BUG: Respect the show time zone setting option value.
* OTHER: Correct readme.txt URL/s.
* OTHER: Correct typos in readme.txt.
* OTHER: Update readme.txt URL/s to HTTPS.
* OTHER: Update copyright year.
* OTHER: Update readme.txt plugin header.
* OTHER: Update plugin header name to be consistent with other addons.

= 1.0 08/24/2017 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
Initial release.

= 1.1 =
It is recommended to backup before updating. Requires WordPress >= 4.8 and PHP >= 5.6.20 PHP version >= 7.2 recommended.

= 1.2 =
It is recommended to backup before updating. Requires WordPress >= 5.0 and PHP >= 5.6.20 PHP version >= 7.2 recommended.

= 1.2.1 =
It is recommended to backup before updating. Requires WordPress >= 5.1 and PHP >= 5.6.20 PHP version >= 7.2 recommended.
