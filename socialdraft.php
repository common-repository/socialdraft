<?php
/*
Plugin Name: Socialdraft Plugin
Plugin URI: http://socialdraft.com
Description: The official plugin of SocialDraft.com to monitor reviews and analysis. To get started: 1) Sign up for a Socialdraft API key, and 3) Go to your Socialdraft configuration page, and save your API key.
Author: Michael
Version: 1.0
Author URI: http://friendseat.com/mikelcelestial
*/

if(!class_exists('Socialdraft'))
{
	class Socialdraft
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$Socialdraft_Settings = new Socialdraft_Settings();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=socialdraft">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}


	} // END class Socialdraft
} // END if(!class_exists('Socialdraft'))

if(class_exists('Socialdraft'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('Socialdraft', 'activate'));
	register_deactivation_hook(__FILE__, array('Socialdraft', 'deactivate'));

	// instantiate the plugin class
	$socialdraft = new Socialdraft();

}
