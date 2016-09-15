<?php

$jwt_path = realpath(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/jwt/index.php');
require_once($jwt_path);

function nanofab_sso_debug($str) {
    $tmp_path = realpath(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/tmp');
    $fh = fopen("$tmp_path/log.txt", 'a');
    fwrite($fh, $str . "\n");
    fclose($fh);
}

function nanofab_sso_auth( $user, $username, $password ){
    $jwt_user = Nanofab_SSO_Plugin::get_jwt_user();
    nanofab_sso_debug("Called: " . 
                        print_r($user, TRUE) . 
                        " - $username ");

    // Make sure a username and password are present for us to work with
    if($username == '' && $password == 'NANOFABJWT') {

        if( $jwt_user ) {
            $username = $jwt_user['username'];
            // External user exists, try to load the user info from the WordPress user table
            $userobj = new WP_User();
            $user = $userobj->get_data_by( 'login', $username ); // Does not return a WP_User object :(
            $user = new WP_User($user->ID); // Attempt to load up the user with that ID

            if( $user->ID == 0 ) {
                // The user does not currently exist in the WordPress user table.
                // You have arrived at a fork in the road, choose your destiny wisely

                // If you do not want to add new users to WordPress if they do not
                // already exist uncomment the following line and remove the user creation code
                //$user = new WP_Error( 'denied', __("ERROR: Not a valid user for this system") );

                // Setup the minimum required user information for this example
                $userdata = array( 'user_email' => $jwt_user['email'],
                                   'user_login' => $username,
                                   'first_name' => $jwt_user['firstname'],
                                   'last_name' => $jwt_user['lastname']
                                   );
                $new_user_id = wp_insert_user( $userdata ); // A new user has been created

                if ($jwt_user['staff']) {
                   wp_update_user( array ('ID' => $new_user_id, 'role' => 'administrator' ) ) ;
                }

                // Load the new user info
                $user = new WP_User ($new_user_id);
            }
        } else {
                // User does not exist,  send back an error message
           $user = new WP_Error( 'denied', __("ERROR: User/pass bad") );
        }
    }

     // Comment this line if you wish to fall back on WordPress authentication
     // Useful for times when the external service is offline
     // remove_action('authenticate', 'wp_authenticate_username_password', 20);

     return $user;
}

