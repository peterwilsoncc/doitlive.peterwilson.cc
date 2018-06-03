#!/usr/bin/env bash

source install-functions.sh

download_wp_core
download_wp_tests
install_db
config_test_suite
