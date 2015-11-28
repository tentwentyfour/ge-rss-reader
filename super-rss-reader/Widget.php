<?php
/**
 * @package  super-rss-reader
 */

/**
 * WordPress Widget
 */
class SuperRSSReaderWidget extends WP_Widget
{

    // Default colour styles
    private $color_styles = array(
        'No style' => 'none',
        'Grey' => 'grey',
        'Dark' => 'dark',
        'Orange' => 'orange',
        'Simple modern' => 'smodern',
    );


    /**
     * Initialize
     */
    public function __construct()
    {
        $control_ops = array(
            'width' => 430,
            'height' => 500
        );
        parent::__construct(
            'super_rss_reader',
            __( 'Super RSS Reader', 'super-rss-reader' ),
            array(
                'classname' => 'widget_super_rss_reader',
                'description' => __('Enhanced RSS feed reader widget with advanced features.', 'super-rss-reader')
            )
        );
    }

    /**
     * Display the Widget
     * @param  [type] $args     [description]
     * @param  [type] $instance [description]
     * @return [type]           [description]
     */
    public function widget($args, $instance)
    {
        extract($args);
        if (empty($instance['title'])) {
            $title = '';
        } else {
            $title = $before_title . apply_filters('widget_title', $instance['title'], $instance, $this->id_base) . $after_title;
        }

        echo $before_widget . $title;
        echo "\n" . '
        <!-- Start - Super RSS Reader v' . SRR_VERSION . '-->
        <div class="super-rss-reader-widget">' . "\n";
        srr_rss_parser($instance);
        echo "\n" . '</div>
        <!-- End - Super RSS Reader -->
        ' . "\n";
        echo $after_widget;
    }

