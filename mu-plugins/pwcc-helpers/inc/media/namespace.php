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

/**
 * Bootstrap media helpers.
 *
 * This covers both the core component and plugins.
 */
function bootstrap() {
	$tachy = tachyon_helper_instance();

	if ( ! $tachy ) {
		return;
	}

	// Use Tachyon in the admin.
	add_filter( 'tachyon_disable_in_admin', '__return_false' );
	// No need to resize on upload due to use in admin.
	add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );

	// Without real sources, we need to fake the image file names.
	// Filter meta data to include all image sizes.
	add_filter( 'wp_get_attachment_metadata', __NAMESPACE__ . '\\filter_attachment_meta_data', 10, 2 );

	// Tachyon plugin and tachyon service can fail to play nice.
	add_filter( 'tachyon_image_downsize_string', __NAMESPACE__ . '\\validate_tachyon_gravity' );
}

/**
 * This sets up the extension we have for Tachyon.
 */
function tachyon_helper_instance() {
	static $instance = false;

	if ( $instance !== false ) {
		return $instance;
	}

	if ( ! defined( 'TACHYON_URL' ) || ! TACHYON_URL ) {
		$instance = null;
		return $instance;
	}

	require_once( __DIR__ . '/class-tachyon-helpers.php' );

	$instance = Tachyon_Helpers::instance();
	return $instance;
}

/**
 * Filter meta data to include all image sizes.
 *
 * This is required to ensure WordPress runs the `wp_calculate_image_srcset`
 * filter when calculating the srcset and to ensure new image sizes are
 * included when Tachyon filters the srcset.
 *
 * A crop hash is added to ensure the right image crop is displayed at any one time.
 *
 * @param array|bool $data          Array of meta data for the given attachment, or false
 *                                  if the object does not exist.
 * @param int        $attachment_id Attachment post ID.
 * @return array|bool Modified image meta data to include crops not saved on upload.
 */
function filter_attachment_meta_data( $data, $attachment_id ) {
	if ( ! is_array( $data ) || ! wp_attachment_is_image( $attachment_id ) ) {
		return $data;
	}

	$image_sizes = tachyon_helper_instance()::image_sizes();
	$mime_type = get_post_mime_type( $attachment_id );
	$filename = pathinfo( $data['file'], PATHINFO_FILENAME );
	$ext = pathinfo( $data['file'], PATHINFO_EXTENSION );
	$orig_w = $data['width'];
	$orig_h = $data['height'];

	foreach ( $image_sizes as $size => $crop ) {
		if ( isset( $data['sizes'][ $size ] ) ) {
			// Meta data is set.
			continue;
		}
		if ( 'full' === $size ) {
			// Full is a special case.
			continue;
		}
		$new_dims = image_resize_dimensions( $orig_w, $orig_h, $crop['width'], $crop['height'], $crop['crop'] );
		/*
		 * $new_dims = [
		 *    0 => 0
		 *    1 => 0
		 *    2 => // Crop start X axis
		 *    3 => // Crop start Y axis
		 *    4 => // New width
		 *    5 => // New height
		 *    6 => // Crop width on source image
		 *    7 => // Crop height on source image
		 * ];
		*/
		if ( ! $new_dims ) {
			continue;
		}
		$w = (int) $new_dims[4];
		$h = (int) $new_dims[5];

		// Set crop hash if source crop isn't 0,0,orig_width,orig_height
		$crop_details = "{$orig_w},{$orig_h},{$new_dims[2]},{$new_dims[3]},{$new_dims[6]},{$new_dims[7]}";
		$crop_hash = '';
		if ( $crop_details !== "{$orig_w},{$orig_h},0,0,{$orig_w},{$orig_h}" ) {
			/*
			 * NOTE: Custom file name data.
			 *
			 * @TODO: Use the image crop somehow.
			 *
			 * The crop hash is used to help determine the correct crop to use for identically
			 * sized images.
			 */
			// $crop_hash = '-c' . substr( strtolower( sha1( $crop_details ) ), 0, 8 );
		}
		// Add meta data with fake WP style file name.
		$data['sizes'][ $size ] = [
			'width' => $w,
			'height' => $h,
			'file' => "{$filename}{$crop_hash}-{$w}x{$h}.{$ext}",
			'mime-type' => $mime_type,
		];
	}

	return $data;
}

/**
 * Tachyon will sometimes return eastnorth, etc instead of northeast, etc.
 *
 * This can cause problems with the Tachyon service.
 *
 * @param $tachyon_args array Arguments with which to invoke the Tacyon service.
 * @return array Tachyon args with gravity corrected.
 */
function validate_tachyon_gravity( $tachyon_args ) {
	if ( ! isset( $tachyon_args['gravity'] ) ) {
		return $tachyon_args;
	}

	// Fix the gravity argument.
	switch ( $tachyon_args['gravity'] ) {
		case 'eastnorth':
			$tachyon_args['gravity'] = 'northeast';
			break;
		case 'eastsouth':
			$tachyon_args['gravity'] = 'southeast';
			break;
		case 'westsouth':
			$tachyon_args['gravity'] = 'southwest';
			break;
		case 'westnorth':
			$tachyon_args['gravity'] = 'northwest';
			break;
	}

	return $tachyon_args;
}
