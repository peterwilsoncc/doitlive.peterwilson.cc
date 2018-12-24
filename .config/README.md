# Webpack Configuration

This is a site-wide webpack configuration. we're using [multi-compiler](https://github.com/webpack/webpack/tree/master/examples/multi-compiler) configuration to easily add plugins/themes. Ideally, each plugin/theme should only add one configuration object that will build one JS file and one CSS file (if applicable). You can add more :)


## config.js
This is the main configuration file where each plugin/theme's config should be added to.

## css.js
Configuration for CSS files. It supports PostCSS and other goodness.

## shared.js
Configuration for dealing with JS files and others.
