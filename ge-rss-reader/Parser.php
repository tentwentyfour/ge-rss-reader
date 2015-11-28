<?php
/**
 * @package: super-rss-reader-2
 */

class Parser
{

    private $config;
    private $ticker;
    private $items;

    public function create($instance)
    {
        $this->config = array(
            'urls' => stripslashes($instance['urls']),
            'tab_titles' => stripslashes($instance['tab_titles']),
            'count' => intval($instance['count']),
            'color_style' => stripslashes($instance['color_style'])
        );

        $this->ticker = array(
            'enabled' => intval($instance['enable_ticker']),
            'visible_items' => intval($instance['visible_items']),
            'speed' => intval($instance['ticker_speed']) * 1000,
        );

        $this->items = array(
            'show_title' => intval($instance['show_title']),
            'show_date' => intval($instance['show_date']),
            'show_desc' => intval($instance['show_desc']),
            'show_author' => intval($instance['show_author']),
            'meta_placement' => intval($instance['meta_placement']),
            'show_thumb' => stripslashes($instance['show_thumb']),
            'open_newtab' => intval($instance['open_newtab']),
            'strip_desc' => intval($instance['strip_desc']),
            'strip_title' => intval($instance['strip_title']),
            'read_more' => htmlspecialchars($instance['read_more']),
            'rich_desc' => intval($instance['rich_desc']),
            'date_format' => stripslashes($instance['date_format'])
        );
    }

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
    public function parse()
    {
        if (empty($this->config['urls'])) {
            return '';
        }

        $rand = array();
        $url = explode(',', $this->config['urls']);
        $tab_title = explode(',', $this->config['tab_titles']);
        $ucount = count($url);

        // Generate the Tabs
        if ($ucount > 1) {
            printf(
                '<ul class="srr-tab-wrap srr-tab-style-%s srr-clearfix">',
                $this->config['color_style']
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
                $maxitems = $rss->get_item_quantity($this->config['count']);
                $rss_items = $rss->get_items(0, $maxitems);
                $rss_title = esc_attr(strip_tags($rss->get_title()));
                $rss_desc = esc_attr(strip_tags($rss->get_description()));
            } else {
                printf(
                    '<div class="srr-wrap srr-style-%s" data-id="srr-tab-%d"><p>RSS Error: %s</p></div>',
                    $this->config['color_style'],
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
                ($this->ticker['enabled'] == 1) ? 'srr-vticker' : '' ,
                $this->config['color_style'],
                $this->ticker['visible_items'],
                $this->ticker['speed'],
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
                    $this->displayItem($item, $j++);
                }
            }

            // Outer wrap end
            echo "\n\n</div>
            </div><!-- .srr-wrap --> \n\n" ;

            $rss->__destruct();
            unset($rss);
        }
    }

    /**
     * Render and display a single feed item
     *
     * @param  Object $item One item from the RSS feed
     * @param  integer $j   Counter to determine even/odd
     * @return void
     */
    private function displayItem($item, $j)
    {
        $item_properties = array(
            'index' => $j,
            'meta_placement' => $this->items['meta_placement'],
            'show_date' => $this->items['show_date']
        );

        // Get the link
        $link = $item->get_link();
        while (stristr($link, 'http') != $link) {
            $link = substr($link, 1);
        }
        $item_properties['link'] = esc_url(strip_tags($link));

        // Get the item title
        $title = esc_attr(strip_tags($item->get_title()));
        if (empty($title)) {
            $title = __('No Title', 'super-rss-reader');
        }

        if ($this->items['strip_title'] != 0) {
            $titleLen = strlen($title);
            $title = wp_html_excerpt($title, $this->items['strip_title']);
            $title = ($titleLen > $this->items['strip_title']) ? $title . ' ...' : $title;
        }
        $item_properties['title'] = $title;

        // Open links in new tab
        $item_properties['newtab'] = ($this->items['open_newtab']) ? 'target="_blank"' : '';

        if ($this->items['show_date']) {
            $date = $item->get_date($this->items['date_format']);
            $item_properties['date'] = $date;
            $item_properties['time'] = sprintf(
                '<time class="srr-date">%s</time>',
                $date
            );
        }

        // Get thumbnail if present @since v2.2
        $thumb = '';
        if ($this->items['show_thumb'] == 1 && $enclosure = $item->get_enclosure()) {
            $thumburl = $enclosure->get_thumbnail();
            if (!empty($thumburl)) {
                $thumb = sprintf(
                    '<img src="%s" alt="%s" class="srr-thumb" align="left"/>',
                    $thumburl,
                    $title
                );
            }
        }
        $item_properties['thumb'] = $thumb;

        // Get the description
        if ($this->items['show_desc']) {
            if ($this->items['rich_desc'] == 1) {
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
                if ($this->items['strip_desc'] != 0) {
                    $desc = wp_html_excerpt($desc, $this->items['strip_desc']);
                    if (!empty($this->items['read_more'])) {
                        $rmore = sprintf(
                            '<a href="%s" title="%s" %s>%s</a>',
                            $link,
                            __('Read more', 'super-rss-reader'),
                            $newtab,
                            $this->items['read_more']
                        );
                    }

                    if ('[...]' == substr($desc, -5 )) {
                        $desc = substr($desc, 0, -5);
                    } elseif ('[&hellip;]' != substr($desc, -10)) {
                        $desc .= '';
                    }
                    $desc = esc_html($desc);
                }
                $desc = $thumb . $desc . ' ' . $rmore;
            }
            $item_properties['desc'] = $desc;
        }

        // Get the author
        if ($this->items['show_author']) {
            $author = $item->get_author();
            if (is_object($author)) {
                $author = $author->get_name();
                $author = esc_html(strip_tags($author));
                $cite = sprintf(
                    '<cite class="srr-author">%s</cite>',
                    $author
                );
            }
            $item_properties['cite'] = $cite;
        }

        // srr_print is pluggable!
        $item_properties = apply_filters('srr_item_properties', $item_properties);
        srr_print($item_properties);
        // End display
    }
}