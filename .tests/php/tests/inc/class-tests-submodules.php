<?php
namespace PWCC\Tests;

/**
 * Class Tests_Submodules
 *
 * @package PWCC\Tests
 * @group   git
 */
class Tests_Submodules extends \WP_UnitTestCase {
	/**
	 * Ensure public git repos are included as HTTP submodules.
	 *
	 * Using HTTP for public repos allows them to be included without
	 * the need for an SSH key. This is very convenient.
	 */
	function test_public_git_repos() {
		$git_submodules = __DIR__ . '/../../../../.gitmodules';

		if ( ! file_exists( $git_submodules ) ) {
			$this->assertFileNotExists( $git_submodules );
			$this->markTestIncomplete( '.gitmodules does NOT exist.' );
		}
		// phpcs:ignore
		$submodules = file_get_contents( $git_submodules, 'r' );

		// Count submodules in use. It is expected all will use the HTTP protocol.
		$submodule_count = substr_count( $submodules, '[submodule' );
		$expected        = $submodule_count;

		// The actual number of submodules using the HTTP protocol.
		$actual = substr_count( $submodules, 'url = http' );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Ensure the Altis modules have loaded.
	 */
	function test_modules_available() {
		$this->assertTrue( function_exists( 'tachyon_url' ), 'Tachyon Plugin unavailable.' );
		$this->assertTrue( function_exists( 'HM\\Cavalcade\\Plugin\\bootstrap' ), 'Cavalcade unavailable.' );
		$this->assertTrue( function_exists( 's3_uploads_init' ), 'S3 uploads unavailable.' );
		$this->assertTrue( function_exists( 'register_extended_post_type' ), 'Extended Post Types unavailable.' );
		$this->assertTrue( function_exists( 'batcache_post' ), 'Batcache unavailable.' );

		$this->assertTrue( class_exists( 'Abraham\\TwitterOAuth\\Request' ), 'TwitterOAuth Request unavailable.' );
	}
}
