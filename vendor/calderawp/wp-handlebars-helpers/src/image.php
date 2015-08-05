<?php
/**
 * Adds a "_image" helper for getting the image URL from an image field.
 *
 * @package calderawp\helpers
 * @author    David Cramer <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 David Cramer
 */

namespace calderawp\helpers;

/**
 * Class image
 *
 * @package calderawp\helpers
 */
class image {
	/**
	 * Execute the _image Helper for Handlebars.php {{_image image_id size}}
	 * based off the IfHelper
	 *
	 * @param \Handlebars\Template $template The template instance
	 * @param \Handlebars\Context  $context  The current context
	 * @param array                $args     The arguments passed the the helper
	 * @param string               $source   The source
	 *
	 * @return mixed
	 */
	public static function helper( $template, $context, $args, $source ){

		if( strlen( $args ) <= 0 ){
			return $context->get( 'image' );
		}
		$parts = explode(" ", $args);
		$tmp = $context->get( trim( $parts[0] ) );
		if (isset( $tmp[ 'ID' ] ) ) {
			$url = wp_get_attachment_image_src( $tmp[ 'ID' ] );


		} else {
			$url = wp_get_attachment_image_src( $tmp, ( ! empty( $parts[1] ) ? trim( $parts[1] ) : 'thumbnail' ) );

		}

		if( is_array( $url ) && isset( $url[0] ) ){
			return $url[0];
		}

		return null;
	}

} 
