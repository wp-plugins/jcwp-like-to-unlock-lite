<?php
/*
  Plugin Name: jcwp like to unlock lite
  Plugin URI: http://jaspreetchahal.org/wordpress-like-to-unlock-content-plugin/
  Description: This plugin gives you control to initially hide part of your article from user. Content is displayed correctly once user Facebook Like or +1 your page <a href="http://jaspreetchahal.org/wordpress-like-to-unlock-content-plugin/" style='color:#C00'><strong >UPGRADE TO PRO</strong></a>
  Author: Jaspreet Chahal
  Version: 1.1
  Author URI: http://jaspreetchahal.org
  License: GPLv2 or later
  */

/*
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

// if not an admin just block access
$jcorgltu_version = '1.1';
if (preg_match('/admin\.php/', $_SERVER['REQUEST_URI']) && is_admin() == false) {
    return false;
}
require_once 'JCLTUMobile_Detect.php';
$detectjcucn = new JCLTUMobile_Detect();
register_activation_hook(__FILE__, 'jcorgltu_activate');
function jcorgltu_activate()
{
    add_option('jcorgltu_active', '1');
    add_option('jcorgltu_facebook_app_id', '');
    add_option('jcorgltu_show_faces', 'no');
    add_option('jcorgltu_width', "100");
    add_option('jcorgltu_font', "lucida grande");
    add_option('jcorgltu_colorscheme', 'light');
    add_option('jcorgltu_hide', 'hide');
    add_option('jcorgltu_text_to_display', 'To continue reading please like this article');

}

add_action("admin_menu", "jcorgltu_menu");
function jcorgltu_menu()
{
    add_options_page('JCWP Like to Unlock', 'JCWP Like to Unlock', 'manage_options', 'jcorgStpLtU-plugin', 'jcorgltu_plugin_options');
}

add_action('admin_init', 'jcorgltu_regsettings');
function jcorgltu_regsettings()
{
    global $jcorgltu_version;
    register_setting("jcorgStpLtU-setting", "jcorgltu_active");
    register_setting("jcorgStpLtU-setting", "jcorgltu_facebook_app_id");
    register_setting("jcorgStpLtU-setting", "jcorgltu_show_faces");
    register_setting("jcorgStpLtU-setting", "jcorgltu_width");
    register_setting("jcorgStpLtU-setting", "jcorgltu_font");
    register_setting("jcorgStpLtU-setting", "jcorgltu_colorscheme");
    register_setting("jcorgStpLtU-setting", "jcorgltu_text_to_display");
    register_setting("jcorgStpLtU-setting", "jcorgltu_hide");
    wp_enqueue_script('jquery');
    wp_enqueue_script('jqueryui');
}


add_action('wp_head', 'jcorgltu_init');
function jcorgltu_init()
{
    global $detectjcucn;
    if ((get_option("jcorgltu_disableon_mobile") == "Yes" && $detectjcucn->isMobile()) || (get_option("jcorgltu_disableon_tablet") == "Yes" && $detectjcucn->isTablet())) {
        return;
    }
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jcorgltu_script_fb', plugins_url("application.js", __FILE__), array('jquery', 'jquery-ui-core', 'jquery-effects-core'), '1.1');
    ?>
    <script type="text/javascript">
    function jcorg_ltugpcb(resp) {

    <?php
    if(get_option("jcorgltu_hide") == 'hide') {
        ?>
        if(resp.state == "on") {
            jQuery(".jcwp-like-to-unlock").hide();
            jQuery("#jcwp-like-to-unlock-wrap").hide();
        }
        else {
            jQuery(".jcwp-like-to-unlock").show();
            jQuery("#jcwp-like-to-unlock-wrap").show();
        }
              <?php
    }
    ?>
    }
    </script>
    <?php
}

add_action('wp_footer', 'jcorgltu_inclscript', 20);
function jcorgltu_inclscript()
{
    global $detectjcucn;
    //if((get_option("jcorgltu_disableon_mobile") == "Yes" && $detectjcucn->isMobile()) || (get_option("jcorgltu_disableon_tablet") == "Yes" && $detectjcucn->isTablet())) {
    //    return;
    // }
    if (get_option('jcorgltu_active') == "1") {
        ?>
        <script>
            jQuery(document).ready(function () {
                $jcorgltuel = jQuery(".jcwp-like-to-unlock");
                var jcorgltu_cnv = 'jcorgltu_cnv';
                <?php
                if(get_option("jcorgltu_hide") == 'hide') {
                   echo '$jcorgltuel .hide();';
                }

                ?>

                jQuery("<div id='jcwp-like-to-unlock-wrap' style='background: #f9f9f9;border:5px #eeeeee solid;border-radius: 15px;padding:15px;margin-bottom:25px;height:92px;'><div style='text-align: center;font-size: 18px; border-bottom:3px solid #eee;margin-bottom:8px;box-sizing:border-box;padding-bottom:10px;color:#C00'><?php echo get_option("jcorgltu_text_to_display")?></div><div id='jcwp-like-to-unlock-fb' style='float:left;width:100%;text-align:center'></div><div style='clear:both'></div></div>").insertBefore($jcorgltuel);
                jQuery("#jcwp-like-to-unlock-fb").jcFacebookLike({
                    href: location.href,
                    applicationId: "<?php echo strlen(trim(get_option("jcorgltu_facebook_app_id")))>0?trim(get_option("jcorgltu_facebook_app_id")):''?>",
                    show_faces:<?php echo (trim(get_option("jcorgltu_show_faces")) == 'yes')?'true':'false'?>,
                    layout: "button_count",
                    width: "<?php echo strlen(trim(get_option("jcorgltu_width")))>0?trim(get_option("jcorgltu_width")):'300'?>",
                    font: "<?php echo strlen(trim(get_option("jcorgltu_font")))>0?trim(get_option("jcorgltu_font")):'arial'?>",
                    colorscheme: "<?php echo strlen(trim(get_option("jcorgltu_colorscheme")))>0?trim(get_option("jcorgltu_colorscheme")):'light'?>",
                    callbackLike: function (response) {
                        <?php
                            if(get_option("jcorgltu_hide") == 'hide') {
                               echo '$jcorgltuel.show();
                                    jQuery("#jcwp-like-to-unlock-wrap").hide();';
                            }
                        ?>
                    },
                    callbackUnLike: function (response) {
                        <?php
                            if(get_option("jcorgltu_hide") == 'hide') {
                               echo '$jcorgltuel.hide();
                                     jQuery("#jcwp-like-to-unlock-wrap").show();';
                            }

                        ?>
                    }
                });

            });
        </script>

    <?php

    }
}

function jcorgltu_custom_tag($content)
{
    global $wp_current_filter;
    $start_tag = '[ltu]';
    $end_tag = '[/ltu]';
    if(get_option(base64_decode("amNvcmdsdHVfZGVmYXVsdF9rZXk=")) == "")
    {
        $content = str_ireplace($start_tag, "", $content);
        return str_ireplace($end_tag, "", $content);
    }
    if (strpos($content,$start_tag) !== FALSE && get_option('jcorgltu_active') == "1" /*&& (array_search('get_the_excerpt', $wp_current_filter) === false) && (array_search('the_excerpt', $wp_current_filter) === false)*/) {
        $text = substr($content, stripos($content, $start_tag) + strlen($start_tag), stripos($content, $end_tag) + strlen($end_tag));
        $replacement = "<div class='jcwp-like-to-unlock'><p>" . $text . "</p></div>";
        $content =  substr_replace($content, $replacement, stripos($content, $start_tag), stripos($content, $end_tag) + strlen($end_tag));
        $content = str_ireplace($start_tag, "", $content);
        return str_ireplace($end_tag, "", $content);
    } else {
        $content = str_ireplace($start_tag, "", $content);
        return str_ireplace($end_tag, "", $content);
    }

}

