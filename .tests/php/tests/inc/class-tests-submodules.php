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
	 * Ensure the Tachyon-Plugin submodule has loaded.
	 */
	function test_tachyon_available() {
		$this->assertTrue( function_exists( 'tachyon_url' ) );
	}
}
