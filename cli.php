<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__."/vendors/Symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php";

$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();

$loader->registerNamespaces([
	'Blocky' => __DIR__.'/src',
	'RedBeanPHP' => __DIR__.'/vendors/RedBean/src',
	'Aura' => __DIR__.'/vendors/Aura/src',
	'Psr' => __DIR__.'/vendors/Psr/src',
	'Pimple' => __DIR__.'/vendors/Pimple/src'
]);

$loader->registerNamespaceFallbacks([__DIR__.'/extensions']);

$loader->registerPrefixes([
	'Twig_' => __DIR__.'/vendors/Twig/src',
	'Swift_' => __DIR__.'/vendors/SwiftMailer/src'
]);

$loader->register();

$application = new Blocky\Application();

$application['path']['root'] = __DIR__;
$application['path']['themes'] = __DIR__."/themes";
$application['path']['files'] = __DIR__."/files";
$application['path']['config'] = __DIR__."/app/config";
$application['path']['views'] = __DIR__."/app/views";
$application['path']['extensions'] = __DIR__."/extensions";
$application['path']['cache'] = __DIR__."/app/cache";
$application['path']['translations'] = __DIR__."/app/translations";

$application['event']->on('Blocky::onRequest', function() use(&$application, &$argv) {
	$core = new Blocky\CLI\Core($application);
	$core->boot();

	if(!is_array($argv) || (count($argv) < 2))
		die('No command provided.');

	array_shift($argv);
	$command = array_shift($argv);

	$core->exec($command, $argv);

	die();
}, 1);

$application['event']->trigger("Blocky::BootsrapFinished");
$application['event']->trigger("Blocky::Halting");