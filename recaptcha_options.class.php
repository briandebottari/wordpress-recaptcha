<?php
ob_start();
class recaptcha_options {

  public static function init() {
    add_action('admin_menu',array(__CLASS__,'register_menu_page'));
  }

  //
  public static function register_menu_page() {
    add_menu_page(
      'reCAPTCHA',
      'reCAPTCHA',
      'manage_options',
      'recaptcha_config',
      array(__CLASS__,'recaptcha_options_page'),
      'dashicons-image-rotate'
    );
  }

  //
  public static function recaptcha_options_page() {
    //
    $recaptcha_options = get_option('recaptcha_options');
    $site_key = isset($recaptcha_options['site_key']) ? $recaptcha_options['site_key'] : '';
    $secret_key = isset($recaptcha_options['secret_key']) ? $recaptcha_options['secret_key'] : '';

    //
    $recaptcha_login = isset($recaptcha_options['recaptcha_login']) ? $recaptcha_options['recaptcha_login'] : '';
    $recaptcha_registration = isset($recaptcha_options['recaptcha_registration']) ? $recaptcha_options['recaptcha_registration'] : '';
    $recaptcha_comment = isset($recaptcha_options['recaptcha_comment']) ? $recaptcha_options['recaptcha_comment'] : '';

    //
    self::save_options();

    //
    ?>
    <style>
      input[type="text"]{
        width:600px;
      }
    </style>
    <div class="wrap">
      <h1><?php _e('reCAPTCHA','recaptcha_plugin'); ?></h1>

      <?php
      if(isset($_GET['settings-updated']) && ($_GET['settings-updated'])) {
        echo'<div id="message" class="updated"><p><strong>'.__('Indstillingerne er gemt','recaptcha_plugin').'</strong></p></div>';
      }
      ?>

      <form method="post">

        <div class="postbox">
          <div class="inside">
            <h2><span>Keys</span></h2>
            <table class="form-table">
              <tr>
                <th scope="row">
                  <label for="site_key"><?php _e('Site key','recaptcha_plugin'); ?></label>
                </th>
                <td>
                  <input id="site_key" type="text" name="recaptcha_options[site_key]" value="<?php echo $site_key; ?>">
                </td>
              </tr>
              <tr scope="row">
                <th>
                  <label for="secret_key"><?php _e('Secret key','recaptcha_plugin'); ?></label>
                </th>
                <td>
                  <input id="secret_key" type="text" name="recaptcha_options[secret_key]" value="<?php echo $secret_key; ?>">
                </td>
              </tr>
            </table>
            <p>
              <?php wp_nonce_field('recaptcha_options_nonce'); ?>
              <input class="button-primary" type="submit" name="settings_submit" value="<?php _e('Save all changes','recaptcha_plugin'); ?>">
            </p>
          </div>
        </div>

        <div class="postbox">
          <div class="inside">
            <h2><span>Settings</span></h2>
            <table class="form-table">
              <tr>
                <th scope="row">
                  <label for="recaptcha_login">Login form</label>
                </th>
                <td>
                  <input id="recaptcha_login" type="checkbox" name="recaptcha_options[recaptcha_login]" value="yes" <?php checked($recaptcha_login,'yes') ?>>
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="recaptcha_registration">Registration form</label>
                </th>
                <td>
                  <input id="recaptcha_registration" type="checkbox" name="recaptcha_options[recaptcha_registration]" value="yes" <?php checked($recaptcha_registration,'yes') ?>>
                </td>
              </tr>
              <tr>
                <th scope="row">
                  <label for="recaptcha_login">Comments form</label>
                </th>
                <td>
                  <input id="recaptcha_comment" type="checkbox" name="recaptcha_options[recaptcha_comment]" value="yes" <?php checked($recaptcha_comment,'yes') ?>>
                </td>
              </tr>
            </table>
            <p>
              <?php wp_nonce_field('recaptcha_options_nonce'); ?>
              <input class="button-primary" type="submit" name="settings_submit" value="<?php _e('Save all changes','recaptcha_plugin'); ?>">
            </p>
          </div>
        </div>
      </form>
    </div>
  <?php
  }

  public static function save_options() {
    if(isset($_POST['settings_submit']) && check_admin_referer('recaptcha_options_nonce','_wpnonce')) {

      $saved_options = $_POST['recaptcha_options'];
      //print ($saved_options);

      update_option('recaptcha_options',$saved_options);

      wp_redirect('?page=recaptcha_config&settings-updated=true');
      exit;
    }
  }
}
