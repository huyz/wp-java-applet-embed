<?php
/*
Plugin Name: Java Applet Embed
Plugin URI: http://huyz.us/wp-java-applet-embed/
Version: 0.5.1.1
Author: Huy Z, Anthony Mattox
Description: Embeds Java applets into posts or pages. | <a href="options-general.php?page=java-applet-embed.php">Settings</a>
Author URI: http://huyz.us/, http://www.anthonymattox.com
License: GPLv2


Copyright (C) 2011  Huy Z (http://huyz.us)
Copyright (C) 2010  Anthony Mattox  (email : ahmattox@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if (!class_exists("JavaAppletEmbed")) {
    class JavaAppletEmbed {
        var $optionName = 'jembed_options';
        
        function JavaAppletEmbed() {
        }
        
        // add script tag to header to load javascript
        function addHeaderScripts() {
            $html = "\r\n<!-- java applet embed scripts -->\r\n";
            //$html .= '<script src="'.get_bloginfo('url').'/wp-content/plugins/java-applet-embed/data/jae_deployJava.js"></script>';
            $html .= '<script src="'.get_bloginfo('url').'/wp-content/plugins/java-applet-embed/data/jae_deployJava-min.js"></script>';
            $html .= "\r\n\r\n";
            echo($html);
        }
        
        // create options when plugin is activated
        function initialize() {
            $this->getOptions();
        }
        
        // Returns an array of options
        public function getOptions() {
            $jembed_options = array(
                'width' => '450',
                'height' => '300',
                'method' => 'inline');
            $jembed_saved_options = get_option($this->optionName);
            foreach ($jembed_saved_options as $key => $option) {
                $jembed_options[$key] = $option;
            }
            update_option($this->optionName, $jembed_options);
            return $jembed_options;
        }
        
        function createAdminPanel() {
            $options = $this->getOptions();
            // update options if they have been set
            if (isset($_POST['update_settings'])) {
                if(isset($_POST['width'])) {
                    // $options['width'] = $_POST['width'];
                    $options['width'] = preg_replace('/[a-zA-Z ;,]+/', '', $_POST['width']);
                }
                if(isset($_POST['height'])) {
                    // $options['height'] = $_POST['height'];
                    $options['height'] = preg_replace('/[a-zA-Z ;,]+/', '', $_POST['height']);
                }
                if(isset($_POST['method'])) {
                    $options['method'] = $_POST['method'];
                }
                
                update_option($this->optionName, $options);
                echo('<div class="updated"><p><strong>');
                _e("Settings Updated.", "applet_embed");
                echo('</strong></p></div>');
            }
            
            /*
              *  Wordpress Admin Panel
              *
              */ ?>
            <div class="wrap">
                <form method="post" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" >
                    <h2>Java Applet Embed</h2>
                    <h3>Load Method</h3>
                    <input type="radio" name="method" value="inline" <?php if($options['method']=='inline') {echo('checked="checked"' );} ?>/> <label>Inline: <small>Load immediately within the page.</small></label><br />
                    <input type="radio" name="method" value="onclick" <?php if($options['method']=='onclick') {echo('checked="checked"' );} ?>/> <label>On Click: <small>Load in page when alt content is clicked.</small></label><br />
                    <input type="radio" name="method" value="popup" <?php if($options['method']=='popup') {echo('checked="checked"' );} ?>/> <label>Popup: <small>Load in a popup window when alt content is clicked.</small></label><br />
                    <br />
                    <h3>Default Size <small>(in pixels)</small></h3>
                    <label for="width" style="float:left;width:5em;padding:3px 0 0;">Width:</label>
                    <input type="text" name="width" id="width" value="<?php echo($options['width']); ?>" />
                    <br />
                    <label for="height" style="float:left;width:5em;padding:3px 0 0;">Height:</label>
                    <input type="text" name="height" id="height" value="<?php echo($options['height']); ?>" />
                    <br />
                    <div class="submit">
                        <input type="submit" name="update_settings" value="<?php _e('Update Settings', 'applet_embed'); ?> &raquo;" />
                    </div>
                </form>
                <br />
                <div class="wrap">
