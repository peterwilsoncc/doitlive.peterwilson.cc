<?php

namespace PWCC\CodeBin;

/**
 * Bootstrap code bin plugin.
 *
 * Runs on `plugins_loaded` hook.
 */
function bootstrap() {
	add_action( 'init', __NAMESPACE__ . '\\register_cpt' );
	add_action( 'init', __NAMESPACE__ . '\\register_editor_template' );
	add_filter( 'the_content', __NAMESPACE__ . '\\generate_preview_iframe' );
}

function register_cpt() {
	register_extended_post_type(
		'pwcc_code_bin',
		[
			'description'     => __( 'A self hosted code bin.', 'pwcc' ),
			'capability_type' => 'post',
			'hierarchical'    => false,
			'supports'        => [ 'comments', 'trackbacks', 'author', 'editor', 'title', 'post-formats' ],
			'has_archive'     => true,
			'show_in_rest'    => true,
			'public'          => true,
		],
		[
			'singular' => __( 'Code Bin', 'pwcc' ),
			'plural'   => __( 'Code Bins', 'pwcc' ),
			'slug'     => 'code-bin',
		]
	);
}

function register_editor_template() {
	$post_type_object = get_post_type_object( 'pwcc_code_bin' );
	$post_type_object->template = [
		[ 'core/heading', [ 'content' => 'HTML' ] ],
		[ 'core/code', [ 'language' => 'xml' ] ],
		[ 'core/heading', [ 'content' => 'CSS' ] ],
		[ 'core/code', [ 'language' => 'css' ] ],
		[ 'core/heading', [ 'content' => 'JavaScript' ] ],
		[ 'core/code', [ 'language' => 'javascript' ] ],
	];

	$post_type_object->template_lock = 'all';
}

function generate_preview_iframe( $the_content ) {
	if ( ! is_singular( 'pwcc_code_bin' ) ) {
		return $the_content;
	}

	$blocks = parse_blocks( get_post()->post_content );
	$iframe = [];

	foreach ( $blocks as $block ) {
		if ( $block['blockName'] !== 'core/code' ) {
			continue;
		}

		$preview = $block['innerHTML'];

		$prefixes = [
			'<pre class="wp-block-code"><code>',
		];

		$preview = preg_replace(
			'/^\s*(' .
			implode(
				'|',
				array_map(
					function( $v ) {
						return preg_quote( $v, '/' );
					},
					$prefixes
				)
			)
			. ')/',
			'',
			$preview
		);

		$suffixes = [
			'</code></pre>',
		];

		$preview = preg_replace(
			'/(' .
			implode(
				'|',
				array_map(
					function( $v ) {
						return preg_quote( $v, '/' );
					},
					$suffixes
				)
			)
			. ')\s*$/',
			'',
			$preview
		);

		$preview = htmlspecialchars_decode( $preview );

		$iframe[ $block['attrs']['language'] ] = $preview;

		unset( $preview );
	}

	$result = '<html><body>';
	$result .= "<style>{$iframe['css']}</style>";
	$result .= $iframe['xml'];
	$result .= "<script>try{ {$iframe['javascript']} } catch(e){}</script>";
	$result .= '</body></html>';

	$src_fallback = 'data:text/html;charset=utf-8;base64,';
	$src_fallback .= base64_encode( "<!DOCTYPE html> \n <html lang='en-US'><head><meta http-equiv='Content-Type' content='text/html;charset=UTF-8' /><title>Previews not supported.</title><style type='text/css'> html{background:#f1f1f1}body{background:#fff;border:1px solid #ccd0d4;color:#444;font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;margin:2em auto;padding:1em 2em;max-width:700px;-webkit-box-shadow:0 1px 1px rgba(0, 0, 0, .04);box-shadow:0 1px 1px rgba(0, 0, 0, .04)}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font-size:24px;margin:30px 0 0 0;padding:0;padding-bottom:7px}#ep{margin-top:50px}#ep p, #ep .wpdm{font-size:14px;line-height:1.5;margin:25px 0 20px}#ep code{font-family:Consolas, Monaco, monospace}ul li{margin-bottom:10px;font-size:14px }a{color:#0073aa}a:hover, a:active{color:#006799}a:focus{color:#124964;-webkit-box-shadow:0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);box-shadow:0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);outline:none}.button{background:#f3f5f6;border:1px solid #016087;color:#016087;display:inline-block;text-decoration:none;font-size:13px;line-height:2;height:28px;margin:0;padding:0 10px 1px;cursor:pointer;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;vertical-align:top}.button.button-large{line-height:2.30769231;min-height:32px;padding:0 12px}.button:hover, .button:focus{background:#f1f1f1}.button:focus{background:#f3f5f6;border-color:#007cba;-webkit-box-shadow:0 0 0 1px #007cba;box-shadow:0 0 0 1px #007cba;color:#016087;outline:2px solid transparent;outline-offset:0}.button:active{background:#f3f5f6;border-color:#7e8993;-webkit-box-shadow:none;box-shadow:none}</style></head><body id='ep'><div class='wpdm'><p>Your browser does not support previews.</p><p>Please update to a browser on the <a href='https://browsehappy.com/'>Browse Happy</a> website.</p></div></body></html>" );

	$the_content .= "<figure class='wp-block-embed is-type-wp-embed is-provider-pwcc-code-bin wp-block-embed-code-bin'><div class='wp-block-embed__wrapper'>";
	$the_content .= '<iframe ';
	$the_content .= 'allow="camera; geolocation; microphone" ';
	$the_content .= 'sandbox="allow-downloads allow-forms allow-modals allow-pointer-lock allow-popups allow-presentation allow-scripts allow-top-navigation-by-user-activation" ';
	$the_content .= 'src="' . esc_attr( $src_fallback ) . '" ';
	$the_content .= 'srcdoc="' . esc_attr( $result ) . '" ';
	$the_content .= 'style="width:100%" ';
	$the_content .= '/>';
	$the_content .= '</div></figure>';

	return $the_content;
}
