# virus-total-api
Very simple VirusTotal api wrapper for Laravel 5. This package wraps the VirusTotal Api in to very simple methods in order to allow you to very easily perform a virus scan or malware scan on urls or files.
Please be aware, that there are some limits to the public api. See their docs: https://www.virustotal.com/documentation/public-api/

## Installation
Installation is super simple. Use Composer to require this package:
 
    "require": {
        "moay/virus-total-api": "dev-master"
    }

After having pulled the repo via `composer update`, you will have to add this to your laravel providers in `app/config/app.php` (within the providers array):

    'moay\VirusTotalApi\VirusTotalApiServiceProvider',

In order to access the api, you need a VirusTotal API key. Get one at their website and publish the package settings:

    php artisan vendor:publish

This will publish a file named `virus-total-api` to your `app/config` directory. Insert your api key there to make it work.

## Usage

Usage is super simple. Let's imagine a simple controller:

	<?php namespace app\Http\Controllers;

	use VirusTotal;

	class Simplecontroller extends Controller {
	
		/**
		 * Let's scan google for malware
		 */
		public function scanGoogle()
		{
			return VirusTotal::scanUrl('http://google.de');
		}
	
		/**
		 * Let's scan a file for malware
		 */
		public function scanFile()
		{
			return VirusTotal::scanFile('/my/absolute/filename.txt');
		}

	}

This is it, there are no more methods to learn. Please be aware of the access rate limits of the api. Read more about the returned stuff over at https://www.virustotal.com/documentation/public-api/