<h3>Usage Instructions</h3>
<p>
First, you need to make your applets' Java code accessible.  Either, make the
JAR archive(s) available from an URL not managed by WordPress (even on some
external web site), or upload the file using the WordPress Media Library, as
you would any other image, video, or audio file.
</p><p>
Then, for each Java applet, simply add the shortcode to the content of the appropriate
post or page.  Multiple applets are allowed.
</p><p>
The most basic format is:<br />
<code>[applet code="your.applet.ClassName" file="http://domain.com/full/path/to/yourJar.jar"]</code><br />
This shortcode is in non-enclosing form.
</p><p>
You can override global default settings by specifying 'width', 'height',
or 'method' attributes in the shortcode, where the method can be 'inline',
'onclick', or 'popup'.
</p><p>
By using the enclosing form of the applet shortcode, you can specify "alternate
content" and, optionally, applet parameters.  The enclosing format is:<code><br />
[applet code="your.applet.ClassName" file="http://domain.com/full/path/to/yourJar.jar"]<br />
[param name="paramName" value="paramValue"] <br />
alternate content<br />
[/applet]</code><br />
</p><p>
Alternate content is used differently depending on the loading method of the
applet: for the 'popup' and 'onclick' methods, alternate content will be
wrapped in a link and displayed for the user to click; for the 'inline'
method, it will only be displayed if Java is not installed.
</p>
<h4>Examples</h4>
<p>Example:<br />
<code>[applet code="us.huyz.SlickApplet" file="http://huyz.us/wp-content/uploads/2011/06/slickApplets.jar" method="popup"]Popup the applet &rarr;[/applet]</code><p>
<p>Example (Note '<code>files/</code>' for multisite):<br />
<code>&lt;div style="text-align:center;"&gt;<br />
[applet code="de.mud.jta.Applet" file="http://huyz.us/files/2011/06/jta26.jar" width="600" height="400" method="onclick"]<br />
[param name="config" value="http://huyz.us/non-wp-content/applet.conf"]<br />
[param name="Terminal.size" value="[112,24]"]<br />
&amp;uarr;<br />
&amp;larr; Load the applet &amp;rarr;<br />
&amp;darr;<br />
[/applet]<br />
&lt;/div&gt;</code></p>
<h4>Details about shortcodes</h4>
<p>
As with all WordPress shortcodes, you cannot
<a href="http://codex.wordpress.org/Shortcode_API#Unclosed_Shortcodes">mix shortcodes</a>
in enclosing form and non-enclosing (a.k.a. "unclosed") form.
</p><p>
Either do<br />
<code>[applet...]<br />
[applet...]<br />
[applet...]</code><br />
or<br />
<code>[applet...]...[/applet]<br />
[applet...]...[/applet]<br />
[applet...]...[/applet]</code><br />
but don't mix and match within a single post or page.
</p><p>
Unlike with true WordPress shortcodes, there is no restriction about
<a href="http://codex.wordpress.org/Shortcode_API#Square_Brackets">square 
brackets</a> within attributes of <code>[param]</code> shortcodes.  Thus, this is allowed:<br />
<code>[param name="Terminal.size" value="[112,24]"]</code>
</p>

                </div>
                <br />
                <p><i><b>Java Applet Embed</b> created by <a href="http://huyz.us/">Huy Z</a>, based on <b>Wordpress Processing Embed</b> created by <a href="http://www.anthonymattox.com">Anthony Mattox</a>.</i></p>
            </div><?php
        }
    }
}

if (class_exists("JavaAppletEmbed")) {
    $jembed = new JavaAppletEmbed();
}

//Initialize the admin panel
if (!function_exists("jembed_ap")) {
    function jembed_ap() {
        global $jembed;
        if (!isset($jembed)) {
            return;
        }
        if (function_exists('add_options_page')) {
            add_options_page('Java Applet Embed', 'Java Applet Embed', 9, basename(__FILE__), array(&$jembed, 'createAdminPanel'));
        }
    } 
}
        