    /**
     * Save settings
     * @param  [type] $new_instance [description]
     * @param  [type] $old_instance [description]
     * @return [type]               [description]
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = stripslashes($new_instance['title']);
        $instance['urls'] = stripslashes($new_instance['urls']);
        $instance['tab_titles'] = stripslashes($new_instance['tab_titles']);

        $instance['count'] = intval($new_instance['count']);
        $instance['show_title'] = intval($new_instance['show_title']);
        $instance['show_date'] = intval($new_instance['show_date']);
        $instance['meta_placement'] = intval($new_instance['meta_placement']);
        $instance['show_desc'] = intval($new_instance['show_desc']);
        $instance['show_author'] = intval($new_instance['show_author']);
        $instance['show_thumb'] = stripslashes($new_instance['show_thumb']);
        $instance['open_newtab'] = intval($new_instance['open_newtab']);
        $instance['strip_desc'] = intval($new_instance['strip_desc']);
        $instance['strip_title'] = intval($new_instance['strip_title']);
        $instance['read_more'] = stripslashes($new_instance['read_more']);
        $instance['rich_desc'] = stripslashes($new_instance['rich_desc']);

        $instance['date_format'] = stripslashes($new_instance['date_format']);
        $instance['color_style'] = stripslashes($new_instance['color_style']);
        $instance['enable_ticker'] = intval($new_instance['enable_ticker']);
        $instance['visible_items'] = intval($new_instance['visible_items']);
        $instance['ticker_speed'] = intval($new_instance['ticker_speed']);

        return $instance;
    }

    /**
     * Widget form
     * @param  [type] $instance [description]
     * @return [type]           [description]
     */
    public function form($instance)
    {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => '', 'urls' => '', 'tab_titles' => '',
                'count' => 5, 'show_date' => 0, 'show_title' => 1, 'show_desc' => 1,
                'meta_placement' => 1,
                'show_author' => 0, 'show_thumb' => 1, 'open_newtab' => 1,
                'strip_desc' => 100, 'read_more' => '[...]', 'rich_desc' => 0 ,
                'color_style' => 'none', 'enable_ticker' => 1, 'visible_items' => 5,
                'strip_title' => 0, 'ticker_speed' => 4, 'date_format' => 'j F Y',
            )
        );

        $title = htmlspecialchars($instance['title']);
        $urls = htmlspecialchars($instance['urls']);
        $tab_titles = htmlspecialchars($instance['tab_titles']);
        $count = intval($instance['count']);

        $show_title = intval($instance['show_title']);
        $show_date = intval($instance['show_date']);
        $show_desc = intval($instance['show_desc']);
        $show_author = intval($instance['show_author']);
        $meta_placement = intval($instance['meta_placement']);
        $show_thumb = intval($instance['show_thumb']);
        $open_newtab = intval($instance['open_newtab']);
        $strip_desc = intval($instance['strip_desc']);
        $strip_title = intval($instance['strip_title']);
        $read_more = htmlspecialchars($instance['read_more']);
        $rich_desc = htmlspecialchars($instance['rich_desc']);

        $date_format = stripslashes($instance['date_format']);
        $color_style = stripslashes($instance['color_style']);
        $enable_ticker = intval($instance['enable_ticker']);
        $visible_items = intval($instance['visible_items']);
        $ticker_speed = intval($instance['ticker_speed']);

        ?>
        <div class="srr_settings">
        <table width="100%" height="72" border="0">
        <tr>
          <td width="13%" height="33"><label for="<?php echo $this->get_field_id('title'); ?>">Title: </label></td>
          <td width="87%"><input id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat"/></td>
        </tr>
        <tr>
            <td><label for="<?php echo $this->get_field_id('urls'); ?>">URLs: </label></td>
            <td><input id="<?php echo $this->get_field_id('urls');?>" name="<?php echo $this->get_field_name('urls'); ?>" type="text" value="<?php echo $urls; ?>" class="widefat"/>
            <small class="srr_smalltext">Can enter multiple RSS/atom feed URLs separated by a comma.</small>
            </td>
        </tr>

        <tr>
            <td><label for="<?php echo $this->get_field_id('tab_titles'); ?>">Tab titles: </label></td>
            <td><input id="<?php echo $this->get_field_id('tab_titles');?>" name="<?php echo $this->get_field_name('tab_titles'); ?>" type="text" value="<?php echo $tab_titles; ?>" class="widefat"/>
            <small class="srr_smalltext">Enter corresponding tab titles separated by a comma.</small>
            </td>
        </tr>

        </table>
        </div>

        <div class="srr_settings">
        <h4>Settings</h4>
        <table width="100%" border="0">
          <tr>
            <td width="7%" height="28"><input id="<?php echo $this->get_field_id('show_desc'); ?>" type="checkbox"  name="<?php echo $this->get_field_name('show_desc'); ?>" value="1" <?php echo $show_desc == "1" ? 'checked="checked"' : ""; ?> /></td>
            <td width="40%"><label for="<?php echo $this->get_field_id('show_desc'); ?>"><?php _e('Show Description', 'super-rss-reader'); ?></label></td>
            <td width="28%"><label for="<?php echo $this->get_field_id('strip_desc');?>">Strip description</label></td>
            <td width="25%"><input id="<?php echo $this->get_field_id('strip_desc');?>" name="<?php echo $this->get_field_name('strip_desc'); ?>" type="text" value="<?php echo $strip_desc; ?>" class="widefat" title="The number of characters to be displayed. Use 0 to disable stripping"/>          </td>
            </tr>
            <tr>
                <td height="29"><input id="<?php echo $this->get_field_id('show_date'); ?>" type="checkbox"  name="<?php echo $this->get_field_name('show_date'); ?>" value="1" <?php echo $show_date == "1" ? 'checked="checked"' : ""; ?> /></td>
                <td><label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Show Title', 'super-rss-reader'); ?></label></td>
                <td><label for="<?php echo $this->get_field_id('strip_title'); ?>">Strip Title</label></td>
                <td><input id="<?php echo $this->get_field_id('strip_title');?>" name="<?php echo $this->get_field_name('strip_title'); ?>" type="text" value="<?php echo $strip_title; ?>" class="widefat" title="The number of characters to be displayed. Use 0 to disable stripping"/></td>
            </tr>
          <tr>
            <td height="29"><input id="<?php echo $this->get_field_id('show_date'); ?>" type="checkbox"  name="<?php echo $this->get_field_name('show_date'); ?>" value="1" <?php echo $show_date == "1" ? 'checked="checked"' : ""; ?> /></td>
            <td><label for="<?php echo $this->get_field_id('show_date'); ?>">Show Date</label></td>
            <td><label for="<?php echo $this->get_field_id('date_format'); ?>"><?php _e('Date format', 'super-rss-reader'); ?></label></td>
            <td><input id="<?php echo $this->get_field_id('date_format');?>" name="<?php echo $this->get_field_name('date_format'); ?>" type="text" value="<?php echo $date_format; ?>" class="widefat" title="Date format. See PHP's date() function for valid format."/></td>
          </tr>
          <tr>
            <td height="29"><input id="<?php echo $this->get_field_id('show_author'); ?>" type="checkbox"  name="<?php echo $this->get_field_name('show_author'); ?>" value="1" <?php echo $show_author == "1" ? 'checked="checked"' : ""; ?> /></td>
            <td><label for="<?php echo $this->get_field_id('show_author'); ?>">Show Author</label></td>
            <td><label for="<?php echo $this->get_field_id('read_more'); ?>">Read more text</label></td>
            <td><input id="<?php echo $this->get_field_id('read_more'); ?>" name="<?php echo $this->get_field_name('read_more'); ?>" type="text" value="<?php echo $read_more; ?>" class="widefat" title="Leave blank to hide read more text"/></td>
          </tr>
          <tr>
            <td height="29"><input id="<?php echo $this->get_field_id('open_newtab'); ?>" type="checkbox"  name="<?php echo $this->get_field_name('open_newtab'); ?>" value="1" <?php echo $open_newtab == "1" ? 'checked="checked"' : ""; ?> /></td>
            <td><label for="<?php echo $this->get_field_id('open_newtab'); ?>">Open links in new tab</label></td>
            <td><label for="<?php echo $this->get_field_id('count');?>">Count</label></td>
            <td><input id="<?php echo $this->get_field_id('count');?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" class="widefat" title="No of feed items to parse"/></td>

          </tr>
          <tr>
            <td height="29"><input id="<?php echo $this->get_field_id('show_thumb'); ?>" type="checkbox"  name="<?php echo $this->get_field_name('show_thumb'); ?>" value="1" <?php echo $show_thumb == "1" ? 'checked="checked"' : ""; ?> /></td>
            <td><label for="<?php echo $this->get_field_id('show_thumb'); ?>">Show thumbnail if present</label></td>
            <td><label for="<?= $this->get_field_id('meta_placement'); ?>"><?php _e('Where to place meta info (author, date)', 'super-rss-reader');?></label></td>
            <td>
                <select name="<?= $this->get_field_name('meta_placement');?>" id="<?= $this->get_field_id('meta_placement');?>">
                    <option value="0"><?php _e('above', 'super-rss-reader'); ?></option>
                    <option value="1"><?php _e('below', 'super-rss-reader'); ?></option>
                </select>
            </td>
          </tr>

          <tr>
            <td height="32"><input id="<?php echo $this->get_field_id('rich_desc'); ?>" type="checkbox"  name="<?php echo $this->get_field_name('rich_desc'); ?>" value="1" <?php echo $rich_desc == "1" ? 'checked="checked"' : ""; ?> /></td>
            <td><label for="<?php echo $this->get_field_id('rich_desc'); ?>">Enable full or rich description</label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

        </table>
        </div>

        <?php if ($rich_desc == 1): ?>
        <span class="srr_note">
        <?php
            _e('Note: You have enabled "Full/Rich HTML" Please make sure that the feed(s) are from trusted sources and do not contain any harmful scripts.', 'super-rss-reader');
        ?> <?php
            _e('If there are some alignment issues in the description, please use custom CSS to fix that.', 'super-rss-reader');
        ?>
        </span>
        <?php endif; ?>

        <div class="srr_settings">
        <h4>Other settings</h4>
        <table width="100%" height="109" border="0">
          <tr>
            <td height="32"><label>Colour style: </label></td>
            <td>
            <?php
            echo '<select name="' . $this->get_field_name('color_style') . '" id="' . $this->get_field_id('color_style') . '">';
            foreach ($this->color_styles as $key => $value) {
                echo '<option value="' . $value . '" ' . ($color_style == $value ? 'selected="selected"' : "") .  '>' . $key . '</option>';
            }
            echo '</select>';
            ?>
            </td>
          </tr>
          <tr>
            <td height="33"><label for="<?php echo $this->get_field_id('enable_ticker'); ?>">Ticker animation:</label> </td>
            <td><input id="<?php echo $this->get_field_id('enable_ticker'); ?>" type="checkbox"  name="<?php echo $this->get_field_name('enable_ticker'); ?>" value="1" <?php echo $enable_ticker == "1" ? 'checked="checked"' : ""; ?> /></td>
          </tr>
          <tr>
            <td height="36"><label for="<?php echo $this->get_field_id('visible_items');?>">Visible items: </label></td>
            <td><input id="<?php echo $this->get_field_id('visible_items');?>" name="<?php echo $this->get_field_name('visible_items'); ?>" type="text" value="<?php echo $visible_items; ?>" class="widefat" title="The no of feed items to be visible."/>
            </td>
          </tr>
          <tr>
            <td height="36"><label for="<?php echo $this->get_field_id('ticker_speed');?>">Ticker speed: </label></td>
            <td><input id="<?php echo $this->get_field_id('ticker_speed');?>" name="<?php echo $this->get_field_name('ticker_speed'); ?>" type="text" value="<?php echo $ticker_speed; ?>" title="Speed of the ticker in seconds"/> seconds
            </td>
          </tr>
        </table>
        </div>
        <?php
    }
}
