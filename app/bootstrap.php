<?php

require_once __DIR__."/../vendors/Symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php";

$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();

$loader->registerNamespaces([
	'Blocky' => __DIR__.'/../src',
	'RedBeanPHP' => __DIR__.'/../vendors/RedBean/src',
	'Aura' => __DIR__.'/../vendors/Aura/src',
	'Psr' => __DIR__.'/../vendors/Psr/src',
	'Pimple' => __DIR__.'/../vendors/Pimple/src'
]);

$loader->registerNamespaceFallbacks([__DIR__.'/../extensions']);

$loader->registerPrefixes([
	'Twig_' => __DIR__.'/../vendors/Twig/src',
	'Swift_' => __DIR__.'/../vendors/SwiftMailer/src'
]);

$loader->register();

$application = new Blocky\Application();

$application['path']['root'] = __DIR__."/..";
$application['path']['themes'] = __DIR__."/../themes";
$application['path']['files'] = __DIR__."/../files";
$application['path']['config'] = __DIR__."/config";
$application['path']['views'] = __DIR__."/views";
$application['path']['extensions'] = __DIR__."/../extensions";
$application['path']['cache'] = __DIR__."/cache";


$application['event']->trigger("Blocky::BootsrapFinished");
$application['event']->trigger("Blocky::Halting");

return $app;
//@deprecated
//$application['event']->trigger('Bootstrap::CoreLoaded');