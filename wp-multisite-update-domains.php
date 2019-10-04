<?php

use Platformsh\ConfigReader\Config;

require __DIR__.'/vendor/autoload.php';

// Create a new config object to ease reading the Platform.sh environment variables.
// You can alternatively use getenv() yourself.
$config = new Config();

if (!$config->isValidPlatform()) {
    die("Not in a Platform.sh Environment.");
}

try {
	# get primary domain
	$primaryRouteArray = array_filter($config->routes(), function($k) {
		return $k['primary'] == true;
	});

	$primaryDomain = parse_url(key($primaryRouteArray), PHP_URL_HOST);
	# get database credentials and connect
	$credentials = $config->credentials('database');
	$mysqli = new mysqli($credentials['host'], $credentials['username'], $credentials['password'], $credentials['path']);

	# get the current list of wp sites/blogs
	$blogsQuery = $mysqli->query("SELECT blog_id, domain FROM wp_blogs");
	$blogs = $blogsQuery->fetch_all();

	$siteQuery = $mysqli->query("SELECT domain FROM wp_site");
	$site = $siteQuery->fetch_assoc();
	$originalDomain = $site['domain'];

	# differnt logic depends if custom domain is set
	if (strpos($originalDomain, 'platform.sh') !== false) {
		echo "platform.sh site";
		# base domain is a platform.sh domain
			foreach ($blogs as $blog) {
				if($blog[0] === 1) {
					$mysqli->query("UPDATE wp_blogs SET domain = '{$primaryDomain}' WHERE blog_id = {$blog[0]}");
				} else {
					$subDomain = explode('.', $blog[1])[0];
					$mysqli->query("UPDATE wp_blogs SET domain = '{$subDomain}.{$primaryDomain}' WHERE blog_id = {$blog[0]}");
				}
			}
	} else {
		# custom domain
		# update all domains based on the primary/base domain we have on the current environment
		foreach ($blogs as $blog) {
			if($blog[0] == 1) {
				$mysqli->query("UPDATE wp_blogs SET domain = '{$originalDomain}.{$primaryDomain}' WHERE blog_id = {$blog[0]}");
			} else {
				$subDomain = explode('.', $blog[1])[0];
				$mysqli->query("UPDATE wp_blogs SET domain = '{$subDomain}.{$originalDomain}.{$primaryDomain}' WHERE blog_id = {$blog[0]}");
			}
		}
	}
} catch (\Exception $e) {
	print $e->getMessage();
}
