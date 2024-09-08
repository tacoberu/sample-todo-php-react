<?php

if (@!include __DIR__ . '/../vendor/autoload.php') {
	die('Install using `composer install`');
}

return new Taco\Todo\Builder;
