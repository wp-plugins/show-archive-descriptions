<?php
/*
Plugin Name: Show Archive Descriptions
Plugin URI: http://www.jimmyscode.com/wordpress/show-category-tag-descriptions/
Description: Show category, tag and author descriptions on your archive pages.
Version: 0.0.8
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
if (!defined('SATD_PLUGIN_NAME')) {
	define('SATD_PLUGIN_NAME', 'Show Archive Descriptions');
	// plugin constants
	define('SATD_VERSION', '0.0.8');
	define('SATD_SLUG', 'show-archive-descriptions');
	define('SATD_LOCAL', 'satd');
	define('SATD_OPTION', 'satd');
	define('SATD_OPTIONS_NAME', 'satd_options');
	define('SATD_PERMISSIONS_LEVEL', 'manage_options');
	define('SATD_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('SATD_DEFAULT_ENABLED', true);
	define('SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES', false);
	define('SATD_DEFAULT_DISPLAY_ON_TAG_PAGES', false);
	define('SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES', false);
	define('SATD_DEFAULT_SHOW_GRAVATAR', false);
	define('SATD_DEFAULT_GRAVATAR_SIZE', 45);
	define('SATD_CUSTOM_TITLE', '');
	/* option array member names */
	define('SATD_DEFAULT_ENABLED_NAME', 'enabled');
	define('SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES_NAME', 'showcategorypages');
	define('SATD_DEFAULT_DISPLAY_ON_TAG_PAGES_NAME', 'showtagpages');
	define('SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES_NAME', 'showauthorpages');
	define('SATD_DEFAULT_SHOW_GRAVATAR_NAME', 'showgravatar');
	define('SATD_DEFAULT_GRAVATAR_SIZE_NAME', 'gravatarsize');
	define('SATD_CUSTOM_TITLE_NAME', 'customtitle');
}
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', satd_get_local()));
	}

	// localization to allow for translations
	add_action('init', 'satd_translation_file');
	function satd_translation_file() {
		$plugin_path = satd_get_path() . '/translations';
		load_plugin_textdomain(satd_get_local(), '', $plugin_path);
		register_satd_style();
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'satd_options_init');
	function satd_options_init() {
		register_setting(SATD_OPTIONS_NAME, satd_get_option(), 'satd_validation');
		register_satd_admin_style();
	}
	// validation function
	function satd_validation($input) {
		if (!empty($input)) {
			// validate all form fields
			$input[SATD_DEFAULT_ENABLED_NAME] = (bool)$input[SATD_DEFAULT_ENABLED_NAME];
			$input[SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES_NAME] = (bool)$input[SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES_NAME];
			$input[SATD_DEFAULT_DISPLAY_ON_TAG_PAGES_NAME] = (bool)$input[SATD_DEFAULT_DISPLAY_ON_TAG_PAGES_NAME];
			$input[SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES_NAME] = (bool)$input[SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES_NAME];
			$input[SATD_DEFAULT_SHOW_GRAVATAR_NAME] = (bool)$input[SATD_DEFAULT_SHOW_GRAVATAR_NAME];
			$input[SATD_DEFAULT_GRAVATAR_SIZE_NAME] = intval($input[SATD_DEFAULT_GRAVATAR_SIZE_NAME]);
			$input[SATD_CUSTOM_TITLE_NAME] = wp_kses_post(force_balance_tags($input[SATD_CUSTOM_TITLE_NAME]));
		}
		return $input;
	}
	// add Settings sub-menu
	add_action('admin_menu', 'satd_plugin_menu');
	function satd_plugin_menu() {
		add_options_page(SATD_PLUGIN_NAME, SATD_PLUGIN_NAME, SATD_PERMISSIONS_LEVEL, satd_get_slug(), 'satd_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	function SATD_page() {
		// check perms
		if (!current_user_can(SATD_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', satd_get_local()));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo satd_getimagefilename('description.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo SATD_PLUGIN_NAME; _e(' by ', satd_get_local()); ?><a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div><?php _e('You are running plugin version', satd_get_local()); ?> <strong><?php echo SATD_VERSION; ?></strong>.</div>

			<?php /* http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page--wp-24971 */ ?>
			<?php $active_tab = (isset($_GET['tab']) ? $_GET['tab'] : 'settings'); ?>
			<h2 class="nav-tab-wrapper">
			  <a href="?page=<?php echo satd_get_slug(); ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', satd_get_local()); ?></a>
				<a href="?page=<?php echo satd_get_slug(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', satd_get_local()); ?></a>
			</h2>
			
			<form method="post" action="options.php">
			<?php settings_fields(SATD_OPTIONS_NAME); ?>
			<?php $options = satd_getpluginoptions(); ?>
			<?php update_option(satd_get_option(), $options); ?>
			<?php if ($active_tab == 'settings') { ?>
			<h3 id="settings"><img src="<?php echo satd_getimagefilename('settings.png'); ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', satd_get_local()); ?></h3>
				<table class="form-table" id="theme-options-wrap">
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', satd_get_local()); ?>" for="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', satd_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', satd_checkifset(SATD_DEFAULT_ENABLED_NAME, SATD_DEFAULT_ENABLED, $options)); ?> /></td>
					</tr>
					<?php satd_explanationrow(__('Is plugin enabled? Uncheck this to turn it off temporarily.', satd_get_local())); ?>
					<?php satd_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Where do you want to show archive descriptions', satd_get_local()); ?>"><?php _e('Where do you want to show archive descriptions', satd_get_local()); ?></label></strong></th>
						<td>
						<input type="checkbox" id="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES_NAME; ?>]" name="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES_NAME; ?>]" value="1" <?php checked('1', satd_checkifset(SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES_NAME, SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES, $options)); ?> /> <?php _e('Category Pages', satd_get_local()); ?>
						<input type="checkbox" id="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_DISPLAY_ON_TAG_PAGES_NAME; ?>]" name="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_DISPLAY_ON_TAG_PAGES_NAME; ?>]" value="1" <?php checked('1', satd_checkifset(SATD_DEFAULT_DISPLAY_ON_TAG_PAGES_NAME, SATD_DEFAULT_DISPLAY_ON_TAG_PAGES, $options)); ?> /> <?php _e('Tag Pages', satd_get_local()); ?>
						<input type="checkbox" id="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES_NAME; ?>]" name="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES_NAME; ?>]" value="1" <?php checked('1', satd_checkifset(SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES_NAME, SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES, $options)); ?> /> <?php _e('Author Pages', satd_get_local()); ?>
						</td>
						</tr>
					<?php satd_explanationrow(__('Where to display archive description -- on Category pages, Tag pages, Author pages?', satd_get_local())); ?>
					<?php satd_explanationrow(__('Remember to fill in descriptions for author, categories and tags otherwise they will not display!', satd_get_local())); ?>
					<?php satd_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter custom title', satd_get_local()); ?>" for="<?php echo satd_get_option(); ?>[<?php echo SATD_CUSTOM_TITLE_NAME; ?>]"><?php _e('Enter custom title (category/tag only)', satd_get_local()); ?></label></strong></th>
						<td><textarea rows="12" cols="75" id="<?php echo satd_get_option(); ?>[<?php echo SATD_CUSTOM_TITLE_NAME; ?>]" name="<?php echo satd_get_option(); ?>[<?php echo SATD_CUSTOM_TITLE_NAME; ?>]"><?php echo satd_checkifset(SATD_CUSTOM_TITLE_NAME, SATD_CUSTOM_TITLE, $options); ?></textarea></td>
					</tr>
					<?php satd_explanationrow(__('Enter custom title here. Leave blank for default which is \'This is the XXX category/tag.\'<br />Template tag <strong>%CATEGORY%</strong> is for the category name and <strong>%TAG%</strong> is for the tag name. <strong> For categories and tags only.</strong>', satd_get_local())); ?>
					<?php satd_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Show gravatar with author description?', satd_get_local()); ?>" for="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_SHOW_GRAVATAR_NAME; ?>]"><?php _e('Show gravatar with author description?', satd_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_SHOW_GRAVATAR_NAME; ?>]" name="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_SHOW_GRAVATAR_NAME; ?>]" value="1" <?php checked('1', satd_checkifset(SATD_DEFAULT_SHOW_GRAVATAR_NAME, SATD_DEFAULT_SHOW_GRAVATAR, $options)); ?> /></td>
					</tr>
					<?php satd_explanationrow(__('Show author\'s gravatar with author description?', satd_get_local())); ?>
					<?php satd_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Gravatar size', satd_get_local()); ?>" for="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_GRAVATAR_SIZE_NAME; ?>]"><?php _e('Gravatar size', satd_get_local()); ?></label></strong></th>
						<td><input type="number" size="20" min="16" max="512" step="1" id="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_GRAVATAR_SIZE_NAME; ?>]" name="<?php echo satd_get_option(); ?>[<?php echo SATD_DEFAULT_GRAVATAR_SIZE_NAME; ?>]" value="<?php echo satd_checkifset(SATD_DEFAULT_GRAVATAR_SIZE_NAME, SATD_DEFAULT_GRAVATAR_SIZE, $options); ?>" /></td>
					</tr>
					<?php satd_explanationrow(__('Gravatar size (in pixels), max 512', satd_get_local())); ?>
				</table>
				<?php submit_button(); ?>
			<?php } else { ?>
			<h3 id="support"><img src="<?php echo satd_getimagefilename('support.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Support', satd_get_local()); ?></h3>
				<div class="support">
				<?php echo satd_getsupportinfo(satd_get_slug(), satd_get_local()); ?>
				</div>
			<?php } ?>
			</form>
		</div>
		<?php }

	// main function and action
	// http://yoast.com/wordpress-archive-pages/
  add_action('loop_start','satd_showarchivedescriptions');
  function satd_showarchivedescriptions() {
		$options = satd_getpluginoptions();
		if (!empty($options)) {
			$enabled = (bool)$options[SATD_DEFAULT_ENABLED_NAME];
		} else {
			$enabled = SATD_DEFAULT_ENABLED;
		}
		
		$output = '';
		
		if ($enabled) {
			if (!empty($options)) {
				$showoncats = $options[SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES_NAME];
				$showontags = $options[SATD_DEFAULT_DISPLAY_ON_TAG_PAGES_NAME];
				$showonauthors = $options[SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES_NAME];
				$customtitle = $options[SATD_CUSTOM_TITLE_NAME];
			} else {
				$showoncats = satd_setupvar($showoncats, SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES, SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES_NAME, $options);
				$showontags = satd_setupvar($showontags, SATD_DEFAULT_DISPLAY_ON_TAG_PAGES, SATD_DEFAULT_DISPLAY_ON_TAG_PAGES_NAME, $options);
				$showonauthors = satd_setupvar($showonauthors, SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES, SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES_NAME, $options);
				$customtitle = satd_setupvar($customtitle, SATD_CUSTOM_TITLE, SATD_CUSTOM_TITLE_NAME, $options);
			}

			if (is_archive()) { // we are on a Category, Tag, Author or Date archive page
				if (!get_query_var('paged')) { // we are on the first page of a possibly paged list

					if (is_category() && $showoncats) {
						$archivetype = __('category', satd_get_local());
					}
					if (is_tag() && $showontags) {
						$archivetype = __('tag', satd_get_local());
					}

					if ((is_category() && $showoncats) || (is_tag() && $showontags)) {
						// check if anything is written in the category or tag description
						$termdesc = term_description();
						if ($termdesc) {
							// load CSS
							satd_styles();

							$output = '<div class="satd-archive-description">';
							$output .= '<h2 class="satd-archive-title">';
							if ($customtitle) {
								$output .= $customtitle;
								$output = str_replace(array('%CATEGORY%', '%TAG%'), ucwords(single_term_title('', false)), $output);
							} else {
								$output .= __('This is the ', satd_get_local()) . ucwords(single_term_title('', false)) . ' ' . $archivetype . '.';
							}
							$output .= '</h2>';
							$output .= '<div class="satd-arch-content">' . wpautop($termdesc) . '</div>';
							$output .= '</div>';
						} else {
							$output = sprintf( __('<!-- %1$s: No description is available for the current %2$s.', satd_get_local()), SATD_PLUGIN_NAME, $archivetype) . ' -->';
						}
					} elseif (is_author() && $showonauthors) {
						// check if the author wrote anything in description
						$authordesc = get_the_author_meta('description');
						if ($authordesc) {
							// load CSS
							satd_styles();

							$showgravatar = $options[SATD_DEFAULT_SHOW_GRAVATAR_NAME];
							$authorname = get_the_author_meta('display_name');
							
							$output = '<div class="satd-archive-description">';
							if ($showgravatar) {
								$output .= '<span class="satd-gravatar">';
								$output .= get_avatar(get_the_author_meta('user_email'), $options[SATD_DEFAULT_GRAVATAR_SIZE_NAME], '', $authorname);
								$output .= '</span>';
							}
							$output .= '<span class="satd-author-name">';
							$output .= __('About ', satd_get_local()) . $authorname;
							$output .= '</span>';
							$output .= '<p class="satd-author-desc">' . $authordesc . '</p>';
							
							$output .= '</div>';
							
						} else {
							$output = sprintf( __('<!-- %s: No description is available for the current author.', satd_get_local()), SATD_PLUGIN_NAME) . ' -->';
						}
					}
				}
			}
			echo $output;
		} // end enabled
	}
		
	// show admin messages to plugin user
	add_action('admin_notices', 'satd_showAdminMessages');
	function satd_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(SATD_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == satd_get_slug()) { // we are on this plugin's settings page
						$options = satd_getpluginoptions();
						if (!empty($options)) {
							$enabled = (bool)$options[SATD_DEFAULT_ENABLED_NAME];
							if (!$enabled) {
								echo '<div id="message" class="error">' . SATD_PLUGIN_NAME . ' ' . __('is currently disabled.', satd_get_local()) . '</div>';
							}
						}
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function
	// enqueue admin CSS if we are on the plugin options page
	add_action('admin_head', 'insert_satd_admin_css');
	function insert_satd_admin_css() {
		global $pagenow;
		if (current_user_can(SATD_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == satd_get_slug()) { // we are on this plugin's settings page
						satd_admin_styles();
					}
				}
			}
		}
	}
	// add helpful links to plugin page next to plugin name
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'satd_plugin_settings_link');
	add_filter('plugin_row_meta', 'satd_meta_links', 10, 2);
	
	function satd_plugin_settings_link($links) {
		return satd_settingslink($links, satd_get_slug(), satd_get_local());
	}
	function satd_meta_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links,
			array(
				sprintf(__('<a href="http://wordpress.org/support/plugin/%s">Support</a>', satd_get_local()), satd_get_slug()),
				sprintf(__('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', satd_get_local()), satd_get_slug()),
				sprintf(__('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a>', satd_get_local()), satd_get_slug())
			));
		}
		return $links;	
	}
	// enqueue/register the plugin CSS file
	function satd_styles() {
		wp_enqueue_style('satd_style');
	}
	function register_satd_style() {
		wp_register_style('satd_style', 
			plugins_url(satd_get_path() . '/css/satd.css'), 
			array(), 
			SATD_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/satd.css')), 
			'all' );
	}
	// enqueue/register the admin CSS file
	function satd_admin_styles() {
		wp_enqueue_style('satd_admin_style');
	}
	function register_satd_admin_style() {
		wp_register_style('satd_admin_style',
			plugins_url(satd_get_path() . '/css/admin.css'),
			array(),
			SATD_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'satd_activate');
	function satd_activate() {
		$options = satd_getpluginoptions();
		update_option(satd_get_option(), $options);
	
		// delete option when plugin is uninstalled
		register_uninstall_hook(__FILE__, 'uninstall_satd_plugin');
	}
	function uninstall_satd_plugin() {
		delete_option(satd_get_option());
	}
		
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function satd_getpluginoptions() {
		return get_option(satd_get_option(), 
			array(
				SATD_DEFAULT_ENABLED_NAME => SATD_DEFAULT_ENABLED, 
				SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES_NAME => SATD_DEFAULT_DISPLAY_ON_CATEGORY_PAGES, 
				SATD_DEFAULT_DISPLAY_ON_TAG_PAGES_NAME => SATD_DEFAULT_DISPLAY_ON_TAG_PAGES, 
				SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES_NAME => SATD_DEFAULT_DISPLAY_ON_AUTHOR_PAGES, 
				SATD_DEFAULT_SHOW_GRAVATAR_NAME => SATD_DEFAULT_SHOW_GRAVATAR, 
				SATD_DEFAULT_GRAVATAR_SIZE_NAME => SATD_DEFAULT_GRAVATAR_SIZE,
				SATD_CUSTOM_TITLE_NAME => SATD_CUSTOM_TITLE
				));
	}
	
	// encapsulate these and call them throughout the plugin instead of hardcoding the constants everywhere
	function satd_get_slug() { return SATD_SLUG; }
	function satd_get_local() { return SATD_LOCAL; }
	function satd_get_option() { return SATD_OPTION; }
	function satd_get_path() { return SATD_PATH; }
	
	function satd_settingslink($linklist, $slugname = '', $localname = '') {
		$settings_link = sprintf( __('<a href="options-general.php?page=%s">Settings</a>', $localname), $slugname);
		array_unshift($linklist, $settings_link);
		return $linklist;
	}
	function satd_setupvar($var, $defaultvalue, $defaultvarname, $optionsarr) {
		if ($var == $defaultvalue) {
			$var = $optionsarr[$defaultvarname];
			if (!$var) {
				$var = $defaultvalue;
			}
		}
		return $var;
	}
	function satd_getsupportinfo($slugname = '', $localname = '') {
		$output = __('Do you need help with this plugin? Check out the following resources:', $localname);
		$output .= '<ol>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/support/plugin/%s">Support Forum</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://www.jimmyscode.com/wordpress/%s">Plugin Homepage / Demo</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/developers/">Development</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/changelog/">Changelog</a><br />', $localname), $slugname) . '</li>';
		$output .= '</ol>';
		
		$output .= sprintf( __('If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/%s/">rate it on WordPress.org</a>', $localname), $slugname);
		$output .= sprintf( __(' and click the <a href="http://wordpress.org/plugins/%s/#compatibility">Works</a> button. ', $localname), $slugname);
		$output .= '<br /><br /><br />';
		$output .= __('Your donations encourage further development and support. ', $localname);
		$output .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Support this plugin" width="92" height="26" /></a>';
		$output .= '<br /><br />';
		return $output;
	}
	function satd_checkifset($optionname, $optiondefault, $optionsarr) {
		return (isset($optionsarr[$optionname]) ? $optionsarr[$optionname] : $optiondefault);
	}
	function satd_getlinebreak() {
	  echo '<tr valign="top"><td colspan="2"></td></tr>';
	}
	function satd_explanationrow($msg = '') {
		echo '<tr valign="top"><td></td><td><em>' . $msg . '</em></td></tr>';
	}
	function satd_getimagefilename($fname = '') {
		return plugins_url(satd_get_path() . '/images/' . $fname);
	}
?>