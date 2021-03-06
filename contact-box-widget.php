<?php
/**
 * Contact Box Widget
 *
 * Display posts as widget items.
 *
 * @package   DPT_Contact_Box_Widget
 * @author    Wilfried Reiter <wilfried.reiter@devpoint.at>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/extend/plugins/post-teaser-widget
 * @copyright 2015-2016 Wilfried Reiter
 *
 * @post-teaser-widget
 * Plugin Name:       Contact Box Widget
 * Plugin URI:        http://wordpress.org/extend/plugins/post-teaser-widget
 * Description:       An advanced posts display widget with many options: get posts by post type and taxonomy & term or by post ID; sorting & ordering; feature images; custom templates and more.
 * Version:           1.3.0
 * Author:            willriderat
 * Author URI:        http://devpoint.at
 * Text Domain:       contact-box-widget
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/devpoint/wordpress-contact-box-widget
 */

/**
 * Copyright 2015-2016 Wilfried Reiter (email: wilfried.reiter@devpoint.at)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


// Block direct requests
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Load the widget on widgets_init
function dpt_load_contact_box_widget() {
	register_widget('DPT_Contact_Box_Widget');
}
add_action('widgets_init', 'dpt_load_contact_box_widget');


/**
 * Contact Box Widget Class
 */
class DPT_Contact_Box_Widget extends WP_Widget {

