<?php
/**
 * PWCC Helpers.
 *
 * @package     PWCC Helpers
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */
namespace PWCC\Helpers\Media;

use Tachyon;

/**
 * Class Tachyon_Helpers
 *
 * The purpose of this is the get the return value of the protected
 * image sizes function in the parent class.
 */
class Tachyon_Helpers extends \Tachyon {

	/**
	 * Class variables
	 */
	// Oh look, a singleton
	private static $__instance = null;

	/**
	 * Singleton implementation
	 *
	 * @return object
	 */
	public static function instance() {
		// Set up Tachyon::Instance.
		parent::instance();

		if ( ! is_a( self::$__instance, __CLASS__ ) ) {
			$class = get_called_class();
			self::$__instance = new  $class;
		}

		return self::$__instance;
	}

	/**
	 * Silence is golden.
	 */
	private function __construct() {}

	/**
	 * Provide an array of available image sizes and corresponding dimensions.
	 * Similar to get_intermediate_image_sizes() except that it includes image sizes' dimensions, not just their names.
	 *
	 * @uses Tachyon::image_sizes.
	 * @return array Combination of registered and core image sizes.
	 */
	/**
	 *
	 * @return array
	 */
	public static function image_sizes() {
		return parent::image_sizes();
	}
}


