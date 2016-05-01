<?php

require_once __DIR__."/../vendors/Symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php";

$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();

$loader->registerNamespaces([
	'Blocky' => __DIR__.'/../src',
	'RedBeanPHP' => __DIR__.'/../vendors/RedBean/src',
	'Aura' => __DIR__.'/../vendors/Aura/src',
	'Psr' => __DIR__.'/../vendors/Psr/src',
	'Pimple' => __DIR__.'/../vendors/Pimple/src',
	'MatthiasMullie' => __DIR__.'/../vendors/MatthiasMullie/src',
	'Asm89' => __DIR__.'/../vendors/Asm89/src'
]);

$loader->registerNamespaceFallbacks([__DIR__.'/../extensions']);

$loader->registerPrefixes([
	'Twig_' => __DIR__.'/../vendors/Twig/src',
	'Swift_' => __DIR__.'/../vendors/SwiftMailer/src',
	'Hybrid_' => __DIR__.'/../vendors/Hybrid/src'
]);

$loader->register();

$application = new Blocky\Application();

$application['path']['root'] = __DIR__."/..";
$application['path']['themes'] = __DIR__."/../themes";
$application['path']['files'] = __DIR__."/../files";
$application['path']['config'] = __DIR__."/config";
$application['path']['translations'] = __DIR__."/translations";
$application['path']['views'] = __DIR__."/views";
$application['path']['extensions'] = __DIR__."/../extensions";
$application['path']['cache'] = __DIR__."/cache";

$application['cache'] = function(&$c) {
	$service = new Blocky\Cache\CacheService($c);

	$service->boot();
	$service->addStrategy(new Blocky\Cache\Strategy\NoStrategy());
	$service->addStrategy(new Blocky\Cache\Strategy\FileStrategy($c['path']['cache']."/cache.data"));

	return $service;
};

/* reference to $app in yamlwrapper was added because of cache service access */
Blocky\Config\YamlWrapper::$app = $application;

$application['event']->trigger("Blocky::BootsrapFinished");
$application['event']->trigger("Blocky::Halting");

//@deprecated
//$application['event']->trigger('Bootstrap::CoreLoaded');