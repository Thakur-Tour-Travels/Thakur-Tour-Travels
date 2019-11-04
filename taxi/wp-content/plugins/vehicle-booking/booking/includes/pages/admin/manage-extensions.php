<?php
/**
 * This template is used to display the 'bookings' for admin / executive
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  manage_extensions
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'simontaxi_admin_menu_manage_extensions' );
function simontaxi_admin_menu_manage_extensions() {
    add_submenu_page( 'edit.php?post_type=vehicle', esc_html__( 'Manage Extensions', 'simontaxi' ),esc_html__( 'Manage Extensions', 'simontaxi' ),'manage_extensions','manage_extensions','manage_extensions' );
}

/**
 * Extensions Page
 *
 * Renders the Extensions page content.
 *
 * @since 2.0.0
 * @return void
 */
function manage_extensions() {
	if ( ! empty( $_GET['action'] ) && 'reset_cache' === $_GET['action'] ) {
		global $wpdb;
		/**
		 * It will delete only transients with name 'simontaxi_extensions'!
		 */
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_simontaxi_extensions_%'" );
		$redirect = admin_url( 'edit.php?post_type=vehicle&page=manage_extensions' );
		simontaxi_set_message( 'success', esc_html__( 'Products reset successfully', 'simontaxi' ) );
		wp_safe_redirect( $redirect );
		exit;
	}
    $extensions_tabs = apply_filters( 'simontaxi_extensions_tabs', 
		array( 'all' => esc_html__( 'All', 'simontaxi' ), 
			'simontaxi' => esc_html__( 'All Simontaxi', 'simontaxi' ), 
			'gateways' => esc_html__( 'Payment Gateways (Simontaxi)', 'simontaxi' ), 
			'extensions' => esc_html__( 'Extensions (Simontaxi)', 'simontaxi' ),
			'wpthemes' => esc_html__( 'Themes', 'simontaxi' ),
			'mobile' => esc_html__( 'Mobile', 'simontaxi' ),
			'otherproducts' => esc_html__( 'Other Products', 'simontaxi' ),
			) );
    $active_tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $extensions_tabs ) ? $_GET['tab'] : 'simontaxi';
    $activate = isset( $_GET['activate'] ) ?  $_GET['activate'] : '';
    if( '' !== $activate ) {
        simontaxi_activate_plugin( $activate . '/' . $activate . '.php' );
    }
	
	$base_url = SIMONTAXI_DEMO_SITE;
	$support_url = SIMONTAXI_PRODUCT_SITE;
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    ob_start(); ?>
	
