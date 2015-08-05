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

		$postionalArgs = $template->parseArguments($args);

		if( isset( $postionalArgs[1] ) ){
			$func = $postionalArgs[1];
			$arg = $context->get( $postionalArgs[0] );
		}else{
			$func = $postionalArgs[0];
		}
		
		if( is_object( $func ) ){
			$func = $context->get( $func );
		}else{
			$func = $func;	
		}

		if( !empty( $func ) && function_exists( $func )){
			ob_start();
			if( isset( $arg ) ){
				$output = $func( $arg );
			}else{
				$output = $func();
			}
			$has_output = ob_get_clean();
			if( !empty( $has_output ) && !is_bool( $has_output ) ){
				return $has_output;
			}
			return $output;
		}

		return $arg;

	}

} 
