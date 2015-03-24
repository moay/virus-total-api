<?php namespace moay\VirusTotalApi;
 
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
        } catch (\Exception $e) {
            if ($e instanceof \VirusTotal\Exceptions\RateLimitException) 
            {
                return ['success'=>false, 'error'=>'rate limit exceeded'];
            }
            else
            {
            throw new \Exception("Please setup a valid VirusTotal api key to use the api.", 1);
            }
        }
        return array_merge(['success'=>true], $scanResults);
    }

    /**
     * Scan a file via VirusTotal
     * @param  string  $filename  The filename to scan, provide an absolute filename
     * @param  boolean $rescan    Wether or not to force a rescan
     * @return array              Thee information returned by VirusTotal.
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
        } catch (\Exception $e) {
            if ($e instanceof \VirusTotal\Exceptions\RateLimitException) 
            {
                return ['success'=>false, 'error'=>'rate limit exceeded'];
            }
            else
            {
                throw new \Exception("Please setup a valid VirusTotal api key to use the api.", 1);
            }
        }
        return array_merge(['success'=>true], $scanResults);
    }

    /**
     * Scan a file via VirusTotal by providing its url
     * @param  string  $url  The filename to scan, provided as publicly available url
     * @param  boolean $rescan    Wether or not to force a rescan
     * @return array              Thee information returned by VirusTotal.
     */
    public static function scanFileViaUrl($url){
        $scanResults = self::scanUrl($url);
        if($scanResults['success'] && isset($scanResults['filescan_id']))
        {
            // The api key must be set!
            $apikey = config("virus-total-api.api_key");
            if(!$apikey)
            {
                throw new \Exception("Please setup your VirusTotal api key to use the api.", 1);
            }

            // Prepare the file report
            $filereport = new \VirusTotal\File($apikey);

            // Try/Catch to get rate limit exceptions thrown by the api wrapper
            try {
                $fileReport = $filereport->getReport($scanResults['filescan_id']);
            } catch (\Exception $e) {
                if ($e instanceof \VirusTotal\Exceptions\RateLimitException) 
                {
                    return ['success'=>false, 'error'=>'rate limit exceeded'];
                }
                else
                {
                    throw new \Exception("Please setup a valid VirusTotal api key to use the api.", 1);
                }
            }
            return array_merge(['success'=>true], $fileReport);
        }
        
        return $scanResults;
    }
 
}