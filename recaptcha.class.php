<?php
class recaptcha{
  //
  /**
   * @var string recaptcha site key
   */
  static private $site_key;

  /**
   * @var string recaptcha secret key
   */
  static private $secret_key;

  //
  static protected $recaptcha_options;
  static protected $recaptcha_script_handle;
  static protected $recaptcha_textdomain;

  //
  public static function init() {
    //
    self::$recaptcha_options = get_option('recaptcha_options');
    self::$site_key = isset(self::$recaptcha_options['site_key']) ? self::$recaptcha_options['site_key'] : '';
    self::$secret_key = isset(self::$recaptcha_options['secret_key']) ? self::$recaptcha_options['secret_key'] : '';

    //
    self::$recaptcha_script_handle = 'g-recaptcha';
    self::$recaptcha_textdomain = 'recaptcha_plugin';

    //
    if((isset(self::$recaptcha_options['recaptcha_login']) && self::$recaptcha_options['recaptcha_login'] == 'yes') || (isset(self::$recaptcha_options['recaptcha_registration']) && self::$recaptcha_options['recaptcha_registration'] == 'yes')) {
      add_action('login_enqueue_scripts',array(__CLASS__,'recaptcha_js'));
      add_action('login_enqueue_scripts',array(__CLASS__,'recaptcha_custom_css'));
    }
  }

  //
  public static function recaptcha_js() {
    echo '<script src="https://www.google.com/recaptcha/api.js"></script>'."\r\n";
  }

  //
  public static function enqueue_recaptcha_js() {
    $src = 'https://www.google.com/recaptcha/api.js';

    //
    wp_enqueue_script(self::$recaptcha_script_handle,$src,false,false,true);
  }


  //
  public static function recaptcha_custom_css() {
    echo '<style type="text/css">#login{width:350px !important;}</style>'."\r\n";
  }


  //
  public static function display_recaptcha() {
    //
    if(isset($_GET['recaptcha']) && $_GET['recaptcha'] == 'failed') {
      echo 'Error!';
    }

    //
    echo '<div class="g-recaptcha" data-sitekey="'.self::$site_key.'" style="margin:1.6842em 0;"></div>';
  }


  /**
   * @return bool
   */
  public static function verify_recaptcha() {
    $response = isset($_POST['g-recaptcha-response']) ? esc_attr($_POST['g-recaptcha-response']) : '';
    $remote_ip = $_SERVER["REMOTE_ADDR"];

    //
    $request = wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret='.self::$secret_key.'&response='.$response.'&remoteip='.$remote_ip);

    //
    $response_body = wp_remote_retrieve_body($request);

    //
    $result = json_decode($response_body,true);

    return $result['success'];
  }


  //
  public static function recaptcha_activate() {
    //
    if(!current_user_can('activate_plugins')) {
      return;
    }
    $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
    check_admin_referer("activate-plugin_{$plugin}");

    $default_options = array(
      'recaptcha_registration' => 'no',
      'recaptcha_login' => 'no',
      'recaptcha_comment' => 'yes'
    );

    add_option('recaptcha_options',$default_options);
  }

  //
  public static function recaptcha_remove() {
    if(!current_user_can('activate_plugins')) {
      return;
    }

    delete_option('recaptcha_options');
  }
}
