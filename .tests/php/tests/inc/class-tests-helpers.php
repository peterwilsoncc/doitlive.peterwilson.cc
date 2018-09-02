<?php
namespace PWCC\Tests;

/**
 * Class Tests_Helpers
 *
 * Tests for the PWCC Helpers plugin.
 *
 * @package PWCC\Tests
 * @group   git
 */
class Tests_Helpers extends \WP_UnitTestCase {
	/**
	 * Ensures the the Jetpack full sync is only running
	 * every twelve hours.
	 */
	function test_jetpack_sync_full_schedule() {
		$expected_schedule = 'twicedaily';
		$actual_schedule = wp_get_schedule( 'jetpack_sync_full_cron' );

		$this->assertSame( $expected_schedule, $actual_schedule );
	}

	/**
	 * Ensures the the Jetpack incremental sync is only running
	 * every hour.
	 */
	function test_jetpack_sync_incremental_schedule() {
		$expected_schedule = 'hourly';
		$actual_schedule = wp_get_schedule( 'jetpack_sync_cron' );

		$this->assertSame( $expected_schedule, $actual_schedule );
	}
}
