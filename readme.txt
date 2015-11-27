=== Super RSS Reader ===
Contributors: Aakash Chakravarthy
Author URI: http://www.aakashweb.com/
Plugin URI: http://www.aakashweb.com/wordpress-plugins/super-rss-reader/
Tags: rss, feeds, widget, links, twitter, admin, plugin, feed, posts, page, ticker, thumbnail, atom, jquery
License: GPLv2 or later
Donate Link: http://bit.ly/srrDonation
Requires at least: 2.8
Tested up to: 3.8.1
Stable tag: 2.5

An awesome RSS widget plugin, which displays feeds like news ticker effect with thumbnails, multiple tabbed feeds, customizable color styles and more

== Description ==

Super RSS Reader is jQuery based RSS reader widget, which displays the RSS feeds in the widget in an attractive way. It uses the jQuery easy ticker plugin to add a news ticker like effect to the RSS feeds. Multiple RSS feeds can be added for a single widget and they get seperated in tabs.

The plugin is fully customizable with external styles and with some inbuilt color styles. It acts as a perfect replacement for the default RSS widget in WordPress.

[Check out the **LIVE DEMO** of the plugin](http://www.aakashweb.com/demos/super-rss-reader/)

= New features in v2.5 =

* Added option to change the title for individual tabs.
* Support for full or rich description.
* Revised feed ordering.

= Features =

* jQuery [news ticker like effect](http://www.aakashweb.com/jquery-plugins/easy-ticker/) to the RSS feeds (can turn off or on)
* Tabs support, if multiple RSS feeds are added in a single widget.
* **(NEW)** Displays thumbnail to the feed items if available.
* Customizable with Inbuilt color styles and with external CSS styles.
* **(NEW)** Customizable ticker speed.
* Add multiple RSS feeds in a page with a ticker effect.
* Supports RSS or atom feed.
* Can strip title and description text of the feed item.
* Can tweak the widget to change the no of visible feed itemas and more...

[youtube=http://www.youtube.com/watch?v=02aOG_-98Tg]

* If you like this plugin, then [just make a small donation](http://bit.ly/srrdonate) and it will be helpful for the plugin development.
* The jQuery ticker effect is by the [jQuery easy ticker plugin](http://www.aakashweb.com/jquery-plugins/easy-ticker/)

= Resources =

* [Donate](http://bit.ly/srrDonation)
* [Documentation](http://www.aakashweb.com/wordpress-plugins/super-rss-reader/)
* [FAQs](http://www.aakashweb.com/faqs/wordpress-plugins/super-rss-reader/)
* [Support](http://www.aakashweb.com/forum/)
* [Report Bugs](http://www.aakashweb.com/forum/)

== Installation ==

Download and upload the latest version of Super RSS Reader,

1. Unzip & upload it to your WordPress site.
1. Activate the plugin.
1. Drag and drop the "Super RSS Reader" widget in the "Widgets" page.
1. Input a RSS feed URL to the widget, tweak some settings and you are,
1. Done !

== Frequently Asked Questions ==

= How can I customize the RSS widget externally ? =

You can use the `super-rss-reader-widget` class in your stylesheet to control the widget styling. Other classes are,

1. `srr-tab-wrap` - the tab's class.
1. `srr-wrap` - the wrapper of the widget.
1. `srr-item.odd` - to control the odd feed items.
1. `srr-item.even` - to control the even feed items.

= Will the additional ticker effect slows the site ? =

No. the additional effect needs only 3.4 Kb of additional file. I think thats not too heavy to slow down the site.

= How to create a tabbed mode or multiple feeds ? =

Just enter the RSS feed URLs seperated by comma in the widget, the plugin automatically renders the tab.

For more FAQs just check out the [official page](http://www.aakashweb.com/wordpress-plugins/super-rss-reader/).

== Screenshots ==

1. Example Super RSS Reader widgets shown in the sidebar, having a ticker effect and a tabbed mode.
1. Plugin working in different themes and RSS feeds.
1. Picture showing some possible ways of Customizing the widget.
1. The Super RSS Reader widget in the administration page.

[Live Working demo](http://www.aakashweb.com/demos/super-rss-reader/)

[More screenshots in Aakash Web](http://www.aakashweb.com/wordpress-plugins/super-rss-reader/)

== Changelog ==

= 2.5 =
* Added feature to change individual tab titles/names.
* Added feature to enable rich or full description.
* Fixed feed ordering issues.
* Updated jQuery easy ticker plugin to v2.0.
* Minor code revisions.

= 2.4 =
* Added feature to cut down/strip feed titles.
* Added a new 'Simple modern' color style.

= 2.3 =
* Fixed imcompatibility of other jQuery plugins due to the usage of the latest version of jQuery.

= 2.2 =
* Displays "thumbnail" of the feed item if available.
* Added setting to change ticker speed.
* Added setting to edit the "Read more" text.
* Default styles are revised.
* Switched to full size ticker code.
* Core code revised.

= 2.1 =
* Added option to open links in new window.
* Changed the method to include the scripts and styles.
* Added a new 'Orange' color style.

= 2.0 =
* Core code is completely rewritten.
* Flash RSS Reader is removed and instead jQuery is used.
* Administration panel used in the previous version is removed and settings are configured in the widget itself.

= 0.8 =
* Second version with included CSS and Proxy file (loadXML.php).

= 0.5 =
* Initial version with a flash RSS Reader

== Upgrade Notice ==

Version 2.0 is a major and recommended upgrade for previous version users.

== Credits ==

* RSS feed reading engine is the inbuilt WordPress's engine
* The news ticker effect is powered by the [jQuery Easy ticker plugin](http://www.aakashweb.com/jquery-plugins/easy-ticker/)
* Default color styles are by Aakash Chakravarthy.