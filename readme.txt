=== Show Archive Descriptions ===
Tags: author, category, tag, description, show
Requires at least: 3.5
Tested up to: 3.9
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display category, tag and author descriptions on the appropriate archive pages.

== Description ==

Show Archive Descriptions is a plugin that displays the description for a category on that category's first archive page. It does the same for tags and author archives.

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just flat out does not work, please go to <a href="http://wordpress.org/plugins/show-archive-descriptions/#compatibility">Compatibility</a> and click "Broken" after verifying your WordPress version and the version of the plugin you are using.

Then, create a thread in the <a href="http://wordpress.org/support/plugin/show-archive-descriptions">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues, to be sure that the issue is with the current version and not with an older version where the issue may have already been fixed.

<strong>Please do not use the <a href="http://wordpress.org/support/view/plugin-reviews/show-archive-descriptions">Reviews</a> section to report issues or request new features.</strong>

= Features =

<ul>
<li>Display archive descriptions (category, tag, author) with your custom text</li>
<li>Create custom titles</li>
</ul>

== Installation ==

1. Upload plugin file through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; Show Archive Descriptions, configure plugin.
4. Go to a category archive page, a tag archive page, or an author page (depending on which options you set). See the description at the top of the first page. Make sure each category and tag has a written description.

== Frequently Asked Questions ==

= How do I use the plugin? =

Go to Settings &raquo; Show Archive Descriptions and choose on which pages you would like to see the archive description: Author Pages, Tag Pages, Category pages. A few other options are also available.

= I activated the plugin but don't see anything on the page. =

Is the page/post cached?

Did you write a description for the category/tag you are viewing? If you are viewing an author archive, has that author filled in their description in their profile? 

If, for example, you select category description to be displayed, and a given category does not have a written description, then nothing will be displayed regardless of what options you have selected. Check the HTML source for a comment saying "No description is available for the current category/tag/author".

= How can I style the output? =

There is some basic CSS applied to the description boxes. Look in the plugin's CSS folder for satd.css to see what CSS is being applied. Viewing the HTML source of the page is also helpful. Add additional CSS to your local stylesheet, and override the existing CSS if you want a different style than the current one.

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_satd_admin_css');`

= How do I use HTML in the descriptions? =

Normally, you can't. Use this plugin to enable HTML in archive descriptions: http://wordpress.org/plugins/allow-html-in-category-descriptions/

Note that you can use HTML in the custom title area of this plugin.

== Screenshots ==

1. Plugin settings page
2. Author Archive page with description at the top
3. Category Archive page with description at the top

== Changelog ==

= 0.0.8 =
- confirmed compatibility with WordPress 4.1
- fixed minor bug when toggling gravatars

= 0.0.7 =
- updated .pot file and readme

= 0.0.6 =
- minor code optimization
- CSS compression
- fixed validation issue
- option to create custom title with template tags for category or tag name

= 0.0.5 =
- permanent fix for Undefined Index issue
- admin CSS and page updates
- you can now enter custom title for archive description

= 0.0.4 =
- code fix

= 0.0.3 =
- updated support tab

= 0.0.2 =
- minor code fix

= 0.0.1 =
- created

== Upgrade Notice ==

= 0.0.8 =
- confirmed compatibility with WordPress 4.1, fixed minor bug when toggling gravatars

= 0.0.7 =
- updated .pot file and readme

= 0.0.6 =
- minor code optimization; CSS compression; fixed validation issue; option to create custom title with template tags for category or tag name

= 0.0.5 =
- permanent fix for Undefined Index issue; admin CSS and page updates; you can now enter custom title for archive description

= 0.0.4 =
- code fix

= 0.0.3 =
- updated support tab

= 0.0.2 =
- minor code fix

= 0.0.1 =
created