<div class="wrap" id="simontaxi-extensions">
    <h1>
        <?php esc_html_e( 'Extensions for Simontaxi - Vehicle Booking', 'simontaxi' ); ?>
        <span>
            &nbsp;&nbsp;<a href="<?php echo $base_url; ?>product-category/plugins/" class="button-primary" target="_blank"><?php esc_html_e( 'Browse All Extensions', 'simontaxi' ); ?></a>
        </span>
    </h1>
    <table width="100%" class="simontaxi-extensions-layout">
		<tr>
			<td width="25%" align="center">
			<a href="<?php echo $base_url; ?>faq-new/" target="_blank" title="<?php esc_html_e( 'FAQs', 'simontaxi' ); ?>">
			<img src="<?php echo esc_url( SIMONTAXI_PLUGIN_URL . 'images/faq.png' );?>" class="attachment-showcase size-showcase wp-post-image" alt="<?php esc_html_e( 'FAQs', 'simontaxi' ); ?>" title="<?php esc_html_e( 'FAQs', 'simontaxi' ); ?>">
			</a>
			
			<a href="<?php echo $base_url; ?>faq-new/" target="_blank" title="<?php esc_html_e( 'FAQs', 'simontaxi' ); ?>"><h2><?php esc_html_e( 'FAQs', 'simontaxi' ); ?></h2></a></td>
			
			<td width="25%" align="center">
			<a href="<?php echo $base_url; ?>forums/" target="_blank" title="<?php esc_html_e( 'Forum', 'simontaxi' ); ?>">
			<img src="<?php echo esc_url( SIMONTAXI_PLUGIN_URL . 'images/forum.jpg' );?>" class="attachment-showcase size-showcase wp-post-image" alt="<?php esc_html_e( 'Forum', 'simontaxi' ); ?>" title="<?php esc_html_e( 'Forum', 'simontaxi' ); ?>" width="62" height="62">
			</a>
			
			<a href="<?php echo $base_url; ?>forums/" target="_blank" title="<?php esc_html_e( 'Forum', 'simontaxi' ); ?>"><h2><?php esc_html_e( 'Forum', 'simontaxi' ); ?></h2></a></td>
			
			<td align="center" width="25%">
			<a href="<?php echo $base_url; ?>documentation/" target="_blank" title="<?php esc_html_e( 'Documentation', 'simontaxi' ); ?>">
			<img src="<?php echo esc_url( SIMONTAXI_PLUGIN_URL . 'images/file.png' );?>" class="attachment-showcase size-showcase wp-post-image" alt="<?php esc_html_e( 'Documentation', 'simontaxi' ); ?>" title="<?php esc_html_e( 'Documentation', 'simontaxi' ); ?>" width="62" height="62">
			</a>
			
			<a href="<?php echo $base_url; ?>documentation/" target="_blank" title="<?php esc_html_e( 'Documentation', 'simontaxi' ); ?>"><h2><?php esc_html_e( 'Documentation', 'simontaxi' ); ?></h2></a></td>
			
			<td align="center" width="25%">
			<a href="<?php echo $support_url; ?>submit-ticket/" target="_blank" title="<?php esc_html_e( 'Suport', 'simontaxi' ); ?>">
			<img src="<?php echo esc_url( SIMONTAXI_PLUGIN_URL . 'images/support.jpg' );?>" class="attachment-showcase size-showcase wp-post-image" alt="<?php esc_html_e( 'Suport', 'simontaxi' ); ?>" title="<?php esc_html_e( 'Suport', 'simontaxi' ); ?>" width="62" height="62">
			</a>
			
			<a href="<?php echo $support_url; ?>submit-ticket/" target="_blank" title="<?php esc_html_e( 'Suport', 'simontaxi' ); ?>"><h2><?php esc_html_e( 'Suport', 'simontaxi' ); ?></h2></a></td>
		</tr>
	</table>
	<p><?php echo __( 'These extensions <em><strong>add functionality</strong></em> to your Simontaxi - Vehicle Booking System.', 'simontaxi' ); ?></p>
	<?php echo simontaxi_print_errors() ?>
	<?php
	$url = admin_url( 'admin.php?page=manage_extensions&action=reset_cache');
	?>
	<p><a href="<?php echo $url; ?>" class="button" style="margin-top:10px;"><?php echo esc_html__( 'Reset Cache', 'simontaxi' ); ?></a></p>
    <h2 class="nav-tab-wrapper">
        <?php
    foreach( $extensions_tabs as $tab_id => $tab_name ) {

        $tab_url = add_query_arg( array(
            'tab' => $tab_id
        ) );

        $active = $active_tab == $tab_id ? ' nav-tab-active' : '';

        echo '<a href="' . esc_url( $tab_url ) . '" class="nav-tab' . $active . '">';
        echo esc_html( $tab_name );
        echo '</a>';
    }
        ?>
    </h2>

        <div class="simontaxi-extensions-layout">


                <?php 
				$extensions = json_decode( simontaxi_get_extensions( $active_tab ) );
				$headers = json_decode( simontaxi_get_extensions_headers( $active_tab ), true );
				$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		// dd( $extensions );
				
				// dd( $headers );
    $count = 0;
	
    if ( ! empty( $extensions ) ) {
        foreach( $extensions as $product ) {
            // $price = $product->price;
			$price = $product->price_html;
			$title = $product->name;
			$permalink = $product->permalink;
            $count++;
                ?>

                <div class="simontaxi-extension">
                    <h3 class="simontaxi-extension-title"><?php echo $title;?></h3>
                    <a href="<?php echo esc_url( $permalink ); ?>" title="<?php echo esc_attr( $title );?>" target="_blank">
                        <?php
            $thumbnail = SIMONTAXI_PLUGIN_URL . 'images/logo.png';
            if( ! empty( $product->images ) ) {
                foreach( $product->images as $image ) {
					$thumbnail = $image->src;
					break;
				}
            } ?>
            <img src="<?php echo esc_url( $thumbnail );?>" class="attachment-showcase size-showcase wp-post-image" alt="" title="<?php echo esc_attr( $title );?>" width="319" height="319">
                    </a>

                    <p><?php
					$text = ( $product->short_description ) ? $product->short_description : $title;
					echo ( strlen( $text ) > 150 ) ? substr( $text, 0, 150) . '<a href="' . $permalink . '" target="_blank">...</a>' : $text;
					?></p>
                    <?php 
					$slug = str_replace('simontaxi-', '', $product->slug );
					if ( is_plugin_active( $slug . '/'.$slug.'.php' ) ) {
                echo __( '<span class="btn-extension-installed">Installed</span>', 'simontaxi' );
            } elseif( file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/' . $slug.'.php' ) ) { ?>
				<a href="<?php echo admin_url( 'edit.php?post_type=vehicle&page=manage_extensions&tab=' . $active_tab . '&activate=' . $slug );?>" class="button activate-now button-primary"><?php esc_html_e( 'Activate', 'simontaxi' );?></a>
				<?php } else { ?>                    
				<a href="<?php echo esc_url( $permalink );?>" target="_blank" class="btn-extension"><?php esc_html_e( 'Get this Extension', 'simontaxi' ); echo '&nbsp;&nbsp;' . $price; ?></a>
				<?php } ?>
                </div>

                <?php
        }
		
		$total_items = $headers['x-wp-total'];
		$total_pages = $headers['x-wp-totalpages'];
		if ( $page > 1 ) {
			$previous_page = simontaxi_get_bookingsteps_urls( 'manage_extensions' ) . '&tab=' . $active_tab . '&cpage=' . ( $page - 1 );
			$first_page = simontaxi_get_bookingsteps_urls( 'manage_extensions' ) . '&tab=' . $active_tab . '&cpage=1';
		} else {
			$previous_page = '';
			$first_page = '';
		}
		
		if ( $page < $total_pages ) {
			$next_page = simontaxi_get_bookingsteps_urls( 'manage_extensions' ) . '&tab=' . $active_tab . '&cpage=' . ( $page + 1 );
			$last_page = simontaxi_get_bookingsteps_urls( 'manage_extensions' ) . '&tab=' . $active_tab . '&cpage=' . $total_pages;
		} else {
			$next_page = '';
			$last_page = '';
		}
		
		
		?>
		<div class="tablenav-pages col-lg-12">
			<span class="displaying-num" style="display: -webkit-inline-box;"><?php echo $headers['x-wp-total']; ?> <?php esc_html_e( 'items', 'simontaxi' ); ?></span>
			<span class="pagination-links">
			<?php if ( ! empty( $first_page ) ) { ?>
			<a class="first-page" href="<?php echo esc_url( $first_page ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'First page', 'simontaxi' ); ?></span><span aria-hidden="true">«</span></a>
			<?php } else { ?>
			<span class="tablenav-pages-navspan" aria-hidden="true">«</span>
			<?php } ?>
			<?php if ( ! empty( $previous_page ) ) { ?>
			<a class="previous-page" href="<?php echo esc_url( $previous_page ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Previous page', 'simontaxi' ); ?></span><span aria-hidden="true">‹</span></a>
			<?php } else { ?>
			<span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
			<?php } ?>
			
			<span class="screen-reader-text"><?php esc_html_e( 'Current Page', 'simontaxi' ); ?></span>
			<span id="table-paging" class="paging-input"><span class="tablenav-paging-text"><?php echo $page; ?> <?php esc_html_e( 'of', 'simontaxi' ); ?> <span class="total-pages"><?php echo $headers['x-wp-totalpages']; ?></span></span></span>
			
			<?php if ( ! empty( $next_page ) ) { ?>
			<a class="next-page" href="<?php echo esc_url( $next_page ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Next page', 'simontaxi' ); ?></span><span aria-hidden="true">›</span></a>
			<?php } else { ?>
			<span aria-hidden="true" class="tablenav-pages-navspan">›</span>
			<?php } ?>
			
			<?php if ( ! empty( $next_page ) ) { ?>
			<a class="last-page" href="<?php echo esc_url( $last_page ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Last page', 'simontaxi' ); ?></span><span aria-hidden="true">»</span></a>
			<?php } else { ?>
			<span aria-hidden="true" class="tablenav-pages-navspan">»</span>
			<?php } ?>
			</span>
		</div>
		<?php
    }

    if ( 0 === $count ) {
        esc_html_e( 'No Plugins found', 'simontaxi' );
    }
                ?>
                <div class="clear"></div>
                <div class="simontaxi-extensions-footer">
                    <a href="<?php echo $base_url; ?>product-category/plugins/" class="button-primary" target="_blank"><?php esc_html_e( 'Browse All Extensions', 'simontaxi' ); ?></a>
                </div>

        </div><!-- #tab_container-->

