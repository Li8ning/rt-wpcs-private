<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8ca6822dec3f3ee66867bde89ec9e6e1
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RTSlideshow\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RTSlideshow\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8ca6822dec3f3ee66867bde89ec9e6e1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8ca6822dec3f3ee66867bde89ec9e6e1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8ca6822dec3f3ee66867bde89ec9e6e1::$classMap;

        }, null, ClassLoader::class);
    }
}