<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3369028fd75e82f2cef72f99d5f314cc
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'f6751967b96004fc91e62372b119449e' => __DIR__ . '/..' . '/phpcheckstyle/phpcheckstyle/src/PHPCheckstyle/_Constants.php',
        '667aeda72477189d0494fecd327c3641' => __DIR__ . '/..' . '/symfony/var-dumper/Resources/functions/dump.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\VarDumper\\' => 28,
        ),
        'D' => 
        array (
            'Database\\Seeders\\' => 17,
            'Database\\Factories\\' => 19,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\VarDumper\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/var-dumper',
        ),
        'Database\\Seeders\\' => 
        array (
            0 => __DIR__ . '/..' . '/laravel/pint/database/seeders',
        ),
        'Database\\Factories\\' => 
        array (
            0 => __DIR__ . '/..' . '/laravel/pint/database/factories',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
            1 => __DIR__ . '/..' . '/laravel/pint/app',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PHPCheckstyle\\' => 
            array (
                0 => __DIR__ . '/..' . '/phpcheckstyle/phpcheckstyle/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3369028fd75e82f2cef72f99d5f314cc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3369028fd75e82f2cef72f99d5f314cc::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit3369028fd75e82f2cef72f99d5f314cc::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit3369028fd75e82f2cef72f99d5f314cc::$classMap;

        }, null, ClassLoader::class);
    }
}
