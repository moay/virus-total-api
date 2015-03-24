<?php namespace moay\VirusTotalApi;

require_once(__DIR__.'/../../../vendor/autoload.php');
 
class VirusTotalApi {
 	
 	/**
 	 * Scan one or multiple urls via VirusTotal
 	 * @param  string|array $urls The urls to scan
 	 * @return array  The information returned by VirusTotal
 	 */
    public static function scanUrl($urls){
    	// The api key must be set!
        $apikey = config("virus-total-api.api_key");
        if(!$apikey)
        {
        	throw new \Exception("Please setup your VirusTotal api key to use the api.", 1);
        }

        // Prepare the url scanner
        $urlscan = new \VirusTotal\Url($apikey);

        // Try/Catch to get rate limit exceptions thrown by the api wrapper
        try {
       	 	$scanResults = $urlscan->getReport($urls);
        	if($scanResults['response_code'] == 0)
        	{
        		$scanResults = $urlscan->scan($urls);
        	}
        } catch (RateLimitException $e) {
        	return ['success'=>'false', 'error'=>'Too many requests'];
        }
        return $scanResults;
    }

    /**
     * Scan a file via VirusTotal
     * @param  string  $filename  The filename to scan, provide an absolute filename
     * @param  boolean $rescan    Wether or not to force a rescan
     * @return array 			  Thee information returned by VirusTotal.
     */
    public static function scanFile($filename, $rescan = false){
    	// The api key must be set!
    	$apikey = config("virus-total-api.api_key");
        if(!$apikey)
        {
        	throw new \Exception("Please setup your VirusTotal api key to use the api.", 1);
        }

        // Prepare the file scanner
        $filescan = new \VirusTotal\File($apikey);

        // Try/Catch to get rate limit exceptions thrown by the api wrapper
        try {
        	// Force rescan if needed.
        	if($rescan)
        	{
	        	$scanResults = $filescan->rescan($filename);
        	}
        	else
        	{
	       	 	$scanResults = $filescan->getReport($filename);
	        	if($scanResults['response_code'] == 0)
	        	{
	        		$scanResults = $filescan->scan($filename);
	        	}
	        }
        } catch (RateLimitException $e) {
        	return ['success'=>'false', 'error'=>'Too many requests'];
        }
        return $scanResults;
    }
 
}