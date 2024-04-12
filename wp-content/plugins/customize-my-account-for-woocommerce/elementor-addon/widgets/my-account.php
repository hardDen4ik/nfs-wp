<?php
class Elementor_myaccount_widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'my_myaccountwidget';
	}

	public function get_title() {
		return esc_html__( 'My Account', 'customize-my-account-for-woocommerce' );
	}

	public function get_icon() {
		return 'eicon-my-account';
	}

	public function get_categories() {
		return [ 'customize-my-account' ];
	}



	protected function render() {
		include 'templates/my-account.php';
	}
}