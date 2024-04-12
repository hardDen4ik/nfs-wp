<?php
class Elementor_mydownloads_widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'my_mydownloadswidget';
	}

	public function get_title() {
		return esc_html__( 'My Downloads', 'customize-my-account-for-woocommerce' );
	}

	public function get_icon() {
		return 'eicon-download-bold';
	}

	public function get_categories() {
		return [ 'customize-my-account' ];
	}



	protected function render() {
		include 'templates/my-downloads.php';
	}
}