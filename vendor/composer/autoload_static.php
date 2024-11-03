<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0cc0f737e4938dbcf266384ce91cffaa
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'LifeJacket\\Server\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'LifeJacket\\Server\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'LifeJacket\\Server\\Plugin' => __DIR__ . '/../..' . '/includes/Plugin.php',
        'LifeJacket\\Server\\REST' => __DIR__ . '/../..' . '/includes/REST.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0cc0f737e4938dbcf266384ce91cffaa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0cc0f737e4938dbcf266384ce91cffaa::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0cc0f737e4938dbcf266384ce91cffaa::$classMap;

        }, null, ClassLoader::class);
    }
}
