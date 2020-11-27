<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class APOD_Api_Option_Page extends APOD_Abstract_Option_Page
{
	public function __construct( $option_name ) {
	  parent::__construct( $option_name );
	}

	protected function add_settings_sections() {
		add_settings_section('section_api_key', '', '', $this->menu_slug );
		add_settings_field('api_key', __('Enter API key here','apod_locale'), array( $this, 'api_key_field_render'), $this->menu_slug, 'section_api_key' );
  }

	public function api_key_field_render() {
		$val = get_option( $this->option_name );
		$val = $val ? $val['api-key'] : '';
		?>
		<input type="text" name="<?php echo $this->option_name?>[api-key]" size=50 value="<?php echo esc_attr( $val ) ?>" />
		<?php
	}

	public function render_page() {
	  $log = get_option( 'apod_last_status' );
		require APOD_DIR . 'templates/admin/api-option-page-template.php';
	}
}