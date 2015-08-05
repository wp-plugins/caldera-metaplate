<?php
/**
 * Adds a "date" helper for running the selected context through a date callback.
 *
 * @package calderawp\helpers
 * @author    David Cramer <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 David Cramer
 */

namespace calderawp\helpers;

/**
 * Class date
 *
 * @package calderawp\helpers
 */
class date {
	/**
	 * Execute the date Helper for Handlebars.php {{date field format}}
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

		return date( $context->get($postionalArgs[1]), strtotime( $context->get($postionalArgs[0]) ) );

	}

} 