    /**
     * Plugin version number
     *
     * The variable name is used as a unique identifier for the widget
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_version = '1.3.0';

    /**
     * Unique identifier for your widget.
     *
     * The variable name is used as a unique identifier for the widget
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $widget_slug = 'dpt_contact-box-widget';
    
    /**
     * Unique identifier for your widget.
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * widget file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $widget_text_domain = 'contact-box-widget';
    
    /**
     * Map with features the widget should make use of
     *
     * @since    1.2.0
     *
     * @var      array
     */
    protected $feature_map = array();
    
	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() 
	{
		// load translation
		load_plugin_textdomain(
			$this->get_widget_text_domain(), 
			false,
			basename(dirname(__FILE__)) . '/languages/');

		// The widget constructor
		parent::__construct(
			$this->get_widget_slug(),
			__('Contact Box', $this->get_widget_text_domain()),
			array(
				'description' => __('Contact box with Phone number and Email.', $this->get_widget_text_domain()),
				'classname' => $this->get_widget_text_domain(),
			)
		);
		
		// Call widget initialization
		add_action('init', array($this, 'init'));
	
		// Setup the default variables after wp is loaded
		add_action('wp_loaded', array($this, 'setup_defaults'));

		// Register admin styles and scripts
		add_action('admin_enqueue_scripts', array($this, 'register_admin_styles'));
		add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));

		// Register styles and scrips
		add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
	}
	
	/**
	 * Return the widget slug.
	 *
	 * @since  1.0.0
	 *
	 * @return string - Plugin slug variable.
	 */
	public function get_widget_slug() 
	{
		return $this->widget_slug;
	}

	/**
	 * Return the widget text domain.
	 *
	 * @since  1.0.0
	 *
	 * @return string - Plugin text domain variable.
	 */
	public function get_widget_text_domain() 
	{
		return $this->widget_text_domain;
	}
	
	/**
	 * Return the plugin version.
	 *
	 * @since  1.0.0
	 *
	 * @return string - Plugin version variable.
	 */
	public function get_plugin_version() 
	{
		return $this->plugin_version;
	}


	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/
	
	/**
	 * Outputs the content of the widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param  array $args - The array of form elements
	 * @param  array $instance - The current instance of the widget
	 * @return void
	 */
	public function widget($args, $instance) 
	{
		$phone = $instance['phone'];
		$email = $instance['email'];
		$instance['title'] = $this->_apply_text_filters(apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']));
		$instance['phone'] = $this->_apply_phone_filters(apply_filters($this->get_widget_slug() . '_phone', $phone, $args, $instance));
		$instance['phone_link'] = $this->_apply_phone_link_filters(apply_filters($this->get_widget_slug() . '_phone_link', $phone, $args, $instance));
		$instance['email'] = apply_filters($this->get_widget_slug() . '_email', $email, $args, $instance);
		$instance['email_link'] = apply_filters($this->get_widget_slug() . '_email_link', $email, $args, $instance);
		$instance['facebook'] = $this->_apply_text_filters(apply_filters($this->get_widget_slug() . '_facebook', $instance['facebook'], $args, $instance));
		$instance['facebook_link'] = apply_filters($this->get_widget_slug() . '_facebook_link', $instance['facebook_link'], $args, $instance);
		$instance['youtube'] = $this->_apply_text_filters(apply_filters($this->get_widget_slug() . '_youtube', $instance['youtube'], $args, $instance));
		$instance['youtube_link'] = apply_filters($this->get_widget_slug() . '_youtube_link', $instance['youtube_link'], $args, $instance);
		$instance['custom_01'] = $this->_apply_text_filters(apply_filters($this->get_widget_slug() . '_custom_01', $instance['custom_01'], $args, $instance));
		$instance['custom_01_link'] = apply_filters($this->get_widget_slug() . '_custom_01_link', $instance['custom_01_link'], $args, $instance);
		$instance['custom_01_new_window'] = apply_filters($this->get_widget_slug() . '_custom_01_new_window', $instance['custom_01_new_window'], $args, $instance);
		$instance['custom_02'] = $this->_apply_text_filters(apply_filters($this->get_widget_slug() . '_custom_02', $instance['custom_02'], $args, $instance));
		$instance['custom_02_link'] = apply_filters($this->get_widget_slug() . '_custom_02_link', $instance['custom_02_link'], $args, $instance);
		$instance['custom_02_new_window'] = apply_filters($this->get_widget_slug() . '_custom_02_new_window', $instance['custom_02_new_window'], $args, $instance);
		$instance['label_length'] = apply_filters($this->get_widget_slug() . '_label_length', $instance['label_length'], $args, $instance);
		include ($this->get_template('widget', $instance['template']));
    }

    /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param  array $new_instance - Values just sent to be saved.
	 * @param  array $old_instance - Previously saved values from database.
	 * @return array - Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$new_instance = wp_parse_args((array)$new_instance, self::get_defaults());
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['phone'] = strip_tags($new_instance['phone']);
		$instance['email'] = strip_tags($new_instance['email']);
		$instance['facebook'] = strip_tags($new_instance['facebook']);
		$instance['facebook_link'] = strip_tags($new_instance['facebook_link']);
		$instance['youtube'] = strip_tags($new_instance['youtube']);
		$instance['youtube_link'] = strip_tags($new_instance['youtube_link']);
		$instance['custom_01'] = strip_tags($new_instance['custom_01']);
		$instance['custom_01_link'] = strip_tags($new_instance['custom_01_link']);
		$instance['custom_01_new_window'] = strip_tags($new_instance['custom_01_new_window']);
		$instance['custom_02'] = strip_tags($new_instance['custom_02']);
		$instance['custom_02_link'] = strip_tags($new_instance['custom_02_link']);
		$instance['custom_02_new_window'] = strip_tags($new_instance['custom_02_new_window']);
		$instance['label_length'] = strip_tags($new_instance['label_length']);
		$instance['template'] = strip_tags($new_instance['template']);
        return $instance;
    }

    /**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param  array $instance - Previously saved values from database.
	 * @return void
	 */
	public function form($instance) 
	{
		include ($this->get_template('widget-admin', 'default'));
	}

	/**
	 * Loads theme files in appropriate hierarchy:
	 * 1. child theme 2. parent theme 3. plugin resources.
	 * Will look in the plugins/contact-box-widget directory in a theme
	 * and the views directory in the plugin
	 *
	 * Based on a function in the amazing image-widget
	 * by Matt Wiebe at Modern Tribe, Inc.
	 * http://wordpress.org/extend/plugins/image-widget/
	 * 
	 * @param  string $template - template file to search for
	 * @param  string $custom_template
	 * @return string - with template path
	 **/
	protected function get_template($template, $custom_template) 
	{
		// whether or not .php was added
		$template_slug = rtrim($template, '.php');
		$template = $template_slug . '.php';
		
		// set to the default
		$file = 'views/' . $template;

		// look for a custom version
		$widgetThemeTemplates = array();
		$widgetThemePath = 'plugins/' . $this->get_widget_text_domain() . '/';
		if (!empty($custom_template) && $custom_template != 'default')
		{
			$custom_template_slug = rtrim($custom_template, '.php');
			$widgetThemeTemplates[] = $widgetThemePath . $custom_template_slug . '.php';
		}
		$widgetThemeTemplates[] = $widgetThemePath . $template;
		if ($theme_file = locate_template($widgetThemeTemplates))
		{
			$file = $theme_file;
		}
		
		return apply_filters($this->get_widget_slug() . '_template_' . $template_slug, $file);
	}

	/**
	 * @return array - with default values
	 */
	private static function get_defaults() 
	{
		$defaults = array(
			'title' => '',
			'phone' => '',
			'email' => '',
			'facebook' => '',
			'facebook_link' => '',
			'youtube' => '',
			'youtube_link' => '',
			'custom_01' => '',
			'custom_01_link' => '',
			'custom_01_new_window' => '',
			'custom_02' => '',
			'custom_02_link' => '',
			'custom_02_new_window' => '',
			'label_length' => '',
			'template' => 'default'
		);
		return $defaults;
	}

	/*--------------------------------------------------*/
	/* Template functions
	/*--------------------------------------------------*/

	/**
	 * Should a certain feature being used
	 *
     * @since  1.2.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_feature($feature)
	{
		return (!empty($this->feature_map[$feature]));
	}

	/**
	 * Check for widget title
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_title(&$instance)
	{
		return !empty($instance['title']);
	}

	/**
	 * Print widget title
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_title(&$instance)
	{
		echo $instance['title'];
	}

	/**
	 * Check widget show labels
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_label(&$instance)
	{
		return (!empty($instance['label_length']) && $instance['label_length'] != 'none');
	}

	/**
	 * Check widget label length
	 *
     * @since  1.0.0
     *
     * @param  array  $instance 
     * @param  string $length 
	 * @return bool
	 */
	public function is_label_length(&$instance, $length)
	{
		return ($length == $instance['label_length']);
	}

	/**
	 * Retrieve available widget label length list
	 *
     * @since  1.0.0
     *
     * @return array - with label_length[name,label]
	 */
	public function get_label_length_list()
	{
		$label_length_list = array(
			array('name' => 'short', 'label' => __('short', $this->get_widget_text_domain())),
			array('name' => 'long', 'label' => __('long', $this->get_widget_text_domain())),
			array('name' => 'none', 'label' => __('none', $this->get_widget_text_domain())));
		$label_length_list = apply_filters($this->get_widget_slug() . '_label_length_list', $label_length_list);
		return $label_length_list;
	}

	/**
	 * Should widget phone being used
	 *
     * @since  1.2.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_phone(&$instance)
	{
		return (!empty($this->feature_map['phone']) && !empty($instance['phone']));
	}

	/**
	 * Print widget phone
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_phone(&$instance)
	{
		echo $instance['phone'];
	}

	/**
	 * Print widget phone link
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_phone_link(&$instance)
	{
		echo $instance['phone_link'];
	}

	/**
	 * Print widget phone label
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_phone_label(&$instance)
	{
		$label_text = '';
		switch ($instance['label_length']) 
		{
			case 'short':
				$label_text = _x('Phone:', 'short', $this->get_widget_text_domain());
				break;
			case 'long':
				$label_text = _x('Phone:', 'long', $this->get_widget_text_domain());
				break;
		}
		echo apply_filters($this->get_widget_slug() . '_phone_label', $label_text, $instance['label_length']);
	}

	/**
	 * Should widget email being used
	 *
     * @since  1.2.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_email(&$instance)
	{
		return (!empty($this->feature_map['email']) && !empty($instance['email']));
	}

	/**
	 * Print widget email
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_email(&$instance)
	{
		echo $instance['email'];
	}

	/**
	 * Print widget email link
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_email_link(&$instance)
	{
		echo $instance['email_link'];
	}

	/**
	 * Print widget email label
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_email_label(&$instance)
	{
		$label_text = '';
		switch ($instance['label_length']) 
		{
			case 'short':
				$label_text = _x('Email:', 'short', $this->get_widget_text_domain());
				break;
			case 'long':
				$label_text = _x('Email:', 'long', $this->get_widget_text_domain());
				break;
		}
		echo apply_filters($this->get_widget_slug() . '_email_label', $label_text, $instance['label_length']);
	}

	/**
	 * Should widget facebook being used
	 *
     * @since  1.1.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_facebook(&$instance)
	{
		return (!empty($this->feature_map['facebook']) && !empty($instance['facebook']));
	}

	/**
	 * Print widget facebook
	 *
     * @since  1.1.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_facebook(&$instance)
	{
		echo $instance['facebook'];
	}

	/**
	 * Print widget facebook link
	 *
     * @since  1.1.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_facebook_link(&$instance)
	{
		echo $instance['facebook_link'];
	}

	/**
	 * Print widget facebook label
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_facebook_label(&$instance)
	{
		$label_text = '';
		switch ($instance['label_length']) 
		{
			case 'short':
				$label_text = _x('Facebook:', 'short', $this->get_widget_text_domain());
				break;
			case 'long':
				$label_text = _x('Facebook:', 'long', $this->get_widget_text_domain());
				break;
		}
		echo apply_filters($this->get_widget_slug() . '_facebook_label', $label_text, $instance['label_length']);
	}

	/**
	 * Should widget youtube being used
	 *
     * @since  1.2.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_youtube(&$instance)
	{
		return (!empty($this->feature_map['youtube']) && !empty($instance['youtube']));
	}

	/**
	 * Print widget youtube
	 *
     * @since  1.2.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_youtube(&$instance)
	{
		echo $instance['youtube'];
	}

	/**
	 * Print widget youtube link
	 *
     * @since  1.2.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_youtube_link(&$instance)
	{
		echo $instance['youtube_link'];
	}

	/**
	 * Print widget youtube label
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_youtube_label(&$instance)
	{
		$label_text = '';
		switch ($instance['label_length']) 
		{
			case 'short':
				$label_text = _x('Youtube:', 'short', $this->get_widget_text_domain());
				break;
			case 'long':
				$label_text = _x('Youtube:', 'long', $this->get_widget_text_domain());
				break;
		}
		echo apply_filters($this->get_widget_slug() . '_youtube_label', $label_text, $instance['label_length']);
	}

	/**
	 * Should widget custom #01 being used
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_custom_01(&$instance)
	{
		return (!empty($this->feature_map['custom_01']) && !empty($instance['custom_01']));
	}

	/**
	 * Print widget custom #01 text
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_custom_01(&$instance)
	{
		echo $instance['custom_01'];
	}

	/**
	 * Print widget custom link #01
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_custom_01_link(&$instance)
	{
		echo $instance['custom_01_link'];
	}

	/**
	 * Print widget custom link #01 target
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_custom_01_target(&$instance)
	{
		echo (!empty($instance['custom_01_new_window'])) ? '_blank' : '_top';
	}

	/**
	 * Print widget custom #01 label
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_custom_01_label(&$instance)
	{
		$label_text = '';
		switch ($instance['label_length']) 
		{
			case 'short':
				$label_text = _x('Custom 01:', 'short', $this->get_widget_text_domain());
				break;
			case 'long':
				$label_text = _x('Custom 01:', 'long', $this->get_widget_text_domain());
				break;
		}
		echo apply_filters($this->get_widget_slug() . '_custom_01_label', $label_text, $instance['label_length']);
	}

	/**
	 * Should widget custom #02 being used
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_custom_02(&$instance)
	{
		return (!empty($this->feature_map['custom_02']) && !empty($instance['custom_02']));
	}

	/**
	 * Print widget custom #02 text
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_custom_02(&$instance)
	{
		echo $instance['custom_02'];
	}

	/**
	 * Print widget custom link #02
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_custom_02_link(&$instance)
	{
		echo $instance['custom_02_link'];
	}

	/**
	 * Print widget custom link #02 target
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_custom_02_target(&$instance)
	{
		echo (!empty($instance['custom_02_new_window'])) ? '_blank' : '_top';
	}

	/**
	 * Print widget custom #02 label
	 *
     * @since  1.3.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_custom_02_label(&$instance)
	{
		$label_text = '';
		switch ($instance['label_length']) 
		{
			case 'short':
				$label_text = _x('Custom 02:', 'short', $this->get_widget_text_domain());
				break;
			case 'long':
				$label_text = _x('Custom 02:', 'long', $this->get_widget_text_domain());
				break;
		}
		echo apply_filters($this->get_widget_slug() . '_custom_02_label', $label_text, $instance['label_length']);
	}

	/**
	 * Compare with widget thumbnail template
	 *
     * @since  1.0.0
     *
     * @param  array  $instance 
     * @param  string $template
	 * @return bool
	 */
	public function is_template(&$instance, $template)
	{
		return ($template == (!empty($instance['template']) ? $instance['template'] : 'default'));
	}

	/**
	 * Retrieve list with custum widget templates
	 *
     * @since  1.0.0
     *
     * @return array - with template object[name,label]
	 */
	public function get_custom_template_list()
	{
		$custom_templates = array();
		$custom_templates = apply_filters($this->get_widget_slug() . '_template_list', $custom_templates);
		return $custom_templates;
	}

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Initialize the Widget
	 *
     * @since  1.1.0
     *
     * @return void
 	 */
	public function init() 
	{
		$this->feature_map = apply_filters($this->get_widget_slug() . '_feature_map', array(
			'phone' => true,
			'email' => true,
			'facebook' => true,
			'youtube' => true,
			'custom_01' => false,
			'custom_02' => false)
		);
	}

	/**
	 * Registers and enqueues admin-specific styles.
	 *
     * @since  1.0.0
     *
     * @return void
 	 */
	public function register_admin_styles($hook) 
	{
		if ('widgets.php' == $hook) 
		{
    	}
	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 *
     * @since  1.0.0
     *
     * @param  string $hook
     * @return void
 	 */
	public function register_admin_scripts($hook) 
	{
		if ('widgets.php' == $hook) 
		{
		}
	}

	/**
	 * Registers and enqueues JavaScript.
	 *
     * @since  1.0.0
     *
     * @return void
 	 */
	public function register_scripts() 
	{
	}

	/**
     * Setup a number of default variables used throughout the plugin
     *
     * @since  1.0.0
     *
     * @return void
     */
	public function setup_defaults() 
	{
	}

	/*--------------------------------------------------*/
	/* Helper Functions
	/*--------------------------------------------------*/

	/**
     * Apply internal text filters
     *
     * @since  1.0.0
     *
     * @param string
     * @return string
     */
	protected function _apply_text_filters($input)
	{
		if (!empty($input))
		{
			$input = str_replace(array('[-]','[ ]'), array('&shy;','&nbsp;'), $input);
		}
		return $input;
	}

	/**
     * Apply internal phone filters
     *
     * @since  1.2.0
     *
     * @param string
     * @return string
     */
	protected function _apply_phone_filters($input)
	{
		if (!empty($input))
		{
			$input = str_replace(array('[-]','[ ]'), array('&shy;','&nbsp;'), $input);
		}
		return $input;
	}

	/**
     * Apply internal phone link filters
     *
     * @since  1.2.0
     *
     * @param string
     * @return string
     */
	protected function _apply_phone_link_filters($input)
	{
		if (!empty($input))
		{
			$input = str_replace(array('[-]','[ ]','(0)','/','-',' '), '', $input);
		}
		return $input;
	}
}

