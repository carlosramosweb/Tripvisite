<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="hcf_box" style="width: 100%;">
    <table class="form-table">
        <tbody>
            <!---->
            <tr valign="top">
                <th scope="row">
                    <label><?php echo __( 'Título', 'wp-tripvisite' ); ?></label>
                </th>
                <td><input type="text" name="wp_tripvisite_title" id="wp_tripvisite_title" size="70" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_tripvisite_title', true ) ); ?>"></td>
            </tr>
            <!---->
            <tr valign="top">
                <th scope="row">
                    <label><?php echo __( 'ID da localização', 'wp-tripvisite' ); ?></label>
                </th>
                <td><input type="number" name="wp_tripvisite_location_id" id="wp_tripvisite_location_id" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_tripvisite_location_id', true ) ); ?>"></td>
            </tr>
            <!---->
            <tr valign="top">
                <th scope="row">
                    <label><?php echo __( 'Nome do Autor', 'wp-tripvisite' ); ?></label>
                </th>
                <td><input type="text" name="wp_tripvisite_name" id="wp_tripvisite_name" size="50" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_tripvisite_name', true ) ); ?>"></td>
            </tr>
            <!---->
            <tr valign="top">
                <th scope="row">
                    <label><?php echo __( 'Login do Autor', 'wp-tripvisite' ); ?></label>
                </th>
                <td><input type="text" name="wp_tripvisite_username" id="wp_tripvisite_username" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_tripvisite_username', true ) ); ?>"></td>
            </tr>
            <!---->
            <tr valign="top">
                <th scope="row">
                    <label><?php echo __( 'Rating', 'wp-tripvisite' ); ?></label>
                </th>
                <td>
                    <?php $wp_tripvisite_rating = esc_attr( get_post_meta( get_the_ID(), 'wp_tripvisite_rating', true ) ); ?>
                    <select name="wp_tripvisite_rating" id="wp_tripvisite_rating">
                    	<option value="1" <?php if($wp_tripvisite_rating == "1" ) { echo "selected"; } ?>><?php echo __( '1 Estrela', 'wp-tripvisite' ); ?></option>
                    	<option value="2" <?php if($wp_tripvisite_rating == "2" ) { echo "selected"; } ?>><?php echo __( '2 Estrelas', 'wp-tripvisite' ); ?></option>
                    	<option value="3" <?php if($wp_tripvisite_rating == "3" ) { echo "selected"; } ?>><?php echo __( '3 Estrelas', 'wp-tripvisite' ); ?></option>
                    	<option value="4" <?php if($wp_tripvisite_rating == "4" ) { echo "selected"; } ?>><?php echo __( '4 Estrelas', 'wp-tripvisite' ); ?></option>
                    	<option value="5" <?php if($wp_tripvisite_rating == "5" ) { echo "selected"; } ?>><?php echo __( '5 Estrelas', 'wp-tripvisite' ); ?></option>
                    </select>
                </td>
            </tr>
            <!---->
            <tr valign="top">
                <th scope="row">
                    <label><?php echo __( 'Linguagem', 'wp-tripvisite' ); ?></label>
                </th>
                <td>
                    <?php $wp_tripvisite_lang = esc_attr( get_post_meta( get_the_ID(), 'wp_tripvisite_lang', true ) ); ?>
                    <select name="wp_tripvisite_lang" id="wp_tripvisite_lang">
                    	<option value="pt" <?php if($wp_tripvisite_lang == "pt" ) { echo "selected"; } ?>><?php echo __( 'Português', 'wp-tripvisite' ); ?></option>
                    	<option value="en" <?php if($wp_tripvisite_lang == "en" ) { echo "selected"; } ?>><?php echo __( 'Inglês', 'wp-tripvisite' ); ?></option>
                    	<option value="es" <?php if($wp_tripvisite_lang == "es" ) { echo "selected"; } ?>><?php echo __( 'Espanhol', 'wp-tripvisite' ); ?></option>
                    </select>
                </td>
            </tr>
            <!---->
            <tr valign="top">
                <th scope="row">
                    <label><?php echo __( 'Url', 'wp-tripvisite' ); ?></label>
                </th>
                <td>
                    <a class="preview button" href="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_tripvisite_url', true ) ); ?>" target="blank" style="float: none;"><?php echo __( 'Ver na Tripvisite', 'wp-tripvisite' ); ?></a>
                    <input type="hidden" name="wp_tripvisite_url" id="wp_tripvisite_url" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_tripvisite_url', true ) ); ?>">
                    <i style="vertical-align: sub;"><span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: middle;"></span><?php echo __( 'Vai abrir nova aba (Link Externo)', 'wp-tripvisite' ); ?></i>
                </td>
            </tr>
            <!---->
            <tr valign="top">
                <th scope="row">
                    <label><?php echo __( 'Data de Publicação', 'wp-tripvisite' ); ?></label>
                </th>
                <td><input type="text" name="wp_tripvisite_date" id="wp_tripvisite_date" value="<?php echo esc_attr( date( 'd/m/Y', strtotime( get_post_meta( get_the_ID(), 'wp_tripvisite_date', true ) ) ) ); ?>" disabled></td>
            </tr>
            <!---->
        </tbody>
    </table>
</div>
<?php
