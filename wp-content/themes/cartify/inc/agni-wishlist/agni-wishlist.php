<?php 

if ( !class_exists( 'Agni_Wishlist' ) ){

		class Agni_Wishlist {

		public function __construct(){

			// add_action( 'agni_woocommerce_after_shop_loop_item', array( $this, 'add_to_wishlist'), 25 );
			add_action( 'woocommerce_single_product_summary', array( __class__, 'add_to_wishlist'), 35 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts') );

			add_filter( 'agni_wishlist_contents', array( $this, 'add_to_wishlist_button' ) );
			add_filter( 'agni_wishlist_contents', array( $this, 'add_to_wishlist_form' ) );

			$this->includes();

		}

		public static function includes(){

			require_once AGNI_TEMPLATE_DIR . '/agni-wishlist/class-wishlist-endpoint.php';
			require_once AGNI_TEMPLATE_DIR . '/agni-wishlist/class-wishlist-page.php';
			require_once AGNI_TEMPLATE_DIR . '/agni-wishlist/class-wishlist-api.php';

		}

				public static function get_user_id(){

					return ( is_user_logged_in() ) ? get_current_user_id() : 0;

				}

				public static function get_wishlist_name(){
			return esc_html__( 'My Wishlist', 'cartify' );
		}

		public static function add_to_wishlist(){
			?>
			<div class="agni-add-to-wishlist">
				<?php echo apply_filters( 'agni_wishlist_contents', '' ); ?>
			</div>
			<?php
		}


		public function add_to_wishlist_button(){

			wp_enqueue_script( 'cartify-wishlist' );

						?>
			<div class="agni-add-to-wishlist__button user-logged-<?php echo esc_attr( $this->get_user_id() ) ? 'in' : 'out'; ?>">
				<a href="<?php echo esc_url( $this->add_to_wishlist_button_url() ); ?>" class="button">
					<?php echo apply_filters( 'agni_woocommerce_wishlist_icon', cartify_get_icon_svg( 'common', 'wishlist' ) ); ?>
					<span><?php echo apply_filters( 'agni_woocommerce_wishlist_button', esc_html__( 'Add to wishlist', 'cartify' ) ); ?></span>
				</a>
			</div>
			<?php
		}

		public function add_to_wishlist_form(){
			if( !$this->get_user_id() ){
				return;
			}


			$current_user = wp_get_current_user();
			$current_username = $current_user->user_login;

			$args = array(
				'post_type' => 'agni_wc_wishlist',
			);

					$wishlist_query = new WP_Query($args);

					?>
			<div class="agni-add-to-wishlist__panel">
				<div class="agni-add-to-wishlist__panel-close"><i class="lni lni-close"></i></div>
				<div class="agni-add-to-wishlist__panel-contents">
					<div class="agni-add-to-wishlist__existing">
						<?php if( $wishlist_query->have_posts() ){ ?>
							<h6 class="agni-add-to-wishlist__existing-title"><?php echo esc_html__( 'Existing Wishlist', 'cartify' ); ?></h6>
							<ul class="agni-add-to-wishlist__existing-list">
							<?php while( $wishlist_query->have_posts() ){ $wishlist_query->the_post();

																if( $current_username == get_the_author_meta( 'user_login' ) ){
									?>
									<li><a href="#" data-wishlist-id="<?php echo esc_attr( get_the_id() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></li>
									<?php 
								}
							} ?>
							</ul>
						<?php }

											wp_reset_postdata(); ?>

											</div>
					<div class="agni-add-to-wishlist__new">
						<h6 class="agni-add-to-wishlist__new-title"><?php echo esc_html__( 'Create New Wishlist', 'cartify' ); ?></h6>
						<form class="agni-add-to-wishlist-form" method="post" enctype="multipart/form-data">
							<input type="text" class="woocommerce-wishlist-name agni-add-to-wishlist-form__name" name="wishlist_name" id="wishlist_name" autocomplete="wishlist_name" value="<?php echo ( ! empty( $_POST['wishlist_name'] ) ) ? esc_attr( wp_unslash( $_POST['wishlist_name'] ) ) : $this->get_wishlist_name(); ?>"  placeholder="<?php esc_attr_e( 'Wishlist name', 'cartify' ); ?>" />
							<button type="submit" class="woocommerce-wishlist-name agni-add-to-wishlist-form__submit"><?php echo esc_html__( 'Submit', 'cartify' ); ?></button>
						</form>
					</div>
					<div class="agni-wishlist__loader"><?php echo esc_html__( 'Adding to list', 'cartify' ); ?></div>
				</div>
			</div>
			<?php
		}


		public function add_to_wishlist_button_url(){

			if( $this->get_user_id() ){
				$url = add_query_arg(array( 
					'user_id' => $this->get_user_id(), 
					'product_id' => get_the_id(), //$this->get_product_id(), 
					'wishlist_name' => $this->get_wishlist_name() 
				), '');
				// $url = add_query_arg( 'product_id', cartify_wishlist_get_product_id(), $url );
			}
			else{
				global $wp;
				$current_page_url = home_url( $wp->request );
				$login_url = get_permalink( get_option('woocommerce_myaccount_page_id') );

				$url = add_query_arg( 'redirect', urlencode( $current_page_url ), $login_url );
			}

					return $url;
		}


		public function enqueue_scripts(){

					// Registering scripts
			wp_register_script('cartify-wishlist', AGNI_FRAMEWORK_JS_URL . '/agni-wishlist/agni-wishlist.js', array('jquery'), wp_get_theme()->get('Version'), true);
			wp_localize_script('cartify-wishlist', 'cartify_wishlist', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
				'resturl' => esc_url_raw( rest_url('agni-wishlist/v1') ),
				// 'apipath' => 'wp-json/wp/v2',
				// 'add_to_compare_text' => 'Compare',
				// 'remove_from_compare_text' => 'Remove Compare',

						'security' => wp_create_nonce('agni_ajax_wishlist_nonce'),
				// 'action' => 'agni_processing_ajax_search',
			));

				}

	}
}

$agni_wishlist = new Agni_Wishlist();

?>