<?php
require plugin_dir_path( dirname( __FILE__ ) ) . 'parasut/vendor/autoload.php';
use Parasut\Client;
	$options = get_option( 'parasut_api_settings' );
    $parasut = new Client([
		  'client_id'     => $options['parasut_api_client_id'],
		  'client_secret' => $options['parasut_api_client_secret'],
		  'username'      => $options['parasut_api_client_username'],
		  'password'      => $options['parasut_api_client_password'],
		  'company_id'    => $options['parasut_api_company_id'],
		  'grant_type'    => 'password',
		  'redirect_uri'  => 'urn:ietf:wg:oauth:2.0:oob',
	]);