<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5f94f1c0a242ee14501d1359e6282481
{
    public static $files = array (
        'b6ec61354e97f32c0ae683041c78392a' => __DIR__ . '/..' . '/scrivo/highlight-php/HighlightUtilities/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPCSStandards\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\' => 57,
        ),
        'L' => 
        array (
            'LastCall\\DownloadsPlugin\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPCSStandards\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\' => 
        array (
            0 => __DIR__ . '/..' . '/dealerdirect/phpcodesniffer-composer-installer/src',
        ),
        'LastCall\\DownloadsPlugin\\' => 
        array (
            0 => __DIR__ . '/..' . '/civicrm/composer-downloads-plugin/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'TOGoS_GitIgnore_' => 
            array (
                0 => __DIR__ . '/..' . '/togos/gitignore/src/main/php',
            ),
        ),
        'H' => 
        array (
            'Highlight\\' => 
            array (
                0 => __DIR__ . '/..' . '/scrivo/highlight-php',
            ),
            'HighlightUtilities\\' => 
            array (
                0 => __DIR__ . '/..' . '/scrivo/highlight-php',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5f94f1c0a242ee14501d1359e6282481::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5f94f1c0a242ee14501d1359e6282481::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit5f94f1c0a242ee14501d1359e6282481::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit5f94f1c0a242ee14501d1359e6282481::$classMap;

        }, null, ClassLoader::class);
    }
}
