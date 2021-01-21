<?php
/**
 * Neuralab Branding setup.
 */

defined( 'ABSPATH' ) || exit;

/**
 * NRLB_Branding class.
 */
final class NRLB_Branding {
  /**
   * Instance of the current class, null before first usage.
   *
   * @var NRLB_Branding
   */
  protected static $instance = null;

  /**
   * Return class instance.
   *
   * @return NRLB_Branding
   */
  public static function get_instance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * Cloning is forbidden.
   */
  public function __clone() {
    return wp_die( 'Cloning is forbidden!' );
  }

  /**
   * Unserializing instances of this class is forbidden.
   */
  public function __wakeup() {
    return wp_die( 'Unserializing instances is forbidden!' );
  }

  /**
   * Class construct.
   */
  private function __construct() {
    $this->define_constants();
    $this->includes();
    $this->init_hooks();
    $this->update();
  }

  /**
   * Define plugin constants.
   */
  private function define_constants() {
    if ( ! defined( 'NRLB_BRANDING_DIR_PATH' ) ) {
      define( 'NRLB_BRANDING_DIR_PATH', plugin_dir_path( NRLB_BRANDING_ROOT_FILE ) );
    }

    if ( ! defined( 'NRLB_BRANDING_DIR_URL' ) ) {
      define( 'NRLB_BRANDING_DIR_URL', plugin_dir_url( NRLB_BRANDING_ROOT_FILE ) );
    }
  }

  /**
   * Add includes.
   */
  private function includes() {
    if ( file_exists( NRLB_BRANDING_DIR_PATH . 'vendor/autoload.php' ) ) {
      require NRLB_BRANDING_DIR_PATH . 'vendor/autoload.php';
    }
  }

  /**
   * Init plugin hooks.
   */
  private function init_hooks() {
    // Activation and deactivation hooks.
    register_activation_hook( NRLB_BRANDING_ROOT_FILE, [ $this, 'activate' ] );
    register_deactivation_hook( NRLB_BRANDING_ROOT_FILE, [ $this, 'deactivate' ] );

    // Plugin hooks.
    // Admin branding.
    add_action( 'admin_bar_menu', [ $this, 'admin_bar_remove_wp_logo' ], 100 );
    add_action( 'admin_bar_menu', [ $this, 'admin_bar_add_nrlb_logo' ], 10 );
    add_action( 'wp_before_admin_bar_render', [ $this, 'admin_bar_nrlb_logo_style' ], PHP_INT_MAX );
    add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ], PHP_INT_MAX );
    // Login branding.
    add_filter( 'login_headerurl', [ $this, 'login_header_url' ], PHP_INT_MAX );
    add_filter( 'login_headertext', [ $this, 'login_header_text' ], PHP_INT_MAX );
    add_action( 'login_enqueue_scripts', [ $this, 'login_logo_style' ], PHP_INT_MAX );
  }

  /**
   * Activate plugin.
   */
  public function activate() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
      return false;
    }
  }

  /**
   * Deactivate plugin.
   */
  public function deactivate() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
      return false;
    }
  }

  /**
   * Enable plugin updates from BitBucket repository.
   */
  public function update() {
    $update = Puc_v4_Factory::buildUpdateChecker(
      'https://github.com/Neuralab/Neuralab-Branding',
      NRLB_BRANDING_ROOT_FILE,
      'nrlb-branding'
    );
  }

  /**
   * Get Neuralab site URL.
   *
   * @return string
   */
  public function get_nrlb_url() : string {
    return 'https://www.neuralab.net';
  }

  /**
   * Get Neuralab site copy.
   *
   * @return string
   */
  public function get_nrlb_url_copy() : string {
    return 'a.neuralab.site';
  }

  /**
   * Get Neuralab site link.
   *
   * @return string
   */
  public function a_nrlb_site() : string {
    $url  = $this->get_nrlb_url();
    $copy = $this->get_nrlb_url_copy();

    return apply_filters( 'nrlb_branding_a_nrlb_site', '<a class="a-nrlb-site" target="_blank" rel="noopener" href="' . esc_url( $url ) . '">' . esc_html( $copy ) . '</a>', $url, $copy );
  }

  /**
   * Remove WordPress logo from admin bar.
   *
   * @param WP_Admin_Bar $wp_admin_bar Instance of WP_Admin_Bar class.
   */
  public function admin_bar_remove_wp_logo( WP_Admin_Bar $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'wp-logo' );
  }

  /**
   * Add Neuralab logo to admin bar.
   *
   * @param WP_Admin_Bar $wp_admin_bar Instance of WP_Admin_Bar class.
   */
  public function admin_bar_add_nrlb_logo( WP_Admin_Bar $wp_admin_bar ) {
    $wp_admin_bar->add_node(
      [
        'id'    => 'nrlb-logo',
        'title' => '<span class="ab-icon">' . file_get_contents( NRLB_BRANDING_DIR_PATH . '/assets/img/neuralab-logo-sm.svg' ) . '</span><span class="screen-reader-text">' . esc_html( $this->get_nrlb_url_copy() ) . '</span>', // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        'href'  => esc_url( $this->get_nrlb_url() ),
        'meta'  => [
          'target' => '_blank',
          'rel'    => 'noopener',
        ],
      ]
    );
  }

  /**
   * Style Neuralab logo in admin bar.
   */
  public function admin_bar_nrlb_logo_style() {
    ?>
    <style type="text/css">
      #wpadminbar #wp-admin-bar-nrlb-logo > .ab-item .ab-icon {
        margin-right: 0 !important;
      }
      #wpadminbar #wp-admin-bar-nrlb-logo > .ab-item .ab-icon svg {
        position: relative;
        top: 2px;
        display: block;
        float: left;
        width:  20px;
        height: 20px;
        fill: currentColor;
      }
    </style>
    <?php
  }

  /**
   * Edit admin footer text.
   *
   * @param  string $text
   * @return string
   */
  public function admin_footer_text( $text ) : string {
    $text = '<span id="footer-thankyou"><a href="' . esc_url( $this->get_nrlb_url() ) . '" target="_blank" rel="noopener">' . esc_html( $this->get_nrlb_url_copy() ) . '</a></span>';

    return $text;
  }

  /**
   * Edit login header URL.
   *
   * @param  string $login_header_url
   * @return string
   */
  public function login_header_url( $login_header_url ) : string {
    $login_header_url = esc_url( $this->get_nrlb_url() );

    return $login_header_url;
  }

  /**
   * Edit login header text.
   *
   * @param  string $login_header_text
   * @return string
   */
  public function login_header_text( $login_header_text ) : string {
    $login_header_text = esc_html( $this->get_nrlb_url_copy() );

    return $login_header_text;
  }


  /**
   * Style Neuralab logo on login screen.
   */
  public function login_logo_style() {
    ?>
    <style type="text/css">
      .login h1 a {
      background-image: url( <?php echo NRLB_BRANDING_DIR_URL . '/assets/img/neuralab-logo.svg'; ?> ) !important;
      background-repeat: no-repeat !important;
      background-size: 300px 40px !important;
      background-position: center !important;
      width: 100% !important;
    }
    </style>
    <?php
  }
}