</div>
<?php
    echo ob_get_clean();
}

add_action( 'simontaxi_activate_plugin', 'simontaxi_activate_plugin', 10, 1 );
function simontaxi_activate_plugin( $plugin ) {
    activate_plugin( $plugin );

    wp_redirect( admin_url('edit.php?post_type=vehicle&page=manage_extensions'), 301);
    exit;
}

function simontaxi_extensions_endpoint_urls( $key ) {
	$endpoints = array(
		'products' => SIMONTAXI_PRODUCT_SITE . 'wp-json/wc/v2/products',
		'productscount' => SIMONTAXI_PRODUCT_SITE . 'wp-json/wc/v2/products',
	);
	return $endpoints[ $key ];
}
function simontaxi_extensions_endpoint() {
	$url = SIMONTAXI_PRODUCT_SITE . 'wp-json/wc/v2/products?consumer_key=ck_c0f1c043add112169f687a4781cc596106fe8811&consumer_secret=cs_c000d18be4508c5158b0a13f3036c18397757d30';
	return $url;
}

/**
 * Get Extensions
 *
 * Gets the Extensions content.
 *
 * @since 2.0.0
 * @return void
 */
function simontaxi_get_extensions_headers( $tab = 'simontaxi' ) {
    $cache = get_transient( 'simontaxi_extensions_headers_' . $tab );
    if ( defined('SIMONTAXI_SCRIPT_DEBUG') && defined('SIMONTAXI_SCRIPT_DEBUG') ) {
		$cache = false;
	}

    if ( false === $cache ) {
        $url = simontaxi_extensions_endpoint();
		
		$ids = apply_filters( 'simontaxi_extensions_ids', 
			array(
				'extensions' => 67,
				'simontaxi' => 69,
				'gateways' => 70,
				'wpthemes' => 83,
				'mobile' => 86,
				'otherproducts' => 88,
			) 
		);
		
		$limit = 9;
		$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		
		$url = $url . '&per_page=' . $limit . '&page=' . $page;
		if ( ! in_array( $tab, array( 'all' ) ) ) {
			$url = add_query_arg( array( 'display' => $tab ), $url . '&category=' . $ids[ $tab ] );
		}
       
		// echo $url;
        $feed = wp_remote_get( esc_url_raw( $url ), array( 'sslverify' => false ) );

        if ( ! is_wp_error( $feed ) ) {
            if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
                
				$headers = array();
				foreach( $feed['headers'] as $key => $val ) {					
					$headers[ $key ] = $val;
				}			
				$cache = wp_json_encode( $headers );				
				$one_month = 30 * 24 * 60 * 60;
				$half_month = 15 * 24 * 60 * 60;
                set_transient( 'simontaxi_extensions_headers_' . $tab, $cache, $half_month );
            }
        } else {
            $cache = '<div class="error"><p>' . esc_html__( 'There was an error retrieving the extensions list from the server. Please try again later.', 'simontaxi' ) . '</div>';
        }
    }

    return $cache;
}

