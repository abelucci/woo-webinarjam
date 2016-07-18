<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function __webinarjam_list_webinars($api_key){
    $response=wp_remote_post('https://app.webinarjam.com/api/v2/webinars',
    array(
        'method'=>'POST',
        'body'=>array('api_key'=>$api_key)
    ));
    if ( is_wp_error( $response ) ) {
        return 'Unauthorized';
    } else {
        $body = $response['body'];
        if('Unauthorized'===$body) return 'Unauthorized';
        else $result= json_decode( $body );
        return isset($result->webinars)?$result->webinars:'Unauthorized';
    }
}

function __webinarjam_get_webinar_data($api_key,$webinar_id){
    $response=wp_remote_post('https://app.webinarjam.com/api/v2/webinar',
        array(
            'method'=>'POST',
            'body'=>array('api_key'=>$api_key,'webinar_id'=>$webinar_id)
        ));
    if ( is_wp_error( $response ) ) {
        return 'Unauthorized';
    } else {
        $body = $response['body'];
        if('Unauthorized'===$body) return 'Unauthorized';
        else $result= json_decode( $body );
        return isset($result->webinar)?$result->webinar:'Unauthorized';
    }
}

function __webinarjam_register_user_to_webinar($api_key,$webinar_id,$user,$schedule=0){
    $name=''; $email='';
    if(is_numeric($user)){
        $user=get_userdata($user);
    }
    if(isset($user->user_email) ){
        $email=$user->user_email;
        $name=(!empty($user->user_firstname))&& (!empty($user->user_lastname))?$user->user_firstname.' '.$user->user_lastname: $user->display_name;
        $response=wp_remote_post('https://app.webinarjam.com/api/v2/register',
            array(
                'method'=>'POST',
                'body'=>array(
                    'api_key'=>$api_key,
                    'webinar_id'=>$webinar_id,
                    'name'=>$name,
                    'email'=>$email,
                    'schedule'=>$schedule
                )
        ));
        if ( is_wp_error( $response ) ) {
            return false;
        } else {
            $body = $response['body'];
            if('Unauthorized'===$body) return false;
            else $result= json_decode( $body );
            return isset($result->user)?$result->user:false;
        }
    }
    return false; // if no right user or user id supplied
}