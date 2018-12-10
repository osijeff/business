<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf5857eb676e4d5e254f4581b313ff87e
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf5857eb676e4d5e254f4581b313ff87e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf5857eb676e4d5e254f4581b313ff87e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}