add_filter('the_content', 'jcorgltu_custom_tag', 99);

function jcorgltu_plugin_options()
{
    ?>
    <style type="text/css">
        .jcorgbsuccess, .jcorgberror {
            border: 1px solid #ccc;
            margin: 0px;
            padding: 15px 10px 15px 50px;
            font-size: 12px;
        }

        .jcorgbsuccess {
            color: #FFF;
            background: green;
            border: 1px solid #FEE7D8;
        }

        .jcorgberror {
            color: #B70000;
            border: 1px solid #FEE7D8;
        }

        .jcorgb-errors-title {
            font-size: 12px;
            color: black;
            font-weight: bold;
        }

        .jcorgb-errors {
            border: #FFD7C4 1px solid;
            padding: 5px;
            background: #FFF1EA;
        }

        .jcorgb-errors ul {
            list-style: none;
            color: black;
            font-size: 12px;
            margin-left: 10px;
        }

        .jcorgb-errors ul li {
            list-style: circle;
            line-height: 150%; /*background: url(/images/icons/star_red.png) no-repeat left;*/
            font-size: 11px;
            margin-left: 10px;
            margin-top: 5px;
            font-weight: normal;
            padding-left: 15px
        }

        td {
            font-weight: normal;
        }
    </style><br>
    <div class="wrap" style="float: left;">
    <?php

    screen_icon('tools');?>
    <h2>Like to Unlock Settings</h2>
    <?php
    $errors = get_settings_errors("", true);
    $errmsgs = array();
    $msgs = "";
    if (count($errors) > 0)
        foreach ($errors as $error) {
            if ($error["type"] == "error")
                $errmsgs[] = $error["message"];
            //  else if($error["type"] == "updated")
            //      $msgs = $error["message"];
        }

    echo jcorgStpLtUMakeErrorsHtml($errmsgs, 'warning1');
    if (strlen($msgs) > 0) {
        echo "<div class='jcorgbsuccess' style='width:90%'>$msgs</div>";
    }
    ?><br><br>

    <form action="options.php" method="post" id="jcorg_settings_form">
        <?php settings_fields("jcorgStpLtU-setting"); ?>
        <h2>Facebook Like button settings</h2>
        <table class="widefat" style="width: 700px;" cellpadding="7">
            <tr valign="top">
                <th scope="row">Enabled</th>
                <td><input type="radio"
                           name="jcorgltu_active" <?php if (get_option('jcorgltu_active') == "1" || get_option('jcorgltu_active') == "") echo "checked='checked'"; ?>
                           value="1"
                        /> Yes
                    <input type="radio"
                           name="jcorgltu_active" <?php if (get_option('jcorgltu_active') == "0") echo "checked='checked'"; ?>
                           value="0"
                        /> No
                </td>
            </tr>
            <tr valign="top">
                <th width="25%" scope="row">Facebook App Id</th>
                <td><input type="number" name="jcorgltu_facebook_app_id"
                           value="<?php echo get_option('jcorgltu_facebook_app_id'); ?>" style="padding:5px"
                           size="40"/> (If you don't have one then create <a style="position: relative;top:-7px" href="https://developers.facebook.com/apps" target="_blank"> it here</a> )
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Show Faces</th>
                <td><input type="radio"
                           name="jcorgltu_show_faces" <?php if (get_option('jcorgltu_show_faces') == "yes") echo "checked='checked'"; ?>
                           value="yes"
                        /> Yes
                    <input type="radio"
                           name="jcorgltu_show_faces" <?php if (get_option('jcorgltu_show_faces') == "no" || get_option('jcorgltu_show_faces') == "") echo "checked='checked'"; ?>
                           value="no"
                        /> No
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Facebook Like placeholder width</th>
                <td><input type="number" name="jcorgltu_width"
                           value="<?php echo get_option('jcorgltu_width'); ?>" style="padding:5px" size="40"/>px
                    (number only)
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Facebook Like button font</th>
                <td><select name="jcorgltu_font">
                        <option value="lucida grande" <?php if (get_option('jcorgltu_font') == "lucida grande") {
                            _e('selected');
                        } ?> >lucida grande
                        </option>
                        <option value="arial" <?php if (get_option('jcorgltu_font') == "arial") {
                            _e('selected');
                        } ?> >arial
                        </option>
                        <option value="segoe ui" <?php if (get_option('jcorgltu_font') == "segoe ui") {
                            _e('selected');
                        } ?> >segoe ui
                        </option>
                        <option value="tahoma" <?php if (get_option('jcorgltu_font') == "tahoma") {
                            _e('selected');
                        } ?> >tahoma
                        </option>
                        <option value="trebuchet ms" <?php if (get_option('jcorgltu_font') == "trebuchet ms") {
                            _e('selected');
                        } ?> >trebuchet ms
                        </option>
                        <option value="verdana" <?php if (get_option('jcorgltu_font') == "verdana") {
                            _e('selected');
                        } ?> >verdana
                        </option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Color scheme</th>
                <td><select name="jcorgltu_colorscheme">
                        <option value="light" <?php if (get_option('jcorgltu_colorscheme') == "light") {
                            _e('selected');
                        } ?> >arial
                        </option>
                        <option value="dark" <?php if (get_option('jcorgltu_colorscheme') == "dark") {
                            _e('selected');
                        } ?> >tahoma
                        </option>
                    </select></td>
            </tr>
        </table>
        <br>

        <h2>Google plus button settings</h2>
        <a href="http://jaspreetchahal.org/wordpress-like-to-unlock-content-plugin/" target="_blank" style='color:#C00'><strong >UPGRADE TO PRO</strong></a>
        <br>

        <h2>Other Settings</h2>
        <table class="widefat" style="width: 700px;" cellpadding="7">
            <tr valign="top">
                <th scope="row">Action Text</th>
                <td><input type="text" name="jcorgltu_text_to_display"
                           value="<?php echo get_option('jcorgltu_text_to_display'); ?>" style="padding:5px"
                           size="40"/></td>
            </tr>
            <tr valign="top">
                <th scope="row">How would you like to hide text</th>
                <td>
                    <input type="radio"
                           name="jcorgltu_hide" <?php if (get_option('jcorgltu_hide') == "hide" || get_option('jcorgltu_hide') == "") echo "checked='checked'"; ?>
                           value="hide"
                        /> Hide
                    <input type="radio"
                           name="jcorgltu_hide" <?php if (get_option('jcorgltu_hide') == "blur") echo "checked='checked'"; ?>
                           value="blur" disabled="disabled"
                        /> Blur (<a href="http://jaspreetchahal.org/wordpress-like-to-unlock-content-plugin/" target="_blank" style='color:#C00'><strong >UPGRADE TO PRO</strong></a>)
                    <input type="radio"
                           name="jcorgltu_hide" <?php if (get_option('jcorgltu_hide') == "dim") echo "checked='checked'"; ?>
                           value="dim"
                           disabled="disabled"
                        /> Dim (<a href="http://jaspreetchahal.org/wordpress-like-to-unlock-content-plugin/" target="_blank" style='color:#C00'><strong >UPGRADE TO PRO</strong></a>)

                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" class="button-primary"
                   value="Save Changes"/>
        </p>
    </form>
    </div>
    <?php
    echo "<div style='float:left;margin-left:20px;margin-top:75px'>" . jcorgStpLtUfeeds() . "</div>";
}


function jcorgStpLtUfeeds()
{
    $list = "
        <table style='width:400px;' class='widefat'>
        <tr>
            <th>
            Latest posts from JaspreetChahal.org
            </th>
        </tr>
        ";
    $max = 5;
    $feeds = fetch_feed("http://feeds.feedburner.com/jaspreetchahal/mtDg");
    $cfeeds = $feeds->get_item_quantity($max);
    $feed_items = $feeds->get_items(0, $cfeeds);
    if ($cfeeds > 0) {
        foreach ($feed_items as $feed) {
            if (--$max >= 0) {
                $list .= " <tr><td><a href='" . $feed->get_permalink() . "'>" . $feed->get_title() . "</a> </td></tr>";
            }
        }
    }
    return $list . "<tr><td><a href='http://jaspreetchahal.org/wordpress-like-to-unlock-content-plugin/' target='_blank' style='color:#C00;text-align:center;font-size:24px;display:inline-block'><br><br><br>UPGRADE TO PRO<br><br><br><img src='".plugin_dir_url(__FILE__)."/LTU.png'></a></td></tr></table>";
}


function jcorgStpLtUMakeErrorsHtml($errors, $type = "error")
{
    $class = "jcorgberror";
    $title = __("Please correct the following errors", "jcorgbot");
    if ($type == "warnings") {
        $class = "jcorgberror";
        $title = __("Please review the following Warnings", "jcorgbot");
    }
    if ($type == "warning1") {
        $class = "jcorgbwarning";
        $title = __("Please review the following Warnings", "jcorgbot");
    }
    $strCompiledHtmlList = "";
    if (is_array($errors) && count($errors) > 0) {
        $strCompiledHtmlList .= "<div class='$class' style='width:90% !important'>
                                        <div class='jcorgb-errors-title'>$title: </div><ol>";
        foreach ($errors as $error) {
            $strCompiledHtmlList .= "<li>" . $error . "</li>";
        }
        $strCompiledHtmlList .= "</ol></div>";
        return $strCompiledHtmlList;
    }
}

