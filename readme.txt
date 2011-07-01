=== Java Applet Embed ===
Contributors: huyz, ahmattox
Tags: embed, java, applet
Tested up to: 3.0.5
Stable tag: 0.5

Allows you to embed Java applets into your Wordpress blog.

== Description ==

Allows you to insert Java applets into your Wordpress blog.

If you've ever tried to embed a Java applet in a post or page by inserting
your usual `<applet>` or `<object>` tags, you would have noticed that
WordPress doesn't recognize the tags and the tags disappear. This plugin
supports new shortcodes permitting WordPress to recognize and properly render
the applets.

You can choose among three possible display modes:

1. Inline, loaded immediately
2. Inline, loaded only when the user clicks
3. Popup window, loaded only when the user clicks

You can reference your JAR archive with any full URL, even on another web
site.  And you can even upload the JAR into WordPress using the Media
Library and then reference the JAR file as you would any other image, video,
or audio.

It is based on the great work done by
[ahmattox](http://profiles.wordpress.org/users/ahmattox/) on his
[WordPress Processing Embed](http://wordpress.org/extend/plugins/wordpress-processing-embed/).
In fact, it works almost exactly the same way and offers the same features.

== Installation ==

The installation procedure is as usual:

1. Upload the 'java-applet-embed' folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Usage ==

= Global Settings =

First, you can optionally set some global default settings for all applets by
visiting the "Java Applet Embed" settings page in the Administration screens.

= JAR code =

Then, you need to make your applets' Java code accessible.  Either, make the
JAR archive(s) available from an URL not managed by WordPress (even on some
external web site), or upload the file using the WordPress Media Library, as
you would any other image, video, or audio file.

= For each applet =

For each Java applet, simply add the shortcode to the content of the appropriate
post or page.  Multiple applets are allowed.

The most basic format is:
`[applet code="your.applet.ClassName" file="http://domain.com/full/path/to/yourJar.jar"]`
This shortcode is in non-enclosing form.

You can override global default settings by specifying 'width', 'height',
or 'method' attributes in the shortcode, where the method can be 'inline',
'onclick', or 'popup'.

By using the enclosing form of the applet shortcode, you can specify "alternate
content" and, optionally, applet parameters.  The enclosing format is:
`[applet code="your.applet.ClassName" file="http://domain.com/full/path/to/yourJar.jar"]
[param name="paramName" value="paramValue"]
alternate content
[/applet]`

Alternate content is used differently depending on the loading method of the
applet: for the 'popup' and 'onclick' methods, alternate content will be
wrapped in a link and displayed for the user to click; for the 'inline'
method, it will only be displayed if Java is not installed.

= Examples =

Example:
`[applet code="us.huyz.SlickApplet" file="http://huyz.us/wp-content/uploads/2011/06/slickApplets.jar"
method="popup"]Popup the applet →[/applet]`

Example (Note `files/` for multisite): 
`<div style="text-align:center;">
[applet code="de.mud.jta.Applet" file="http://huyz.us/files/2011/06/jta26.jar"
width="1000" height="400" method="onclick"]
[param name="config" value="http://huyz.us/non-wp-content/applet.conf"]
[param name="Terminal.size" value="[112,24]"]
↑
← Load the applet →
↓
[/applet]
</div>`

= Details about shortcodes =

As with all WordPress shortcodes, you cannot
[mix shortcodes](http://codex.wordpress.org/Shortcode_API#Unclosed_Shortcodes]
in enclosing form and non-enclosing (a.k.a. "unclosed") form.

Either do
`[applet...]
[applet...]
[applet...]`
or
`[applet...]...[/applet]
[applet...]...[/applet]
[applet...]...[/applet]`
but don't mix and match within a single post or page.

Unlike with true WordPress shortcodes, there is no restriction about [square
brackets](http://codex.wordpress.org/Shortcode_API#Square_Brackets) within
attributes of `[param]` shortcodes.  Thus, this is allowed:
`[param name="Terminal.size" value="[112,24]"]`

== Frequently Asked Questions ==

= Why use the 'onclick' loading method? =

Loading a Java applet takes time, especially the first time the Java plug-in
is loaded by the browser and especially the first time the JAR file is
downloaded by the browser.  Your users may be annoyed if your page immediately
does all this without their initial confirmation and causes delays.

= Why use the 'inline' loading method? =

The 'onclick' and 'popup' methods require users to run Javascript, while the
'inline' method doesn't.  These days, everyone should be running Javascript
but who knows what your crazy requirements.

= Will this plugin work with WordPress 2.x or 3.1? =

It's very likely to work for other versions of WordPress, so give it a shot,
and let me know how it works out.

= How can I upload other supporting files such as config files? =

Right now, that's not supported.  Only JAR files can be uploaded to WordPress.
You have to place your supporting files somewhere else: outside of the
management of WordPress or some other web site.
If there's enough user demand, I'll look into supporting more file types for
upload.

= Is there a way to specify a minimum Java version? =

Not right now.  I don't know if there's demand for it.

== Screenshots ==

1. Administration settings
2. Editing post or page with shortcodes
3. The rendered post or page.  An applet with the 'onclick' loading method first only shows *alternate
content* for the user to click.
4. The Java virtual machine loading, after the user clicks to load the 'onclick applet'.
5. The rendered post or page with a sample applet.

== Changelog ==

= 0.5 =
* Initial Release, based on [WordPress Processing Embed](http://downloads.wordpress.org/plugin/wordpress-processing-embed.0.5.zip) version 0.5
