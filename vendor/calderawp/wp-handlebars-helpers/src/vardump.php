<?php
/**
 * Adds a "vardump" helper for outputting the context for debug and dev
 *
 * @package calderawp\helpers
 * @author    David Cramer <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 David Cramer
 */

namespace calderawp\helpers;

/**
 * Class vardump
 *
 * @package calderawp\helpers
 */
class vardump {
	/**
	 * Execute the vardump Helper for Handlebars.php {{_vardump vardump_id size}}
	 *
	 * @param \Handlebars\Template $template The template instance
	 * @param \Handlebars\Context  $context  The current context
	 * @param array                $args     The arguments passed the the helper
	 * @param string               $source   The source
	 *
	 * @return mixed
	 */
	public static function helper( $template, $context, $args, $source ){

		ob_start();
		var_dump( $context->get( $args ) );
		$output = ob_get_clean();

		return new \Handlebars\SafeString( '<pre>' . $output . '</pre>' );
	}

} 
