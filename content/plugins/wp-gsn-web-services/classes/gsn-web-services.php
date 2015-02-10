<?php

class Gsn_Web_Services extends Gsn_Plugin_Base {

	private $plugin_title, $plugin_menu_title, $plugin_permission, $client;

	const SETTINGS_KEY = 'gsn_settings';

	function __construct( $plugin_file_path ) {

    $this->cache_duration = 120;
		$this->plugin_slug = 'gsn-web-services';

		parent::__construct( $plugin_file_path );

		do_action( 'gsn_init', $this );

		if ( is_admin() ) {
			do_action( 'gsn_admin_init', $this );
		}

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		$this->plugin_permission = 'manage_options';
		$this->plugin_title      = __( 'Gsn Web Services', 'gsn-web-services' );
		$this->plugin_menu_title = __( 'GSN', 'gsn-web-services' );

		load_plugin_textdomain( 'gsn-web-services', false, dirname( plugin_basename( $plugin_file_path ) ) . '/languages/' );
	}

	/**
	 * Add the GSN menu item and sub pages
	 */
	function admin_menu() {
		if ( version_compare( $GLOBALS['wp_version'], '3.8', '<' ) ) {
			$icon_url = plugins_url( 'assets/img/icon16.png', $this->plugin_file_path );
		} else {
			$icon_url = false;
		}

		$hook_suffixes = array();
		$hook_suffixes[] = add_menu_page( $this->plugin_title, $this->plugin_menu_title, $this->plugin_permission, $this->plugin_slug, array(
				$this,
				'render_page',
			), $icon_url );

    $title           = __( 'Tools', 'Tools' );
		$hook_suffixes[] = $this->add_page( $title, $title, $this->plugin_permission, 'gsn-tools', array(
				$this,
				'render_page',
			) );

		$title           = __( 'Addons', 'Add-ons' );
		$hook_suffixes[] = $this->add_page( $title, $title, $this->plugin_permission, 'gsn-addons', array(
				$this,
				'render_page',
			) );

    $title           = __( 'About Us', 'About-Us' );
		$hook_suffixes[] = $this->add_page( $title, $title, $this->plugin_permission, 'gsn-aboutus', array(
				$this,
				'render_page',
			) );

		global $submenu;
		if ( isset( $submenu[ $this->plugin_slug ][0][0] ) ) {
			$submenu[ $this->plugin_slug ][0][0] = __( 'Settings', 'Tools', 'Add-ons', 'About-Us' );
		}

		do_action( 'gsn_admin_menu', $this );

		foreach ( $hook_suffixes as $hook_suffix ) {
			add_action( 'load-' . $hook_suffix, array( $this, 'plugin_load' ) );
		}

		if ( $icon_url === false ) {
			add_action( 'admin_print_styles', array( $this, 'enqueue_menu_styles' ) );
		}
	}

	/**
	 * Add sub page to the GSN menu item
	 *
	 * @param        $page_title
	 * @param        $menu_title
	 * @param        $capability
	 * @param        $menu_slug
	 * @param string $function
	 *
	 * @return bool|string
	 */
	function add_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
		return add_submenu_page( $this->plugin_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	}

	/**
	 * Load styles for the GSN menu item
	 */
	function enqueue_menu_styles() {
		$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : $this->plugin_version;
		$src     = plugins_url( 'assets/css/global.css', $this->plugin_file_path );
		wp_enqueue_style( 'gsn-global-styles', $src, array(), $version );
	}

	/**
	 * Plugin loading enqueue scripts and styles
	 */
	function plugin_load() {
		$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : $this->plugin_version;

		$src = plugins_url( 'assets/css/styles.css', $this->plugin_file_path );
		wp_enqueue_style( 'gsn-styles', $src, array(), $version );

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$src = plugins_url( 'assets/js/script' . $suffix . '.js', $this->plugin_file_path );
		wp_enqueue_script( 'gsn-script', $src, array( 'jquery' ), $version, true );

		if ( isset( $_GET['page'] ) && 'gsn-addons' == sanitize_key( $_GET['page'] ) ) { // input var okay
			add_filter( 'admin_body_class', array( $this, 'admin_plugin_body_class' ) );
			wp_enqueue_script( 'plugin-install' );
			add_thickbox();
		}

		$this->handle_post_request();

		do_action( 'gsn_plugin_load', $this );
	}

