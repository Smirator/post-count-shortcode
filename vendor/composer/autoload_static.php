<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc2635eb48486318700410e230f75ff73
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Ruslan\\PostCountShortcode\\' => 26,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ruslan\\PostCountShortcode\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc2635eb48486318700410e230f75ff73::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc2635eb48486318700410e230f75ff73::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc2635eb48486318700410e230f75ff73::$classMap;

        }, null, ClassLoader::class);
    }
}
