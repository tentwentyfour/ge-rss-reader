=== GE RSS Reader Widgets ===

This plug-in was forked from Aakash Chakravarthy's Super-RSS-Reader after it had not been updated for more than two years.

* Contributors: kwisatz
* Author URI: http://www.1024.lu
* Plugin URI: https://github.com/tentwentyfour/ge-rss-reader
* Original Plugin URI: http://www.aakashweb.com/wordpress-plugins/super-rss-reader/
* Tags: rss, feeds, widget, links, twitter, admin, plugin, feed, posts, page, ticker, thumbnail, atom, jquery
* Requires at least: 2.8
* Tested up to: 4.3.1
* Stable tag: 3.1.1
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

An RSS widget plug-in, which displays feeds like news ticker effect with thumbnails, multiple tabbed feeds, customizable colour styles and more

== Description ==

GE RSS-Reader Widgets is jQuery based RSS reader widget, which displays the RSS feeds in the widget in an attractive way. It uses the jQuery easy ticker plugin to add a news ticker like effect to the RSS feeds. Multiple RSS feeds can be added for a single widget and they get separated in tabs.

The plug-in is fully customizable with external styles and with some inbuilt color styles. It acts as a perfect replacement for the default RSS widget in WordPress.

= New features in v3.1 =

* The plug-in has become more customizable using [pluggable functions](https://codex.wordpress.org/Pluggable_Functions) and several filter hooks.

= New features in v3.0 =

* Added option to set date format
* Added option to determine whether to place meta information above or below the individual items

= Features =

* jQuery [news ticker like effect](http://www.aakashweb.com/jquery-plugins/easy-ticker/) to the RSS feeds (can turn off or on)
* Tabs support, if multiple RSS feeds are added in a single widget.
* **(NEW)** Displays thumbnail to the feed items if available.
* Customizable with Inbuilt color styles and with external CSS styles.
* **(NEW)** Customizable ticker speed.
* Add multiple RSS feeds in a page with a ticker effect.
* Supports RSS or atom feed.
* Can strip title and description text of the feed item.
* Can tweak the widget to change the no of visible feed items and more...
* The jQuery ticker effect is by the [jQuery easy ticker plugin](http://www.aakashweb.com/jquery-plugins/easy-ticker/)


== Available Filters ==

= srr_item_properties =

This filter allows you to filter individual item's properties (author, title, description), before they get passed to the print function.
To see which properties are passed to the filter function, simply var_dump() the contents of $item_properties.
To use it, you could use code similar to this:

```php
add_filter('srr_item_properties', function ($item_properties) {
    $item_properties['title'] = strtolower($item_properties['title']);
    return $item_properties;
}, 10, 1);
```

== Resources ==

* [Documentation](https://github.com/tentwentyfour/ge-rss-reader)
* [FAQs](https://github.com/tentwentyfour/ge-rss-reader/wiki)
* [Support](https://github.com/tentwentyfour/ge-rss-reader/issues)
* [Report Bugs](https://github.com/tentwentyfour/ge-rss-reader/issues)

== Installation ==

Download and upload the latest version of GE RSS-Reader Widgets,

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

No, the additional effect needs only 3.4 Kb of additional file. I think that's not too heavy to slow down the site.

= How to create a tabbed mode or multiple feeds ? =

Just enter the RSS feed URLs separated by comma in the widget, the plug-in automatically renders the tab.

For more FAQs just check out the [official page](https://github.com/tentwentyfour/ge-rss-reader).

== Changelog ==

= 3.1 =

* Pluggable function srr_print
* srr_item_properties filter hook

= 3.0 =
* Refactored codebase
* Date_format and meta_placement options
* New name and new maintainers

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
* Fixed incompatibility of other jQuery plug-ins due to the usage of the latest version of jQuery.

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
* Added a new 'Orange' colour style.

= 2.0 =
* Core code is completely rewritten.
* Flash RSS Reader is removed and instead jQuery is used.
* Administration panel used in the previous version is removed and settings are configured in the widget itself.

= 0.8 =
* Second version with included CSS and Proxy file (loadXML.php).

= 0.5 =
* Initial version with a flash RSS Reader

== Credits ==

* RSS feed reading engine is the inbuilt WordPress's engine
* The news ticker effect is powered by the [jQuery Easy ticker plugin](http://www.aakashweb.com/jquery-plugins/easy-ticker/)
* Default colour styles are by Aakash Chakravarthy.