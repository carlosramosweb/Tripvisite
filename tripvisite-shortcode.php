<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wp_tripvisite_shortcode( $atts = "" ) { 
    global $post;
    date_default_timezone_set('America/Sao_Paulo');
    
    if( !empty( $atts['location_id'] ) ) {
        $location_id = explode(",", $atts['location_id']);
    	$tripvisites = get_posts(
    		array(
                'numberposts'       => 12,
                'posts_per_page'    => 12,
                'post_status'       => 'publish',
                'post_type'         => 'tripvisite',
                'meta_query' => array(
                    array(
                        'key'     => 'wp_tripvisite_location_id',
                        'value'   => $location_id,
                    )
                )
    		)
    	);
    } else {
    	$tripvisites = get_posts(
    		array(
    			'numberposts'       => 12,
    			'posts_per_page'    => 12,
    			'post_status'       => 'publish',
    			'post_type'         => 'tripvisite',
    		)
    	);  
    }
    
	if( $tripvisites ) {
	    $i = 1;
?>
<style>
#box-tripvisite { color:#0c1f40; width: 100%; margin: 0 auto; padding: 0px; max-width: 100%; }
#box-tripvisite .box-item-tripvisite { width: 32%; float: left; display: inline-block; padding:10px; margin:0; margin-bottom: 20px; }
#box-tripvisite .box-rating-tripvisite { display:inline-block; float:left; }
#box-tripvisite .icon-star { width:20px; }
#box-tripvisite .title-tripvisite { font-size: 20px; }
#box-tripvisite .text-tripvisite { font-size: 16px; line-height: 22px;}
#box-tripvisite .leia-mais-tripvisite { font-size: 16px; text-decoration:none; cursor:pointer; }
#box-tripvisite .leia-mais-tripvisite:hover { color:#333; }
#box-tripvisite .box-autor-tripvisite { margin-top: 20px; }
#box-tripvisite .featured-img-tripvisite { display: inline-table; float: left; width:60px; border-radius: 50%; }
#box-tripvisite .autor-tripvisite { display: inline-table; float: left; margin-left: 20px; }
#box-tripvisite .autor-name-tripvisite { font-size: 18px; margin-bottom: 0px; }
#box-tripvisite .date-tripvisite { font-size: 16px; color:#666; }

#box-tripvisite .clear-col-2 { display:none; }
#box-tripvisite .clear-col-3 { display:block; }

@media screen and (min-width: 0px) and (max-width: 600px) {
    #box-tripvisite .box-item-tripvisite { width: 100%; float: none; display: block; }
    #box-tripvisite .clear-col-2 { display:none; }
    #box-tripvisite .clear-col-3 { display:none; }
}

@media screen and (min-width: 601px) and (max-width: 800px) {
    #box-tripvisite .box-item-tripvisite { width: 48%; display: inline-table; }
    #box-tripvisite .clear-col-2 { display: block; }
    #box-tripvisite .clear-col-3 { display:none; }
}
</style>
<script>
function show_texto_tripvisite(post_id) {
	jQuery('.text-tripvisite-' + post_id).hide();
	jQuery('.text-mais-tripvisite-' + post_id).toggle();
	jQuery('.btn-recolher-' + post_id).show();
	jQuery('.btn-expandir-' + post_id).hide();
}
 
function hide_texto_tripvisite(post_id) {
	jQuery('.text-tripvisite-' + post_id).toggle();
	jQuery('.text-mais-tripvisite-' + post_id).hide();
	jQuery('.btn-recolher-' + post_id).hide();
	jQuery('.btn-expandir-' + post_id).show();
}
</script>
<div id="box-tripvisite">
    <?php foreach ( $tripvisites as $tripvisite ) { 
        $wp_tripvisite_title = esc_attr( get_post_meta( $tripvisite->ID, 'wp_tripvisite_title', true ) );
        $wp_tripvisite_rating = esc_attr( get_post_meta( $tripvisite->ID, 'wp_tripvisite_rating', true ) );
        $wp_tripvisite_name = esc_attr( get_post_meta( $tripvisite->ID, 'wp_tripvisite_name', true ) );
        $wp_tripvisite_date = esc_attr( get_post_meta( $tripvisite->ID, 'wp_tripvisite_date', true ) );
        $featured_img_url = get_the_post_thumbnail_url($tripvisite->ID, 'thumbnail'); 
        
        $wp_tripvisite_date = date_i18n( get_option( 'date_format' ), strtotime( $wp_tripvisite_date ) );
        
        if($wp_tripvisite_rating >= 3) {
    ?>
    <div class="box-item-tripvisite">
    	<div class="box-rating-tripvisite" title="<?php echo __( $wp_tripvisite_rating . ' Estrela(s)', 'wp-tripvisite' ); ?>">
        	<?php 
			for ($j = 1; $j <= 5; $j++) { 
				if($j <= $wp_tripvisite_rating) { ?>
    			<span class="icon-star-active">
                	<img src="<?php echo esc_url( plugins_url( '/images/star-rating-active.png', __FILE__ ) ); ?>" class="icon-star">
                </span>
            	<?php } else { ?>
            	<span class="icon-star-desactive">
                	<img src="<?php echo esc_url( plugins_url( '/images/star-rating-desactive.png', __FILE__ ) ); ?>" class="icon-star">
                </span>
                <?php } ?>
            <?php } ?>
        </div>
    	<h3 class="title-tripvisite"><?php echo $wp_tripvisite_title; ?></h3>
        <div class="text-tripvisite text-tripvisite-<?php echo $i; ?>">
        	<?php echo substr( esc_attr( $tripvisite->post_excerpt ), 0, 100 ); ?> [...]
        </div>
        <div class="text-tripvisite text-mais-tripvisite-<?php echo $i; ?>" style="display: none; ">
        	<?php echo esc_attr( $tripvisite->post_excerpt ); ?>
        </div>
        <a href="javascript:;" class="leia-mais-tripvisite btn-expandir-<?php echo $i; ?>" onClick="show_texto_tripvisite(<?php echo $i; ?>);"><?php echo __( 'Leia mais', 'wp-tripvisite' ); ?></a>
        <a href="javascript:;" class="leia-mais-tripvisite btn-recolher-<?php echo $i; ?>" onClick="hide_texto_tripvisite(<?php echo $i; ?>);" style="display: none; "><?php echo __( 'Recolher', 'wp-tripvisite' ); ?></a>
        <div class="box-autor-tripvisite">
       		<img src="<?php echo esc_url($featured_img_url); ?>" class="featured-img-tripvisite">
            <div class="autor-tripvisite">
        		<h5 class="autor-name-tripvisite"><?php echo $wp_tripvisite_name; ?></h5>
            	<div class="date-tripvisite"><?php echo $wp_tripvisite_date; ?></div>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <?php if($i % 2 == 0) { ?>
    <div style="clear:both; margin-top:20px;" class="clear-col-2"><hr/></div>
    <?php } ?>
    <?php if($i % 3 == 0) { ?>
    <div style="clear:both; margin-top:20px;" class="clear-col-3"><hr/></div>
    <?php } ?>
    <?php 
        $i++; 
        } 
    }
    ?>
    <div style="clear:both;"></div>
</div>
<?php
    }
} 
add_shortcode('tripvisite', 'wp_tripvisite_shortcode'); 
