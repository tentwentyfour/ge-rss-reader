<?php
/*
Plugin Name: Super RSS Reader2
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
define('SRR_VERSION', '3.0');

/**
 * Include the required scripts and stylesheets
 * @return void
 */
function srr_enqueue_assets() {
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

/**
 * The main RSS Parser
 *
 * This is the code that displays the contents of a widget instance.
 * We don't implement any caching ourselves, since WordPress' fetch_feed() takes
 * care of that for us. See https://codex.wordpress.org/Function_Reference/fetch_feed
 *
 * It is called from within SuperRSSReaderWidget::widget()
 *
 * @param  Array $instance Widget configuration
 *
 * @return void
 */
function srr_rss_parser($instance)
{
    $urls = stripslashes($instance['urls']);
    $tab_titles = stripslashes($instance['tab_titles']);
    $count = intval($instance['count']);

    $color_style = stripslashes($instance['color_style']);
    $enable_ticker = intval($instance['enable_ticker']);
    $visible_items = intval($instance['visible_items']);
    $ticker_speed = intval($instance['ticker_speed']) * 1000;

    if (empty($urls)) {
        return '';
    }

    $rand = array();
    $url = explode(',', $urls);
    $tab_title = explode(',', $tab_titles);
    $ucount = count($url);

    // Generate the Tabs
    if ($ucount > 1) {
        printf(
            '<ul class="srr-tab-wrap srr-tab-style-%s srr-clearfix">',
            $color_style
        );

        for ($i = 0; $i < $ucount; $i++) {
            // Get the Feed URL
            $feedUrl = trim($url[$i]);
            $rss = fetch_feed($feedUrl);
            $rand[$i] = rand(0, 999);

            if (!is_wp_error($rss)) {
                if (isset($tab_title[$i]) && !empty($tab_title[$i])) {
                    $rss_title = $tab_title[$i];
                } else {
                    $rss_title = esc_attr(strip_tags($rss->get_title()));
                }
                sprintf(
                    '<li data-tab="srr-tab-%d">%s</li>',
                    $rand[$i],
                    $rss_title
                );
            } else {
                sprintf(
                    '<li data-tab="srr-tab-%d">Error</li>',
                    $rand[$i]
                );
            }

        }
        print('</ul>');
    }

    for ($i = 0; $i < $ucount; $i++) {
        // Get the Feed URL
        $feedUrl = trim($url[$i]);
        if (isset($url[$i])) {
            $rss = fetch_feed($feedUrl);
        } else {
            return '';
        }

        if (method_exists($rss, 'enable_order_by_date')) {
            $rss->enable_order_by_date(false);
        }

        // Check for feed errors
        if (!is_wp_error($rss)) {
            $maxitems = $rss->get_item_quantity($count);
            $rss_items = $rss->get_items(0, $maxitems);
            $rss_title = esc_attr(strip_tags($rss->get_title()));
            $rss_desc = esc_attr(strip_tags($rss->get_description()));
        } else {
            printf(
                '<div class="srr-wrap srr-style-%s" data-id="srr-tab-%d"><p>RSS Error: %s</p></div>',
                $color_style,
                $rand[$i],
                $rss->get_error_message()
            );
            continue;
        }

        $randAttr = isset($rand[$i]) ? 'data-id="srr-tab-' . $rand[$i] . '"' : '';

        // Outer Wrap start
        printf(
            '<div class="srr-wrap %s srr-style-%s" data-visible="%s" data-speed="%s" %s>
                <div>',
            ($enable_ticker == 1 ) ? 'srr-vticker' : '' ,
            $color_style,
            $visible_items,
            $ticker_speed,
            $randAttr
        );

        // Check feed items
        if ($maxitems == 0) {
            printf(
                '<div>%s</div>',
                __('No items.', 'super-rss-reader')
            );
        } else {
            $j = 1;
            // Loop through each feed item
            foreach ($rss_items as $item) {
                srr_display_item($instance, $item, $j++);
            }
        }

        // Outer wrap end
        echo "\n\n</div>
        </div><!-- .srr-wrap --> \n\n" ;

        $rss->__destruct();
        unset($rss);
    }
}

function srr_display_item($instance, $item, $j)
{
    $show_title = intval($instance['show_title']);
    $show_date = intval($instance['show_date']);
    $show_desc = intval($instance['show_desc']);
    $show_author = intval($instance['show_author']);
    $meta_placement = intval($instance['meta_placement']);
    $show_thumb = stripslashes($instance['show_thumb']);
    $open_newtab = intval($instance['open_newtab']);
    $strip_desc = intval($instance['strip_desc']);
    $strip_title = intval($instance['strip_title']);
    $read_more = htmlspecialchars($instance['read_more']);
    $rich_desc = intval($instance['rich_desc']);
    $date_format = stripslashes($instance['date_format']);

    // Get the link
    $link = $item->get_link();
    while (stristr($link, 'http') != $link) {
        $link = substr($link, 1);
    }
    $link = esc_url(strip_tags($link));

    // Get the item title
    $title = esc_attr(strip_tags($item->get_title()));
    if (empty($title)) {
        $title = __('No Title', 'super-rss-reader');
    }

    if ($strip_title != 0 ) {
        $titleLen = strlen($title);
        $title = wp_html_excerpt( $title, $strip_title );
        $title = ($titleLen > $strip_title) ? $title . ' ...' : $title;
    }

    // Open links in new tab
    $newtab = ($open_newtab) ? 'target="_blank"' : '';

    // Get the date
    $date = $item->get_date($date_format);
    $time = sprintf(
        '<time class="srr-date">%s</time>',
        $date
    );

    // Get thumbnail if present @since v2.2
    $thumb = '';
    if ($show_thumb == 1 && $enclosure = $item->get_enclosure()) {
        $thumburl = $enclosure->get_thumbnail();
        if (!empty($thumburl)) {
            $thumb = sprintf(
                '<img src="%s" alt="%s" class="srr-thumb" align="left"/>',
                $thumburl,
                $title
            );
        }
    }

    // Get the description
    $desc = '';
    if ($rich_desc == 1) {
        $desc = strip_tags(
            str_replace(
                'eval',
                '',
                $item->get_description()
            ),
            '<p><a><img><em><strong><font><strike><s><u><i>'
        );
    } else {
        $desc = str_replace(
            array("\n", "\r"),
            ' ',
            esc_attr(
                strip_tags(
                    @html_entity_decode(
                        $item->get_description(),
                        ENT_QUOTES,
                        get_option('blog_charset')
                    )
                )
            )
        );
        $rmore = '';
        if ($strip_desc != 0) {
            $desc = wp_html_excerpt($desc, $strip_desc);
            $rmore = (!empty($read_more)) ?  '<a href="' . $link . '" title="Read more"' . $newtab . '>' . $read_more . '</a>' : '';

            if ('[...]' == substr($desc, -5 )) {
                $desc = substr($desc, 0, -5);
            } elseif ('[&hellip;]' != substr($desc, -10)) {
                $desc .= '';
            }
            $desc = esc_html($desc);
        }
        $desc = $thumb . $desc . ' ' . $rmore;
    }

    // Get the author
    $author = $item->get_author();
    if (is_object($author)) {
        $author = $author->get_name();
        $author = esc_html(strip_tags($author));
        $cite = sprintf(
            '<cite class="srr-author">%s</cite>',
            $author
        );
    }

    echo "\n\n\t";

    // Display the feed items
    echo '<div class="srr-item ' . (($j%2 == 0) ? 'even' : 'odd') . '">';

    if ($meta_placement == 0) {
        echo '<div class="srr-meta">';
        if ($show_date && !empty($date)) {
            print($time);
        }
        if ($show_author && !empty($author)) {
            echo '&nbsp;&ndash;&nbsp;' . $cite;
        }
        echo '</div><!-- .srr-meta -->';
    }


    printf(
        '<div class="srr-title">
            <a href="%s" %s title="%s">%s</a>
        </div>',
        $link,
        $newtab,
        sprintf(
            __('Posted on %s', 'super-rss-reader'),
            $date
        ),
        $title
    );

    if ($meta_placement == 1) {
        echo '<div class="srr-meta">';
        if ($show_date && !empty($date)) {
            print($time);
        }
        if ($show_author && !empty($author)) {
            echo '&nbsp;&ndash;&nbsp;' . $cite;
        }
        echo '</div><!-- .srr-meta -->';
    }

    if ($show_desc) {
        echo '<p class="srr-summary srr-clearfix">' . $desc . '</p>';
    }

    echo '</div><!-- .srr-item -->';
    // End display
}