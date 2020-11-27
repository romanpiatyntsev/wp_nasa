<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class APOD_Auto_Loader {

	private $dirs = array();

	public function __construct() {
		spl_autoload_register( array( $this, 'loader' ) );
	}

	public function register() {
		$this->dirs = array(
			APOD_DIR,
			APOD_DIR . 'includes/',
			APOD_DIR . 'includes/config/',
			APOD_DIR . 'includes/option-pages/',
		);
	}

	public function loader( $classname ) {
		$classname = strtolower( $classname );
		$classname = str_replace( '_','-', $classname );
		foreach ( $this->dirs as $dir ) {
			$file = "{$dir}class-{$classname}.php";
			if ( file_exists( $file ) ) {
				require_once $file;
				return;
			}
		}
	}
}

$auto_loader = new APOD_Auto_Loader();
$auto_loader->register();