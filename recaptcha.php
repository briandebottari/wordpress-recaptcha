<?php
/*
    Plugin Name: reCaptcha Plugin
    Description: Implementation of reCaptcha
    Author: Morten Sørensen
    Version: 0.2
 */

require_once dirname(__FILE__). '/recaptcha.class.php';
require_once dirname(__FILE__). '/recaptcha_registration.class.php';
require_once dirname(__FILE__). '/recaptcha_comment.class.php';
require_once dirname(__FILE__). '/recaptcha_login.class.php';
require_once dirname(__FILE__). '/recaptcha_options.class.php';

register_activation_hook(__FILE__,array('recaptcha','recaptcha_activate'));
register_uninstall_hook(__FILE__,array('recaptcha','recaptcha_remove'));

recaptcha::init();
recaptcha_registration::init();
recaptcha_comment::init();
recaptcha_login::init();
recaptcha_options::init();