	/**
	 * Process the saving of the settings form
	 */
	function handle_post_request() {
		if ( empty( $_POST['action'] ) || 'save' != sanitize_key( $_POST['action'] ) ) { // input var okay
			return;
		}

		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'gsn-save-settings' ) ) { // input var okay
			die( __( "Denied due to possible cross-site scripting/xss.", 'gsn-web-services' ) );
		}

		// Make sure $this->settings has been loaded
		$this->get_settings();

		$post_vars = array( 'site_id', 'client_secret' );
		foreach ( $post_vars as $var ) {
			if ( ! isset( $_POST[ $var ] ) ) { // input var okay
				continue;
			}

			$value = sanitize_text_field( $_POST[ $var ] ); // input var okay

			if ( 'client_secret' == $var && '-- not shown --' == $value ) {
				continue;
			}

			$this->set_setting( $var, $value );
		}

		$this->save_settings();
	}

	/**
	 * Adds a class to admin page to style thickbox the same as the plugin directory pages.
	 *
	 * @param $classes
	 *
	 * @return string
	 */
	function admin_plugin_body_class( $classes ) {
		$classes .= 'plugin-install-php';

		return $classes;
	}

	/**
	 * Render the output of a page
	 */
	function render_page() {
		if ( empty( $_GET['page'] ) ) { // input var okay
			// Not sure why we'd ever end up here, but just in case
			wp_die( 'What the heck are we doing here?' );
		}
		$view = 'settings';
		if ( preg_match( '@^gsn-(.*)$@', $_GET['page'], $matches ) ) {
			$allowed = array( 'addons', 'tools', 'aboutus' );
			if ( in_array( $matches[1], $allowed ) ) {
				$view = $matches[1];
			}
		}

		$this->render_view( 'header' );
		$this->render_view( $view );
		$this->render_view( 'footer' );
	}

	/**
	 * Check if we are using constants for the GSN access credentials
	 *
	 * @return bool
	 */
	function are_key_constants_set() {
		return defined( 'GSN_API_BASE_URL' ) && defined( 'GSN_SITE_ID' ) && defined( 'GSN_CLIENT_SECRET' );
	}

  /**
	 * Get the GSN api base url from a constant or the settings
	 *
	 * @return string
	 */
	public function get_api_base_url() {
		if ( $this->are_key_constants_set() ) {
			return rtrim(GSN_API_BASE_URL, '/');
		}

    $apiUrl = $this->get_setting( 'api_base_url' );
    if (defined($apiUrl)) {
      return rtrim($apiUrl, '/');
    }
		return 'https://clientapi.gsn2.com/api/v1';
	}

	/**
	 * Get the GSN id from a constant or the settings
	 *
	 * @return string
	 */
	public function get_site_id() {
		if ( $this->are_key_constants_set() ) {
			return GSN_SITE_ID;
		}

		return $this->get_setting( 'site_id' );
	}

	/**
	 * Get the GSN secret from a constant or the settings
	 *
	 * @return string
	 */
	public function get_client_secret() {
		if ( $this->are_key_constants_set() ) {
			return GSN_CLIENT_SECRET;
		}

		return $this->get_setting( 'client_secret' );
	}

	/**
	 * Instantiate a new GSN service client for the GSN SDK
	 * using the defined GSN id and secret
	 *
	 * @return Gsn|WP_Error
	 */
	public function get_client() {
		if (! $this->get_site_id() || ! $this->get_client_secret() ) {
			return new WP_Error( 'gsn_api_config_missing', sprintf( __( 'You must first <a href="%s">set your GSN Web Services config</a> to use this add-on.', 'gsn-web-services' ), 'admin.php?page=' . $this->plugin_slug ) ); // xss ok
		}

		if ( is_null( $this->client ) ) {
			$this->client = new GsnApiClient($this->get_api_base_url(), $this->get_site_id(), $this->get_client_secret());
      $this->client->authenticate();
		}

		return $this->client;
	}

  /**
	 * Get the profile id
	 *
	 * @return string
	 */
  public function get_profile_id() {
    $result = $_COOKIE['ProfileId'];
    if (!isset($result)) {
       $result = $this->get_anonymous_profile_id();
    }

    return $result;
  }

  /**
	 * Set the profile id
	 */
  public function set_profile_id($value) {
    setcookie('ProfileId', $value, strtotime('+365 day'));
  }

  /**
	 * Get the anonymous profile id
	 *
	 * @return string
	 */
  public function get_anonymous_profile_id() {
    return $_COOKIE['AnonymousProfileId'];
  }

  /**
	 * Set the anonymous profile id
	 */
  public function set_anonymous_profile_id($value) {
    setcookie('AnonymousProfileId', $value, strtotime('+365 day'));
  }

  /**
	 * Get the store id
	 *
	 * @return string
	 */
  public function get_store_id() {
    $result = $_COOKIE['StoreId'];
    return $result;
  }

  /**
	 * Set the store id
	 */
  public function set_store_id($result) {
    setcookie('StoreId', $result, strtotime('+365 day'));
  }

  /**
	 * Get the shopping list id
	 *
	 * @return string
	 */
  public function get_shopping_list_id() {
    $result = $_COOKIE['ShoppingListId'];
    return $result;
  }

  /**
	 * Set the shopping list id
	 */
  public function set_shopping_list_id($result) {
    setcookie('ShoppingListId', $result, strtotime('+365 day'));
  }

  /**
	 * Get the client basic configuration (Name, FacebookId, WelcomeEmailFrom, etc..)
	 *
	 * @return string
	 */
  public function get_site_config( $force = false ) {
    $result = $this->get_client()->get("/store/siteconfig/".$this->get_site_id(), array(), array(), $force ? 0 : $this->cache_duration);
    return $result;
  }

  /**
	 * Get all client settings
	 *
	 * @return string
	 */
  public function get_site_settings( $force = false ) {
    $result = $this->get_client()->get("/settings/all/".$this->get_site_id(), array(), array(), $force ? 0 : $this->cache_duration);
    return $result;
  }

  /**
	 * Get partial contents by partial name.  This retrieve by current site and store id.
	 *
	 * @return string
	 */
  public function get_partial_contents( $partialname, $force = false ) {
    $result = $this->get_client()->get("/content/getpartialcontents/".$this->get_site_id()."/".$this->get_store_id().$partialname, array(), array(), $force ? 0 : $this->cache_duration);
    return $result;
  }

  /**
	 * Get partial contents by meta-data by name.  This retrieve by current site and store id.
	 *
	 * @return string
	 */
  public function get_partial_meta( $partialname, $force = false ) {
    $result = $this->get_client()->get("/content/getpartialmetadata/".$this->get_site_id()."/".$this->get_store_id().$partialname, array(), array(), $force ? 0 : $this->cache_duration);
    return $result;
  }

  /**
	 * Get the home slideshow content
	 *
	 * @return string
	 */
  public function get_home_slideshow( $force = false ) {
    return $this->get_partial_conents("Home%20Slideshow", $force);
  }

  /**
	 * Get the home page content
	 *
	 * @return string
	 */
  public function get_home_content( $force = false ) {
    return $this->get_partial_conents("Home%20Page", $force);
  }

  /**
	 * Get the home page content
	 *
	 * @return string
	 */
  public function get_home_meta( $force = false ) {
    return $this->get_partial_meta("Home%20Page", $force);
  }

  /**
	 * Get the store content: https://clientapi.gsn2.com/Help/Api/GET-api-v1-store-AllContent-storeId
	 *
	 * @return string
	 */
  public function get_store_contents( $force = false ) {
    $result = $this->get_client()->get("/store/allcontent/".$this->get_store_id(), array(), array(), $force ? 0 : $this->cache_duration);
    return $result;
  }

  /**
	 * Get a list of all store
	 *
	 * @return string
	 */
  public function get_store_list( $force = false ) {
    $result = $this->get_client()->get("/store/list/".$this->get_site_id(), array(), array(), $force ? 0 : $this->cache_duration);
    return $result;
  }

	/**
	 * Get a nonced, network safe install URL for a plugin
	 *
	 * @param $slug Plugin slug
	 *
	 * @return string
	 */
	public function get_plugin_install_url( $slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug );
	}
}
