<?php

namespace MatmonSharpSpring;
/**
 * SharpSpring Wrapper
 */

class SharpSpring {
	/**
	 * @var $accountID
	 */
	private $accountID;

	/**
	 * @var $secretKey
	 */
	private $secretKey;

	/**
	 * SharpSpring constructor.
	 *
	 * @param $accountID
	 * @param $secretKey
	 */
	public function __construct( $accountID, $secretKey ) {
		$this->accountID = $accountID;
		$this->secretKey = $secretKey;
	}

	/**
	 * @param $method
	 * @param array $args
	 *
	 * @return bool|mixed
	 */
	public function call( $method, $args = array() ) {
		return $this->makeRequest( $method, $args );
	}

	/**
	 * @param $method
	 * @param array $args
	 *
	 * @return bool|mixed
	 */
	private function makeRequest( $method, $args = array() ) {
		// Build query.
		$queryString = http_build_query( array( 'accountID' => $this->accountID, 'secretKey' => $this->secretKey ) );
		$url         = "http://api.sharpspring.com/pubapi/v1/?$queryString";
		$requestID   = uniqid();

		// Set data
		$data = array(
			'method' => $method,
			'params' => $args,
			'id'     => $requestID,
		);

		// If curl is available.
		if ( function_exists( 'curl_init' ) && function_exists( 'curl_setopt' ) ) {
			// Encode
			$data = json_encode( $data );
			// Curl
			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen( $data ),
			) );
			// Get result and close connection.
			$result = curl_exec( $ch );
			curl_close( $ch );
			// Print.
			print_r( $result );
			exit;

		} else {
			// Encode json and get results.
			$json_data = json_encode( $args );
			$result    = file_get_contents( $url, null, stream_context_create( array(
				'http' => array(
					'protocol_version' => 1.1,
					'user_agent'       => 'PHP-MCAPI/2.0',
					'method'           => 'POST',
					'header'           => "Content-type: application/json\r\n" .
					                      "Connection: close\r\n" .
					                      "Content-length: " . strlen( $json_data ) . "\r\n",
					'content'          => $json_data,
				),
			) ) );
		}
		// Return.
		return $result ? json_decode( $result, true ) : false;
	}
}

?>