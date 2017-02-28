<?php

class recaptcha_comment extends recaptcha{
  /** @var string recaptcha errors */
  private static $recaptcha_error;

  public static function init() {
    if(isset(self::$recaptcha_options['recaptcha_comment']) && self::$recaptcha_options['recaptcha_comment'] == 'yes') {
      //
      add_action('wp_head',array(__CLASS__,'recaptcha_js'));

      //
      add_action('comment_form',array(__CLASS__,'display_recaptcha'));

      //
      add_filter('preprocess_comment',array(__CLASS__,'validate_recaptcha_comment'));

      //
      add_filter('comment_post_redirect',array(__CLASS__,'redirect_failed_recaptcha_comment'),10,2);
    }
  }

  /**
   * //
   * @param $location string location you get redirected to after commenting
   * @param $comment object comment object
   *
   * @return string
   */
  public static function redirect_failed_recaptcha_comment($location,$comment) {
    if (!empty(self::$recaptcha_error)) {
      //
      wp_delete_comment(absint($comment->comment_ID));

      //
      $location = add_query_arg('recaptcha','failed',$location);

      //
      $deleted_comment_id = strstr($location,'#');
      $location = str_replace($deleted_comment_id, '#comments', $location);
    }
    return $location;
  }


  /**
   * //
   * @param $commentdata object comment object
   *
   * @return object
   */
  public static function validate_recaptcha_comment($commentdata) {
    if(!isset($_POST['g-recaptcha-response']) || !self::verify_recaptcha()) {
      self::$recaptcha_error = 'Not valid!';
    }
    return $commentdata;
  }
}