function embed_java_applet($attributes, $altcontent=null) {
    global $jembed_id;
    $html = "";
    if (!isset($attributes['code']) || $attributes['code']=='') {
        // if no code is specified display error message and alternate content
        $html.='<p><strong>No Code Specified</strong></p>';
        $html.='<p>'.$altcontent.'</p>';
    } elseif (!isset($attributes['file']) || $attributes['file']=='') {
        // if no file is selected display error message and alternate content
        $html.='<p><strong>No File Selected</strong></p>';
        $html.='<p>'.$altcontent.'</p>';
    } else {
        // otherwise insert embed code
        $options = get_option('jembed_options');
        // merge the passed attributes into the default settings
        $settings = array_merge($options, $attributes);
        $settings['code'] = preg_replace('/.class$/', '', $settings['code']);
        $settings['name'] = preg_replace('/\W/', '', $settings['code']) . (++$jembed_id);
        $settings['pluginurl'] = get_bloginfo('url')."/wp-content/plugins/java-applet-embed";

        // Get rid of smart quotes in altcontent; otherwise, we won't be able to match the quotes
        $char_codes = array( '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8242;', '&#8243;' );
        $replacements = array( "'", "'", '"', '"', "'", '"' );
        $altcontent = str_replace( $char_codes, $replacements, $altcontent );
        // extract parameters from altcontent, if any
        $pattern = '/\s*\[PARAM(?:\s+NAME\s*=\s*("([^\n]*?)"|\'([^\n]*?)\'|([^"\'\]\s]+))|\s+VALUE\s*=\s*("([^\n]*?)"|\'([^\n]*?)\'|([^"\'\]\s]+)))+\s*\](?:\s*<br *\/?>\s*)?/is';
        $matches = array();
        preg_match_all($pattern, $altcontent, $matches, PREG_SET_ORDER);
        $altcontent = preg_replace($pattern, '', $altcontent);
        $parameters_html = "";
        foreach ($matches as $match) {
            if (!empty($match[2])) $name = $match[2];
            elseif (!empty($match[3])) $name = $match[3];
            elseif (!empty($match[4])) $name = $match[4];
            else continue;
            if (!empty($match[6])) $value = $match[6];
            elseif (!empty($match[7])) $value = $match[7];
            elseif (!empty($match[8])) $value = $match[8];
            else continue;
            $parameters[html_entity_decode($name, ENT_QUOTES)] = html_entity_decode($value, ENT_QUOTES);
            # Matches 1 & 5 already have the proper quoting from the user
            $parameters_html .= "<param name={$match[1]} value={$match[5]}>\n";
        }
        $parameters_json = json_encode($parameters);

        /* // Output settings for testing
        $html .= "<ul>";
        foreach ($settings as $key => $value) {
            $html .= "<li>{$key}: {$value}</li>";
        }
        $html .= "</ul>";*/
        
        // generate the embed code
        $html .= "<div class=\"applet_embed\" id=\"{$settings['name']}_container\">";
        
        if ($settings['method']=='inline') {
            /*
              *   Inline
              */
            $html .= "<script type=\"text/javascript\">
                /* <![CDATA[ */
                var attributes = { 
                    code: '{$settings['code']}.class',
                    archive: '{$settings['file']}',
                    width: {$settings['width']}, 
                    height: {$settings['height']} /*,
                    image: '{$settings['pluginurl']}/data/loading.gif' */
                    };
                var parameters = $parameters_json;
                var version = null; //'1.5';
                jae_deployJava.runApplet(attributes, parameters, version);
                /* ]]> */
            </script>
            <noscript><div>
                <!--[if !IE]> -->
                <object classid=\"java:{$settings['code']}.class\" 
                    type=\"application/x-java-applet\"
                    archive=\"{$settings['file']}\"
                    width=\"{$settings['width']}\" height=\"{$settings['height']}\"
                    standby=\"Loading Java applet...\" >
                <param name=\"archive\" value=\"{$settings['file']}\" />
                $parameters_html

                <param name=\"mayscript\" value=\"true\" />
                <param name=\"scriptable\" value=\"true\" />
                
                <!--<param name=\"image\" value=\"{$settings['pluginurl']}/data/loading.gif\" />-->
                <param name=\"boxmessage\" value=\"Loading Java applet...\" />
                <param name=\"boxbgcolor\" value=\"#FFFFFF\" />
                
                <param name=\"test_string\" value=\"outer\" />
                <!--<![endif]-->
                
                <object classid=\"clsid:8AD9C840-044E-11D1-B3E9-00805F499D93\"
                    codebase=\"http://java.sun.com/update/1.6.0/jinstall-6u20-windows-i586.cab\"
                    width=\"{$settings['width']}\" height=\"{$settings['height']}\"
                    standby=\"Loading Java applet...\"  >
                    
                    <param name=\"code\" value=\"{$settings['code']}\" />
                    <param name=\"archive\" value=\"{$settings['file']}\" />
                    $parameters_html
                    
                    <param name=\"mayscript\" value=\"true\" />
                    <param name=\"scriptable\" value=\"true\" />
                    
                    <!--<param name=\"image\" value=\"{$settings['pluginurl']}/data/loading.gif\" />-->
                    <param name=\"boxmessage\" value=\"Loading Java applet...\" />
                    <param name=\"boxbgcolor\" value=\"#FFFFFF\" />
                    
                    <param name=\"test_string\" value=\"inner\" />
                    
                    <p>
" . ( !is_null($altcontent) ? $altcontent : "" ) . "
                        <strong>This browser does not have a Java Plug-in.
                        <br />
                        <a href=\"http://www.java.com/getjava\" title=\"Download Java Plug-in\">Get the latest Java Plug-in here.</a>
                        </strong>
                    </p>
                
                </object>
                <!--[if !IE]> -->
                </object>
                <!--<![endif]-->
            </div></noscript>";
        } elseif ($settings['method'] == 'onclick') {
            /*
              *   On Click
              */
            $html .= "<p><a href=\"#\" onclick=\"jae_deployJava.addAppletTo('{$settings['code']}', '{$settings['file']}', {$settings['width']}, {$settings['height']}, " . htmlspecialchars($parameters_json) . ", '{$settings['pluginurl']}', '{$settings['name']}_container'); return false;\">";
            if (!is_null($altcontent)) {
                $html .= $altcontent;
            } else {
                $html .= 'Load Java applet';
            }
            $html .= '</a></p>';
        } elseif ($settings['method'] == 'popup') {
            /*
              *   Popup
              */
            // Apostrophes (and backslashes) need to be escaped for JS, not HTML attribute value
            if (!is_null($altcontent)) {
                $altcontent_esc = preg_replace('/([\'\\\\]|\n)/', '\\\\$1', $altcontent);
                $altcontent_esc = htmlspecialchars($altcontent_esc);
            } else {
                $altcontent_esc = "";
            }
            // NOTE: passing the JSON as a string will make it easier to encodeURIcomponent in JS;
            // hence the single quotes, unlike for "On Click".
            $parameters_json_esc = preg_replace('/([\'\\\\]|\n)/', '\\\\$1', $parameters_json);
            $parameters_json_esc = htmlspecialchars($parameters_json_esc);
            $html .= "<p><a href=\"#\" onclick=\"jae_deployJava.openAppletInNewWindow('{$settings['name']}', '{$settings['code']}', '{$settings['file']}', {$settings['width']}, {$settings['height']}, '$parameters_json_esc', '{$settings['pluginurl']}', '$altcontent_esc'); return false;\">";
            if (!is_null($altcontent)) {
                $html .= $altcontent;
            } else {
                $html .= 'Pop-up Java applet';
            }
            $html .= '</a></p>';
        }
        $html .= "</div>";

    }
    return($html);
}

function jembed_addJarMime($mimes) {
    $mimes['jar'] = 'application/java-archive';
    return $mimes;
}

// Actions
if (isset($jembed)) {
    register_activation_hook( __FILE__, array(&$jembed, 'initialize'));
    add_action('admin_menu', 'jembed_ap');
    add_filter('upload_mimes', 'jembed_addJarMime');
    add_action('wp_head', array(&$jembed, 'addHeaderScripts'), 1);
    
    add_shortcode('applet', 'embed_java_applet');
}

// vim:set ai et sts=4 sw=4 tw=0 fileencoding=utf-8
?>
