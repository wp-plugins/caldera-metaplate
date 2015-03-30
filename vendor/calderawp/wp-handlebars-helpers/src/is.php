<?php

/**
 * Adds a "is" helper for conditional checks.
 *
 * @package calderawp\helpers
 * @author    David Cramer <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link
 * @copyright 2014 David Cramer
 */

namespace calderawp\helpers;

/**
 * Class is
 *
 * @package calderawp\helpers
 */
class is {
	/**
	 * Execute the is Helper for Handlebars.php {{#is variable value}} code {{else}} alt code {{/is}}
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

		$parts = explode(' ', $args);
		$args = $parts[0];
		$value = $parts[1];

		if (is_numeric($args)) {
			$tmp = $args;
		} else {
			$tmp = $context->get($args);
		}

		$context->push($context->last());
		if ($tmp === $value) {
			$template->setStopToken('else');
			$buffer = $template->render($context);
			$template->setStopToken(false);
			$template->discard($context);
		} else {
			$template->setStopToken('else');
			$template->discard($context);
			$template->setStopToken(false);
			$buffer = $template->render($context);
		}
		$context->pop();

		return $buffer;

	}

} 
