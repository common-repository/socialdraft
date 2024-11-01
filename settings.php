<?php
require_once('includes/functions.php');

if(!class_exists('Socialdraft_Settings'))
{
	class Socialdraft_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
			add_action('admin_enqueue_scripts', 'sd_scripts' );		
		}
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
			add_action('init','register_session');
			add_action('wp_logout', 'myEndSession');
			add_action('wp_login', 'myEndSession');	
			add_action( 'init', 'plugin_send_email' );			
        	// register your plugin's settings
        	register_setting('socialdraft-group', 'setting_appid');
        	register_setting('socialdraft-group', 'setting_appkey');
        	#register_setting('socialdraft-group', 'setting_byid');
        	register_setting('socialdraft-group', 'setting_email');

        	// add your settings section
        	add_settings_section(
        	    'socialdraft-section', 
        	    '', 
        	    array(&$this, 'settings_section_socialdraft'), 
        	    'socialdraft'
        	);

        	// add your setting's fields
            add_settings_field(
                'socialdraft-setting_appid', 
                'Application ID', 
                array(&$this, 'settings_field_input_text'), 
                'socialdraft', 
                'socialdraft-section',
                array(
                    'field' => 'setting_appid'
                )
            );
            add_settings_field(
                'socialdraft-setting_appkey', 
                'Application Key', 
                array(&$this, 'settings_field_input_text'), 
                'socialdraft', 
                'socialdraft-section',
                array(
                    'field' => 'setting_appkey'
                )
            );
            /* add_settings_field(
                'socialdraft-setting_byid', 
                'Business ID', 
                array(&$this, 'settings_field_input_text'), 
                'socialdraft', 
                'socialdraft-section',
                array(
                    'field' => 'setting_byid'
                )
            ); */
            add_settings_field(
                'socialdraft-setting_email', 
                'Email', 
                array(&$this, 'settings_field_input_text'), 
                'socialdraft', 
                'socialdraft-section',
                array(
                    'field' => 'setting_email'
                )
            );
			
			settings_errors('socialdraft-group');
			$appid = get_option("setting_appid");
			$appkey = get_option("setting_appkey");
			$email = get_option("setting_email");
			
			if(empty($appid) || empty($appkey) || empty($email)) {
				$type = 'error';
				$message = __( 'Please fill out all fields. Data can not be empty.', 'settings-empty-fields' );
			} else {
				$type = 'updated';
				$message = __( 'Changes was saved.', 'settings-changes-saved' );			
			}
			
			add_settings_error(
				'socialdraft-group',
				esc_attr( 'settings_updated' ),
				$message,
				$type
			);

        }
        
        public function settings_section_socialdraft()
        {

        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
        /**
         * add a menu
         */		
        public function add_menu()
        {
			global $sd_settings_page;
            // Add a page to manage this plugin's settings
        	$sd_settings_page = add_options_page(
        	    'Socialdraft Settings', 
        	    'Socialdraft', 
        	    'manage_options', 
        	    'socialdraft', 
        	    array(&$this, 'plugin_settings_page')
        	);
			add_menu_page( 'Socialdraft', 'Socialdraft', 'edit_themes', 'socialdraft-admin-menu', array(&$this, 'plugin_settings_page'), '', 47);
			add_submenu_page('socialdraft-admin-menu', 'Settings', 'Settings', 'manage_options', 'socialdraft-admin-menu' );			
			add_submenu_page( 'socialdraft-admin-menu', 'Trendings', 'Trendings', 'edit_themes', 'socialdraft-trendings', array(&$this, 'plugin_trendings_page') );
			add_submenu_page( 'socialdraft-admin-menu', 'Customers', 'Customers', 'edit_themes', 'socialdraft-customers', array(&$this, 'plugin_customers_page') );
			add_submenu_page( 'socialdraft-admin-menu', 'Reviews', 'Reviews', 'edit_themes', 'socialdraft-reviews', array(&$this, 'plugin_reviews_page') );
			add_action('admin_menu', 'my_menu_pages');
        } // END public function add_menu()

        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        }  // END public function plugin_settings_page()
		
        /**
         * Trendings Submenu Callback
         */		
        public function plugin_trendings_page()
        {
			add_action('init', 'addthickbox');
			
        	if(!current_user_can('edit_themes'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/trendings.php", dirname(__FILE__)));
        }  // END public function plugin_trendings_page()
		
        /**
         * Customers Submenu Callback
         */		
        public function plugin_customers_page()
        {
        	if(!current_user_can('edit_themes'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/customers.php", dirname(__FILE__)));
        }  // END public function plugin_customers_page()

        /**
         * Reviews Submenu Callback
         */		
        public function plugin_reviews_page()
        {
        	if(!current_user_can('edit_themes'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/reviews.php", dirname(__FILE__)));
        } // END public function plugin_reviews_page()
		
        function js_libs() {
            wp_enqueue_script('jquery');
            wp_enqueue_script('thickbox');
        }

        function style_libs() {
            wp_enqueue_style('thickbox');
        }
		
		function addthickbox()
		{
			add_thickbox();
		}
		
		public function sd_scripts($hook)
		{
			global $sd_settings_page;
			
			if( $hook != $sd_settings_page ) 
			return;
 
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'sd-script', plugins_url( 'socialdraft/js/socialdraft.js' ,  __FILE__ ) );
			wp_register_style( 'sd-style', plugins_url( 'socialdraft/css/socialdraft.css' ,  __FILE__ ) );
			wp_enqueue_style( 'sd-style' );
			#wp_register_script( 'sd-script', plugins_url( 'js/socialdraft.js', __FILE__ ) );
			wp_register_script( 'sd-script', plugins_url( 'socialdraft/js/jquery.brokenimage.js', __FILE__ ) );
			// For either a plugin or a theme, you can then enqueue the script:
			wp_enqueue_script( 'sd-script' );
			
			wp_deregister_script('jquery'); // Remove WordPress core's jQuery
			wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', false, null, false);
			add_filter('script_loader_src', 'theme_jquery_local_fallback', 10, 2);			
		}

		function theme_jquery_local_fallback($src, $handle) {
		  static $add_jquery_fallback = false;

		  if ($add_jquery_fallback) {
			echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/vendor/jquery-1.10.2.min.js"><\/script>\')</script>' . "\n";
			$add_jquery_fallback = false;
		  }

		  if ($handle === 'jquery') {
			$add_jquery_fallback = true;
		  }

		  return $src;
		}
		
		function myStartSession() {
			if(!session_id()) {
				session_start();
			}
		}

		function myEndSession() {
			session_destroy ();
		}		
		
    } // END class Socialdraft_Settings
} // END if(!class_exists('Socialdraft_Settings'))
