<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wp_action_tripvisite_curl_cron', 'wp_action_tripvisite_curl_cron_callback' );

function wp_action_tripvisite_curl_cron_callback() {

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
    	CURLOPT_URL => "https://tripadvisor1.p.rapidapi.com/reviews/list?limit=12&currency=BRL&lang=pt_BR&location_id=".get_option( 'wp_options_location_id' )."", // 11991831, 1994886
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_FOLLOWLOCATION => true,
    	CURLOPT_ENCODING => "",
    	CURLOPT_MAXREDIRS => 12,
    	CURLOPT_TIMEOUT => 30,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => "GET",
    	CURLOPT_HTTPHEADER => array(
    		"x-rapidapi-host: tripadvisor1.p.rapidapi.com",
    		"x-rapidapi-key: 5ccbd85739mshb14307ce343b688p16b054jsnd80c84d9079a"
    	),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
    	echo "cURL Error #:" . $err;
    } else {
        $list_response = json_decode($response);
        if($list_response->data) {
        	foreach (array_reverse($list_response->data) as $list_value) {
        	    
                if ( ! function_exists( 'post_exists' ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/post.php' );
                }
        	    
        	    // Pesquisa se já tem ID igual
        	    $fount_post = post_exists( $list_value->id, '', '', '');

        	    if( ! $fount_post ) {
                    $post = array(
                        'post_title'    => esc_attr( $list_value->id ),
                        'post_excerpt'  => esc_attr( $list_value->text ),
                        'post_type'     => 'tripvisite',
                        'post_status'   => 'publish',
                    );
            	    $post_id = wp_insert_post($post);
            	    
            	    if( !empty( $list_value->title ) ) {
                        update_post_meta( $post_id, 'wp_tripvisite_title', esc_attr( $list_value->title ) );
            	    }
            	    
            	    if( !empty( $list_value->location_id ) ) {
                        update_post_meta( $post_id, 'wp_tripvisite_location_id', esc_attr( $list_value->location_id ) );
            	    }
            	    if( !empty( $list_value->user->name ) ) {
                        update_post_meta( $post_id, 'wp_tripvisite_name', esc_attr( $list_value->user->name ) );
                        
            	    } else if( !empty( $list_value->user->first_name ) ) {
                        update_post_meta( $post_id, 'wp_tripvisite_name', esc_attr( $list_value->user->first_name ) );
                        
            	    } else if( !empty( $list_value->user->username ) ) {
                        update_post_meta( $post_id, 'wp_tripvisite_name', esc_attr( $list_value->user->username ) );
                        
            	    }
            	    if( !empty( $list_value->user->username ) ) {
                        update_post_meta( $post_id, 'wp_tripvisite_username', esc_attr( $list_value->user->username ) );
            	    }
            	    if( !empty( $list_value->lang ) ) {
                        update_post_meta( $post_id, 'wp_tripvisite_lang', esc_attr( $list_value->lang ) );
            	    }
            	    if( !empty( $list_value->url ) ) {
                        update_post_meta( $post_id, 'wp_tripvisite_url', esc_attr( $list_value->url ) );
            	    }
            	    if( !empty( $list_value->published_date ) ) {
                        update_post_meta( $post_id, 'wp_tripvisite_date', esc_attr( substr( $list_value->published_date, 0, 10) ) );
            	    }
            	    
            	    // set categoria
            	    if( !empty( $list_value->rating ) ) {
            	        update_post_meta( $post_id, 'wp_tripvisite_rating', esc_attr( $list_value->rating ) );
            	    }
            	    // Set imagem
            	    if( !empty( $list_value->user->avatar->large->url ) ) {
                        // avatar
                        $image_url        = $list_value->user->avatar->large->url;
                        $image_name       = md5( $list_value->id . $list_value->title ) . substr($list_value->user->avatar->large->url, -4, 4);
                        $upload_dir       = wp_upload_dir();
                        $image_data       = file_get_contents($image_url);
                        $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
                        $filename         = basename( $unique_file_name );
                        
                        if( wp_mkdir_p( $upload_dir['path'] ) ) {
                            $file = $upload_dir['path'] . '/' . $filename;
                        } else {
                            $file = $upload_dir['basedir'] . '/' . $filename;
                        }
                        
                        file_put_contents( $file, $image_data );
                        
                        $wp_filetype = wp_check_filetype( $filename, null );
                        
                        $attachment = array(
                            'post_mime_type' => $wp_filetype['type'],
                            'post_title'     => sanitize_file_name( $filename ),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );
                        
                        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
                        wp_update_attachment_metadata( $attach_id, $attach_data );
                        set_post_thumbnail( $post_id, $attach_id );
                        // =>
            	    }
        	    }
            }
            /* end foreach */
        }
    }

}