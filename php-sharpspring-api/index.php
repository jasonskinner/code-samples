<?php

if (!class_exists('MatmonSharpSpring\SharpSpring')){
	require_once ( 'sharpspring.php' );
}

if ( ! defined( 'SHARPSPRING_ACCOUNTID' ) ) {
	define( 'SHARPSPRING_ACCOUNTID', '2_4DDC7A277B078B1B65383A6D07260D53' );
}

if ( ! defined( 'SHARPSPRING_SECRETKEY' ) ) {
	define( 'SHARPSPRING_SECRETKEY', 'F6D32AF198F6CB86E5F6D977E83CB9B9' );
}

$SharpSpring = new \MatmonSharpSpring\SharpSpring( SHARPSPRING_ACCOUNTID, SHARPSPRING_SECRETKEY );

$limit = 500;
$offset = 0;

$result = $SharpSpring->call('getAccounts',
	array('where' => array(), 'limit' => $limit, 'offset' => $offset)
);
print_r($result);