=== Restrict for Elementor ===
Contributors: restrict, tickera, freemius
Donate link: https://restrict.io/restrict-for-elementor
Tags: elementor, woocommerce, restrict, restricted content, hide content
Requires at least: 4.3
Tested up to: 6.6.2
Stable tag: 1.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show or hide Elementor sections, columns and widgets with ease using many different criteria

== Description ==

The [Restrict for Elementor](https://restrict.io/restrict-for-elementor?utm_source=wordpress.org&utm_medium=plugin-page&utm_campaign=rsc_el "Restricted for Elementor") makes it easy for you to control and protect access to your [Elementor-powered website](https://elementor.com/ "Free WordPress Website Builder").

You can restrict, show and hide widgets, sections and columns to:

* Logged in users and guests
* Users with a specific role (administrator, editor, author, subscriber, etc)
* Users with a specific meta key and value
* Users with a specific [capability](https://codex.wordpress.org/Roles_and_Capabilities "WordPress Roles and Capabilities")
* Author of a post / page
* Visitor's country or continent - integration with [Geolocation IP Detection](https://wordpress.org/plugins/geoip-detect/ "Geolocation IP Detection")

== Premium version integrations and available criteria for content restrictions and other features ==

* Alternative Content - display Elementor template or HTML / formatted text in place of restricted section, column or a widget
* White Label - by adding just one line to your wp-config.php like this <strong>define('RSC_EL_PLUGIN_TITLE', 'My Restriction Plugin');</strong> the whole plugin will become white labeled and ready for your clients.
* [WooCommerce](https://woocommerce.com/) users - who made any purchase
* WooCommerce users - who purchased a specific product
* WooCommerce users - who purchased a specific product variation
* Restrict for Elementor also integrates with [WooCommerce Subscription](https://woocommerce.com/products/woocommerce-subscriptions/) addon which allows you to show / hide content for clients with an active subscription.
* [Easy Digital Downloads](https://easydigitaldownloads.com/) users - who purchased any Easy Digital Downloads product
* Easy Digital Downloads users - who purchased a specific Easy Digital Downloads product
* [Tickera](https://tickera.com/) users - who purchased any ticket
* Tickera users - who purchased a specific ticket type
* Tickera users - who purchased a ticket for a specific event


== Installation ==

1. Install plugin
2. Activate the plugin through the 'Plugins' menu in WordPress
3. To start restricting open any page with the Elementor editor, select desired section, column or widget, click on the Content tab, navigate to the "Restrict" section and choose desired criteria.

== Screenshots ==
1. Dashboard / Free features
2. Dashboard / Premium features

== Changelog ==

= 1.0.9 28/10/2024 =
* Additional REST API security measures.
* Clearing PHP Deprecated function warnings.
* Conflict with "Essential Addons for Elementor" plugin. Issue: Section element restriction not working. [Fixed]
* Updated Freemius to the latest SDK v2.9.0.
* Freemius SDK patched with PR:730. Patched Freemius SDK v2.9.0.1. Reference PR: https://github.com/Freemius/wordpress-sdk/pull/730/files

= 1.0.7 22/05/2024 =
* Restrict Visitor's location: "restrict_for_elementor_match_client_ip_location" filter hook to allow developers to set matching instruction (Client IP or Public IP). Default: Public IP

= 1.0.6 05/06/2023 =
* Restrict setting is not showing on containers if "Flexbox Containers" option is active at Elementor > Settings > Features.
* Updated Freemius SDK

= 1.0.5 17/08/2022 =
* Dependency check for Elementor. Ensures no visible errors if Elementor is not installed and activated.

= 1.0.4 =
* Wordpress 6.0 compatibility

= 1.0.3 =
* Freemius SDK update

= 1.0.2 =
* Added check in the admin for the minimum version of the Elementor plugin
* Added tested up to tags for Elementor

= 1.0.1 =
* Fixed issue with continent selection in the Location addon

= 1.0 =
* First release