/**
 * Get Extensions
 *
 * Gets the Extensions content.
 *
 * @since 2.0.0
 * @return void
 */
function simontaxi_get_extensions( $tab = 'simontaxi' ) {
    $cache = get_transient( 'simontaxi_extensions_' . $tab );
	
    if ( defined('SIMONTAXI_SCRIPT_DEBUG') && defined('SIMONTAXI_SCRIPT_DEBUG') ) {
		$cache = false;
	}

    if ( false === $cache ) {
        $url = simontaxi_extensions_endpoint();
		
		$ids = apply_filters( 'simontaxi_extensions_ids', 
			array(
				'extensions' => 67,
				'simontaxi' => 69,
				'gateways' => 70,
				'wpthemes' => 83,
				'mobile' => 86,
				'otherproducts' => 88,
			) 
		);
		
		$limit = 9;
		$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		
		$url = $url . '&per_page=' . $limit . '&page=' . $page;
		if ( ! in_array( $tab, array( 'all' ) ) ) {
			$url = add_query_arg( array( 'display' => $tab ), $url . '&category=' . $ids[ $tab ] );
		}

        $feed = wp_remote_get( esc_url_raw( $url ), array( 'sslverify' => false ) );

        if ( ! is_wp_error( $feed ) ) {
            if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
                $cache = wp_remote_retrieve_body( $feed );
				$one_month = 30 * 24 * 60 * 60;
				$half_month = 15 * 24 * 60 * 60;
                set_transient( 'simontaxi_extensions_' . $tab, $cache, $half_month );
            }
        } else {
            $cache = '<div class="error"><p>' . esc_html__( 'There was an error retrieving the extensions list from the server. Please try again later.', 'simontaxi' ) . '</div>';
        }
    }

    return $cache;
}
?>
