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
	 *
	 * @dataProvider data_modules_available
	 */
	function test_modules_available( $test_for, $message, $is_class = false ) {
		if ( $is_class ) {
			$this->assertTrue( class_exists( $test_for ), "$message unavailable." );
			return;
		}
		$this->assertTrue( function_exists( $test_for ), "$message unavailable." );
	}

	/**
	 * Data provider for test_modules_available.
	 *
	 * 1. String Function or class to test for.
	 * 2. String Name for message on failure.
	 * 3. Bool   True if class.
	 */
	function data_modules_available() {
		return [
			[
				'tachyon_url',
				'Tachyon Plugin',
			],
			[
				's3_uploads_init',
				'S3 Uploads',
			],
			[
				'HM\\Cavalcade\\Plugin\\bootstrap',
				'Cavalcade plugin',
			],
			[
				'register_extended_post_type',
				'Extended Post Types',
			],
			[
				'batcache_post',
				'Batcache plugin',
			],
			[
				'Abraham\\TwitterOAuth\\Request',
				'TwitterOAuth Request',
				true,
			],
		];
	}
}
