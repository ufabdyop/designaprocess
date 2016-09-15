<?php
$jwt_path = realpath(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/vendor/autoload.php');
require_once($jwt_path);

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

$key_path = realpath(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/nanofab-sso-sign-key.php');


class Nanofab_SSO_Plugin {
    /**
     * If a valid, signed JWT token is passed in a cookie with key "nanofab_jwt"
     * This returns the username, otherwise NULL
     * */
    public static function get_jwt_username() {
      $token = Nanofab_SSO_Plugin::get_jwt();
      if ($token) {
          return $token->getClaim('username');
      } else {
          return NULL;
      }
    }

    public static function get_jwt_user() {
      try{
        $token = Nanofab_SSO_Plugin::get_jwt();
        $user = array();
        if ($token) {
          $user['username'] = $token->getClaim('username');
          $user['firstname'] = $token->getClaim('firstname');
          $user['lastname'] = $token->getClaim('lastname');
          $user['email'] = $token->getClaim('email');
          $user['staff'] = $token->getClaim('staff');
          return $user;
        } else {
          return NULL;
        }
      } catch (Exception $e) {
      }
      return NULL;
    }

    public function get_jwt() {
      $token = NULL;
      try {
        if (isset($_COOKIE['nanofab_jwt'])) {
          $token = $_COOKIE['nanofab_jwt'];
          $token = (new Parser())->parse((string) $token);
          $username = $token->getClaim('username');
          $jti = $token->getHeader('jti');
          if (!Nanofab_SSO_Plugin::validate_jwt($token)) {
            $token = NULL;
          }
        }
      } catch (OutOfBoundsException $e) {
      }
      return $token;
    }

    public function validate_jwt($token) {
      $data = new ValidationData();
      $data->setCurrentTime(time());
      $valid = $token->validate($data);

      $signer = new Sha256();
      $verified = $token->verify($signer, NANOFAB_SSO_SIGN_KEY);

      return $verified && $valid;
    }

    public function check_jwt() {
      $username = Nanofab_SSO_Plugin::get_jwt_username();
      if ($username) {
        echo "Username is $username\n";
      } else {
        echo "No username through jwt\n";
      }
    }
}
