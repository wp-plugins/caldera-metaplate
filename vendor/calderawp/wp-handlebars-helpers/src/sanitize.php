<?php
/**
 * Adds a "sanitize" helper for running the selected context through a sanitisation callback.
 *
 * @package calderawp\helpers
 * @author    David Cramer <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 David Cramer
 */

namespace calderawp\helpers;

/**
 * Class sanitize
 *
 * @package calderawp\helpers
 */
class sanitize {
	/**
	 * Execute the sanitize Helper for Handlebars.php {{sanitize field callback}}
	 *
	 * @param \Handlebars\Template $template The template instance
	 * @param \Handlebars\Context  $context  The current context
	 * @param array                $args     The arguments passed the the helper
	 * @param string               $source   The source
	 *
	 * @return mixed
	 */
	public static function helper( $template, $context, $args, $source ){

		$postionalArgs = $args->getPositionalArguments();

		$tmp = $context->get($postionalArgs[0]);
		
		$func = 'sanitize_text_field';

		if( isset( $postionalArgs[1] ) ){
			if( is_object( $postionalArgs[1] ) ){
				$func = $context->get($postionalArgs[1]);
			}else{
				$func = $postionalArgs[1];	
			}
		}
		
		if( !empty( $func ) && function_exists( $func )){
			return $func( $tmp );
		}

		return $tmp;

	}

} 
