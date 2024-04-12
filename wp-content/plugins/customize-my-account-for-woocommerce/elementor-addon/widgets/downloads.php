<?php
class Elementor_downloads_widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'downloads_widget_1';
	}

	public function get_title() {
		return esc_html__( 'downloads', 'customize-my-account-for-woocommerce' );
	}

	public function get_icon() {
		return 'eicon-download-button';
	}

	public function get_categories() {
		return [ 'customize-my-account' ];
	}



	protected function render() {
		include 'templates/downloads.php';
	}
}