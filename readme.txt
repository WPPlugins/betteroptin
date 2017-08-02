=== BetterOptin ===
Contributors: themeavenue,julien731,siamkreative
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KADEESTQ9H3GW
Tags: popup,lightbox,modal,opt-in,lead generation,e-mail,newsletter,marketing,pop-up,optin,subscription,mailchimp,mailpoet,aweber,exit-intent
Requires at least: 3.5.1
Tested up to: 4.0
Stable tag: 1.2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

BetterOptin is the easiest solution to create beautiful and efficient opt-in popups for WordPress.

== Description ==

https://www.youtube.com/watch?v=iQvJHSVhNUc

= Stunning Opt-In Popups for WordPress =

Ok. Popups usually convert better. But how annoying are they? Do you know how much you piss your visitors off? Actually yes, you do. How much does it annoy YOU when a site throws a popup at your face as soon as you land on it?

You know what? We're just like you. We don't like being harassed when we browse the internet. That's why we developed a new WordPress popup plugin: BetterOptin, a better way to use popups. And it converts even better!

= Why is BetterOptin Better? =

Not only because of its name obviously ;) Let me explain how it works and you'll understand why it's better right away...

**The Problem**

BetterOptin is based on a simple observation: people don't like popups thrown at their face for no apparent reason.
mailch
Seriously, let's say you land on a site after a Google search. Maybe you'll like the site. Maybe you'll enjoy reading the content and you'll become a subscriber. But what happens with traditional popups is you're asked to subscribe / like / download this / do that before even reading a sentence!

Some popup systems tried to change that by adding a delay before triggering the popup. But really? Will you be happier if a popup shows up in the middle of your reading? I don't think so.

With BetterOptin, you stay away from all these bad impressions given to visitors and you keep your brand image unharmed.

**Our Solution**

With BetterOptin, you only ask people to complete an action (be it subscribing to your newsletter, downloading your e-book or else) at an appropriate moment. And this particular moment is when the visitor is about to leave without having completed the action first. This is called exit-intent.

BetterOptin is capable of detecting when the visitors leaves your site and will trigger the popup just then.

= Integrations =

By default, BetterOptin will save your leads as users in the WordPress database. Of course those users are "tagged" so that you can easily find them afterwards.

However, if you prefer using a e-mail marketing provider, we provide premium addons for the following services:

* [MailChimp](http://betteropt.in/downloads/mailchimp/)
* [Aweber](http://betteropt.in/downloads/aweber/)
* [MailPoet](http://betteropt.in/downloads/mailpoet/)

All the premium addons are available on the plugin's official site at [http://betteropt.in](http://betteropt.in).

= How Do I Create Popups? =

Creating a popup can take as little as a minute, but it can also take a lot more time if you want to fine tune it.

The creation process is as follows:

1. Choose a template
2. Customize the settings
3. Choose where to display the popup
4. Customize the popup

= Technical Mumbo-Jumbo =

Because every once in a while somebody asks for technical specifities, here is a little technical stuff…

= Templating System =

You know HTML? You'll love what you're about to hear...

One of the great advantages of BetterOptin is that we built it with a simple templating system for popups. The plugin comes with 8 templates, but you can create your own. It only requires HTML. Just code the template as you would do for any HTML page, and add our "template tags" in data attributes. This will tell the customizer what he can or cannot customize.

= Custom Table & Statistics API =

In order to provide valuable insights without slowing down your site, BetterOptin uses a custom database table to save all the "hits".

As working with SQL queries is not safe if done the wrong way, we developed an API within the plugin to let you easily and safely interact with the custom table. No need to write any SQL queries, our helper functions will do it for you. Of course the API uses the WordPress `$wpdb` object when working with the database.

= Titan Framework =

BetterOptin relies on Titan Framework to handle plugin options. This framework is built by expert WordPress developers and well maintained on GitHub.

= Addons Compatibility =

The beauty of BetterOptin (and a painful part for the developers ;)) is that it's built around a flexible core, allowing for new features to be added as extensions of the plugin. This means that we will develop new features in the future, and, if you have the skills, you can do it too.

= Roadmap =

Wanna know what's coming next in BetterOptin? [Check out the roadmap](https://trello.com/b/mWrd0wUg). You can also vote for the ideas you like best.

== Installation ==

1. Upload `better-optin` to the `/wp-content/plugins/` directory or upload `betteroptin.zip` through your WordPress admin
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create your first popup in the `Popups` menu

== Frequently Asked Questions ==

For support please visit the [GitHub repo](https://github.com/ThemeAvenue/BetterOptin).

= How to Find the Lead's E-Mails In WordPress? =

If you are using WordPress to collect your leads, you will want to see your list (to export it for instance).

Finding the leads generated by BetterOptin is very easy. First, make sure you know the role used by the plugin. By default it is `BetterOptin`, but it might have been changed in the settings ([see the documentation](http://support.themeavenue.net/plugins/betteroptin/getting-started/#Default_Role)).

Once you made sure about the role used, go into your WordPress admin panel and go to _Users_.  Right above the table, you will see a filter labeled _“Change role to...”_. Select the appropriate role and click the _“Change”_ button.

![Changing User Role](http://support.themeavenue.net/wp-content/uploads/2014/09/roles.png)

You will now only see the leads you collected with BetterOptin.

== Screenshots ==

1. Creating new popups is easy as a breeze
2. The Analytics page gives you an overview of your popups conversion
3. The list of opt-in popups
4. General Settings
5. The popup visual editor

== Changelog ==

= 1.2.3 =
* Fix colorpicker not loading on popup customizer screen
* Add link to the support in documentation page
* Few small UX improvements

= 1.2.2 =
* Fix issue preventing from deactivating the plugin
* Improve code style and performance
* Include more stuff in the uninstall process
* Add optional credit
* Preload images
* Add today's conversion rate in admin bar
* Edit post type updated messages
* Remove user notification after subscription when using the WordPress integration
* Add today's conversion rate helper function

= 1.2.1 =
* Add direct link to leads for the WordPress integration

= 1.2.0 =
* Release of the plugin's core for free

= 1.0.1 =
* Add popup trigger shortcode
* Add the wiggle option
* Switch MailChimp API wrapper to Drewm
* Add a helper to dismiss a popup
* Stop using anonymous functions incompatible with old PHP versions

= 1.0.0 =
* First stable release

== Upgrade Notice ==

= 1.2.3 =
THis version fixes the issue with colorpicket not loading in the customizer page.

= 1.2.2 =
The errors preventing from deactivating the plugin are now fixed and a couple of neat details have been added to the plugin features.

= 1.2.1 =
It's now even easier to retrieve the leads collected by BetterOptin from the WordPress database.

= 1.2.0 =
Version 1.0.1 is no more officially supported. Only version 1.2.0 and above will be supported by the team.

= 1.0.1 =
This version add a few new features that are not essential, but it also fixes a couple of bugs.