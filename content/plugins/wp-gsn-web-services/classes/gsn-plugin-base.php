<?php

class Gsn_Plugin_Base {

	protected $plugin_file_path, $plugin_dir_path, $plugin_slug, $plugin_basename, $plugin_version;
  protected $cache_duration;
	private $settings;

	function __construct( $plugin_file_path ) {
		$this->plugin_file_path = $plugin_file_path;
		$this->plugin_dir_path  = rtrim( plugin_dir_path( $plugin_file_path ), '/' );
		$this->plugin_basename  = plugin_basename( $plugin_file_path );
		$this->plugin_version   = $GLOBALS['gsn_meta'][ $this->plugin_slug ]['version'];
    $this->cache_duration = 0;
	}

	/**
	 * Accessor for plugin version
	 *
	 * @return mixed
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}

	/**
	 * Accessor for plugin slug
	 *
	 * @return string
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}
  /**
	 * Accessor for plugin dir path
	 *
	 * @return string
	 */
  public function get_plugin_dir_path() {
		return $this->plugin_dir_path;
	}

  /**
	 * Allow for memcache of site options.
	 *
	 * @param bool $force
	 *
	 * @return array
	 */
  function get_option($option_key) {
    if (!isset($result)) {
     $result = get_option($option_key);
    }

    return $result;
  }

  /**
	 * Allow for memcache of site options.
	 *
	 * @param bool $force
	 *
	 * @return array
	 */
  function update_option($option_key, $option_value) {
		update_option( $option_key, $option_value );
  }

	/**
	 * Get the plugin's settings array
	 *
	 * @param bool $force
	 *
	 * @return array
	 */
	function get_settings( $force = false ) {
		if ( is_null( $this->settings ) || $force ) {
			$this->settings = get_option( static::SETTINGS_KEY );
		}

		return $this->settings;
	}

	/**
	 * Get a specific setting
	 *
	 * @param        $key
	 * @param string $default
	 *
	 * @return string
	 */
	function get_setting( $key, $default = '' ) {
		$this->get_settings();

		if ( isset( $this->settings[ $key ] ) ) {
			return $this->settings[ $key ];
		}

		return $default;
	}

	/**
	 * Delete a setting
	 *
	 * @param $key
	 */
	function remove_setting( $key ) {
		$this->get_settings();

		if ( isset( $this->settings[ $key ] ) ) {
			unset( $this->settings[ $key ] );
		}
	}

	/**
	 * Render a view template file
	 *
	 * @param       $view View filename without the extension
	 * @param array $args Arguments to pass to the view
	 */
	function render_view( $view, $args = array() ) {
		extract( $args );
		include $this->plugin_dir_path . '/view/' . $view . '.php';
	}

	/**
	 * Set a setting
	 *
	 * @param $key
	 * @param $value
	 */
	function set_setting( $key, $value ) {
		$this->settings[ $key ] = $value;
	}

	/**
	 * Bulk set the settings array
	 *
	 * @param array $settings
	 */
	function set_settings( $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Save the settings to the database
	 */
	function save_settings() {
		$this->update_option( static::SETTINGS_KEY, $this->settings );
	}
}
