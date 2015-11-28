<?php
/*
Plugin Name: Super RSS Reader-2
Plugin URI:  http://github.com/tentwentyfour/super-rss-reader2
Description: Super RSS Reader-2 is a jQuery based RSS reader widget, which displays the RSS feeds in the widget in an attractive way. It uses the jQuery easy ticker plugin to add a news ticker like effect to the RSS feeds. Multiple RSS feeds can be added for a single widget and they get separated in tabs.
Version:     3.0
Author:      Aakash Chakravarthy, David Raison
License:     GPL2

Super RSS Reader is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Super RSS Reader is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Super RSS Reader. If not, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html.
*/

//Author URI: http://www.aakashweb.com/, http://foo.bar hmm.. one cannot specify more than one author URL?
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    die('Access denied.');
}

require_once('Widget.php');
require_once('Parser.php');

define('SRR_VERSION', '3.0');

/**
 * Include the required scripts and stylesheets
 * @return void
 */
function srr_enqueue_assets()
{
    wp_register_script('jquery-easy-ticker', plugins_url('/assets/js/jquery.easy-ticker.min.js', __FILE__ ), array('jquery'), '20151127', true);
    wp_enqueue_script('super-rss-reader', plugins_url('/assets/js/srr-js.js', __FILE__), array('jquery-easy-ticker'), '20151127', true);
    wp_enqueue_style('srr-css.css', plugins_url('/assets/css/srr-css.css', __FILE__), false, '20151127', false);
}
add_action('wp_enqueue_scripts', 'srr_enqueue_assets');

/**
 * Include additional stylesheets if we're on the admin/widdgets page
 * @return void
 */
function srr_admin_enqueue_scripts($hook_suffix)
{
    if (in_array($GLOBALS['pagenow'], array('widgets.php'))) {
        wp_enqueue_style('srr-settings', plugins_url('/assets/css/srr-settings.min.css', __FILE__), false, '20151127', false);
    }
}
add_action('admin_enqueue_scripts', 'srr_admin_enqueue_scripts');


/**
 * Initialize widget
 * @return [type] [description]
 */
function super_rss_reader_init()
{
    register_widget('SuperRSSReaderWidget');
}
add_action('widgets_init', 'super_rss_reader_init');

/**
 * Load textdomain
 */
add_action('plugins_loaded', function () {
    load_plugin_textdomain(
        'super-rss-reader',
        false,
        basename(dirname( __FILE__ )) . '/languages'
    );
});

if (!function_exists('srr_print')) :

    /**
     * The srr_print function is pluggable, you may replace it with you own, if you wish
     * @param  Array $item_properties Properties of individual items after 'srr_item_properties' filters have been applied
     * @return void
     */
    function srr_print($item_properties)
    {
        echo "\n\n\t";

        // Display the feed items
        echo '<div class="srr-item ' . (($item_properties['index'] % 2 === 0) ? 'even' : 'odd') . '">';

        if ($item_properties['meta_placement'] == 0) {
            echo '<div class="srr-meta">';
            if (isset($item_properties['time'])) {
                print($item_properties['time']);
            }
            if (isset($item_properties['cite'])) {
                echo '&nbsp;&ndash;&nbsp;' . $item_properties['cite'];
            }
            echo '</div><!-- .srr-meta -->';
        }

        printf(
            '<div class="srr-title">
                <a href="%s" %s title="%s">%s</a>
            </div>',
            $item_properties['link'],
            $item_properties['newtab'],
            sprintf(
                __('Posted on %s', 'super-rss-reader'),
                $item_properties['date']
            ),
            $item_properties['title']
        );

        if ($item_properties['meta_placement'] == 1) {
            echo '<div class="srr-meta">';
            if (isset($item_properties['time'])) {
                print($item_properties['time']);
            }
            if (isset($item_properties['cite'])) {
                echo '&nbsp;&ndash;&nbsp;' . $item_properties['cite'];
            }
            echo '</div><!-- .srr-meta -->';
        }

        if (isset($item_properties['desc'])) {
            echo '<p class="srr-summary srr-clearfix">' . $item_properties['desc'] . '</p>';
        }

        echo '</div><!-- .srr-item -->';
    }

endif;