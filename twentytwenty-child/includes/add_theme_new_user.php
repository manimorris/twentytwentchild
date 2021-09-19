<?php

function add_theme_user () {
    // user's details:
    $username = 'wp-test';
    $password = '123456789';
    $email = 'wptest@elementor.com';
    $role = 'editor';
    
    // Does the user allredy exist? (can check also by username..)
    $user = get_user_by( 'email', $email );  
    $user_id = $user->ID;
    
    // if user dosent exsist then create one.
    if ( !$user_id ) {

        $user_id = wp_insert_user( array(
            'user_login' => $username ,
            'user_pass' => $password,
            'user_email'=> $email,
            'role' => $role    
        ));
    }
    
    // Disable the admin bar for theme user
    if( get_current_user_id() == $user_id ) {
        show_admin_bar( false );
    }
}

// run the function.
add_theme_user ();

?>