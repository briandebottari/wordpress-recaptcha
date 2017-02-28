<?php

class recaptcha_registration extends recaptcha {
  public static function init() {
    if(isset(self::$recaptcha_options['recaptcha_registration']) && self::$recaptcha_options['recaptcha_registration'] == 'yes') {
      //
      add_action('register_form',array(__CLASS__,'display_recaptcha'));

      //
      add_action('registration_errors',array(__CLASS__,'validate_recaptcha_registration'),10,3);
    }
  }

  /**
   * //
   * @param $user string login username
   * @param $password string login password
   *
   * @return WP_Error|WP_user
   */
  public static function validate_recaptcha_registration($errors,$sanitized_user_login,$user_email) {
    if(!isset($_POST['g-recaptcha-response']) || !self::verify_recaptcha()) {
      $errors->add('failed_verification','Error!');
    }
    return $errors;
  }
}
