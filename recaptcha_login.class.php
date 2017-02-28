<?php

class recaptcha_login extends recaptcha {
  public static function init() {
    if(isset(self::$recaptcha_options['recaptcha_login']) && self::$recaptcha_options['recaptcha_login'] == 'yes') {
      //
      add_action('login_form',array(__CLASS__,'display_recaptcha'));

      //
      add_action('wp_authenticate_user',array(__CLASS__,'validate_recaptcha'),10,2);
    }
  }

  /**
   * //
   * @param $user string login username
   * @param $password string login password
   *
   * @return WP_Error|WP_user
   */
  public static function validate_recaptcha($user,$password) {
    if(!isset($_POST['g-recaptcha-response']) || !self::verify_recaptcha()) {
      return new WP_Error('empty_recaptcha','Error!');
    }
    return $user;
  }
}
