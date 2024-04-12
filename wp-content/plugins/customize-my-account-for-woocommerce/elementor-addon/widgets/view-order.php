<?php
class Elementor_vieworder_widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'vieworders_widget';
	}

	public function get_title() {
		return esc_html__( 'View Order', 'customize-my-account-for-woocommerce' );
	}

	public function get_icon() {
		return 'eicon-preview-thin';
	}

	public function get_categories() {
		return [ 'customize-my-account' ];
	}



	protected function render() {
		include 'templates/view-order.php';
	}
}