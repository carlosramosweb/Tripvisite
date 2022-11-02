<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wp_tripvisite_settings_callback() { 
    if( ! get_option( 'wp_options_location_id' ) ) {
        update_option( 'wp_options_location_id', '1994886' );
    }
    
	$message = "";
	if( isset( $_REQUEST['_wpnonce'] ) && isset( $_REQUEST['_update'] )) {		
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, "wp-tripvisite-update" ) ) {
			$message = "error";
			
		} else if ( empty($_REQUEST['_update']) ) {
			$message = "error";
			
		} else {
            update_option( 'wp_options_location_id', $_POST['wp_options_location_id'] );
            update_option( 'wp_options_content_help', $_POST['wp_options_content_help'] );
		}
	}
?>
<!----->
<div id="wpwrap">
<!--start-->
    <h1><?php echo __( 'Configurações Tripvisite', 'wp-tripvisite' ) ; ?></h1>
    
    <?php if( isset( $message ) ) { ?>
        <div class="wrap">
    	<?php if( $message == "updated" ) { ?>
            <div id="message" class="updated notice is-dismissible" style="margin-left: 0px;">
                <p><?php echo __( 'Atualizações feita com sucesso!', 'wp-tripvisite' ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php echo __( 'Dispensar este aviso.', 'wp-tripvisite' ); ?>
                    </span>
                </button>
            </div>
            <?php } ?>
            <?php if( $message == "error" ) { ?>
            <div id="message" class="updated error is-dismissible" style="margin-left: 0px;">
                <p><?php echo __( 'Erro! Não conseguimos fazer as atualizações!', 'wp-tripvisite' ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php echo __( 'Dispensar este aviso.', 'wp-tripvisite' ); ?>
                    </span>
                </button>
            </div>
        <?php } ?>
    	</div>
    <?php } ?>
    <!----->
    <div class="wrap">
        <hr/>
        <form action="" id="pesquisar-comentarios" method="post" enctype="application/x-www-form-urlencoded">
            <input type="hidden" name="action_tripvisite_cron" value="1">
            <a href="javascript:;" class="button button-start" onClick="document.getElementById('pesquisar-comentarios').submit();">
            	<span class="dashicons dashicons-search" style="line-height: 1.4;"></span>
                 Pesquisar mais comentários
            </a>
        </form>
        <span style="clear: both;"></span>
        <hr/>
    </div>
    <!---->
    <div class="wrap woocommerce">
        <nav class="nav-tab-wrapper wc-nav-tab-wrapper">
       		<a href="#" class="nav-tab nav-tab-active">
				<?php echo __( 'Configurações', 'wp-tripvisite' ) ; ?>
            </a>
        </nav>
        
    	<form method="post" id="mainform" name="mainform" enctype="multipart/form-data">
            <input type="hidden" name="_update" value="1">
            <input type="hidden" name="_wpnonce" value="<?php echo esc_attr(wp_create_nonce( 'wp-tripvisite-update' )); ?>">
            <!---->
            <table class="form-table">
                <tbody>
                    <!---->
                    <tr valign="top">
                        <th scope="row">
                            <label>
                                <?php echo __( 'ID da localização', 'wp-tripvisite' ) ; ?>
                            </label>
                        </th>
                        <td>
                            <label>
                                <input type="number" name="wp_options_location_id" value="<?php echo esc_attr(get_option( 'wp_options_location_id' )); ?>">
                            </label>
                       </td>
                    </tr> 
                </tbody>
            </table>
            
            <hr/>
            <p><?php echo __( 'Lista de apoio', 'wp-tripvisite' ) ; ?></p>
            <p>
            	<textarea name="wp_options_content_help" style="width: 100%; height: 300px;"><?php echo esc_attr(get_option( 'wp_options_content_help' )); ?></textarea>
            </p>
            
                <hr/>
                <div class="submit">
                    <button class="button-primary" type="submit"><?php echo __( 'Salvar Alterações', 'wp-tripvisite' ) ; ?></button>
                </div>
        </form>
    </div>
</div>
<?php
}