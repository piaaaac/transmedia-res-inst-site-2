<?php

return function ($page, $site, $kirby, $eventUid) {

  // do something with the page, site and kirby

  /**
   * ----------------------------------------------
   * 1. PREPARE TRASH PIECES
   * 
   *    Trash pieces are stored in the $trash_by_source[] array
   *    and are produced using these sources:
   * 
   *    1A. site contents
   *    1B. location data
   *    1C. manually added nerdy strings (eg from git)
   *    1D. apache_request_headers()
   * 
   * ----------------------------------------------
   */

  // keep this list updated!
  $trash_by_source = [];
  // $trash_by_source["geoplugin_raw"] = null;
  $trash_by_source["geoplugin_parsed"] = null;
  $trash_by_source["wikipedia_api"] = null;
  $trash_by_source["hardcoded_git_create"] = null;
  $trash_by_source["hardcoded_git_edits"] = null;
  $trash_by_source["hardcoded_server"] = null;
  $trash_by_source["hardcoded_all"] = null;
  $trash_by_source["apache_request_headers"] = null;
  $trash_by_source["site_contents"] = [];

  // ------------------------------
  // 1A. Site contents
  // ------------------------------
  foreach (page("summer-school-2023")->children()->listed() as $event) {
    $eventData = [
      "names"             => (string)$event->names(),
      "dateText"          => (string)$event->dateText(),
      "shortDescription"  => (string)$event->shortDescription(),
      "fullDescription"   => (string)$event->fullDescription(),
    ];
    $trash_by_source["site_contents"][] = json_encode($eventData);
  }



  // ------------------------------
  // 1B. Location data
  /**
   * API site http://www.geoplugin.net
   * 
   * Example http://www.geoplugin.net/php.gp?ip=185.206.224.67
   * returns:
   *  
   *    [geoplugin_request] => 185.206.224.67
   *    [geoplugin_status] => 200
   *    [geoplugin_delay] => 1ms
   *    [geoplugin_credit] => Some of the returned data includes GeoLite data created by MaxMind, available from http://www.maxmind.com.
   *    [geoplugin_city] => Copenhagen
   *    [geoplugin_region] => Capital Region
   *    [geoplugin_regionCode] => 84
   *    [geoplugin_regionName] => Capital Region
   *    [geoplugin_areaCode] => 
   *    [geoplugin_dmaCode] => 
   *    [geoplugin_countryCode] => DK
   *    [geoplugin_countryName] => Denmark
   *    [geoplugin_inEU] => 1
   *    [geoplugin_euVATrate] => 25
   *    [geoplugin_continentCode] => EU
   *    [geoplugin_continentName] => Europe
   *    [geoplugin_latitude] => 55.7327
   *    [geoplugin_longitude] => 12.3656
   *    [geoplugin_locationAccuracyRadius] => 20
   *    [geoplugin_timezone] => Europe/Copenhagen
   *    [geoplugin_currencyCode] => DKK
   *    [geoplugin_currencySymbol] => kr
   *    [geoplugin_currencySymbol_UTF8] => kr
   *    [geoplugin_currencyConverter] => 6.9602
   * 
   * Then trying to fetch wikipedia using the name of the city
   * for example:
   * 
   * https://en.wikipedia.org/w/api.php?action=query&prop=extracts&titles=hawaii&explaintext&format=json
   * 
   * */
  // ------------------------------

  // TEST IPs
  // https://www.dotcom-monitor.com/wiki/knowledge-base/network-location-ip-addresses/
  // $testIps = ["209.142.68.29", "69.162.81.155", "192.199.248.75", "162.254.206.227", "207.250.234.100", "108.163.153.6", "206.71.50.230", "65.49.22.66", "23.81.0.59", "207.228.238.7", "200.7.98.19", "131.255.7.26", "95.142.107.181", "185.206.224.67", "195.201.213.247", "5.152.197.179", "195.12.50.155", "92.204.243.227", "46.248.187.100", "197.221.23.194", "185.229.226.83", "103.159.84.142", "47.94.129.116", "47.108.182.80", "8.134.33.121", "103.1.14.238", "47.104.1.98", "106.14.156.213", "47.119.149.69", "110.50.243.6", "185.235.10.211", "223.252.19.130", "101.0.86.43", "207.250.235.10"];
  // $rk = array_rand($testIps);
  // $ipAddress = $testIps[$rk];

  // Real IP
  $ipAddress = $_SERVER['REMOTE_ADDR'];

  $geo = [];
  try {
    $geoRaw = file_get_contents('http://www.geoplugin.net/php.gp?ip='. $ipAddress);
    $geo = unserialize($geoRaw);
  } catch (Exception $e) {
    $geo["geoplugin_status"] = 666;
    $geo["exception"] = 'Error getting location: '. $e->getMessage();
  }

  if (isset($geo["geoplugin_status"]) && $geo["geoplugin_status"] === 200) { // if successfully got location info

    $trash_by_source["geoplugin_parsed"] = $geo; // save in $trash variable

    // ------------------------------------------------------
    // Wikipedia.org API - https://en.wikipedia.org/w/api.php
/*
*/
    try {
      // City, region, country or continent
      $geoplugin_key = null;
      if (isset($geo["geoplugin_city"]) && strlen($geo["geoplugin_city"]) > 0) { $geoplugin_key = "geoplugin_city"; }
      elseif (isset($geo["geoplugin_regionName"]) && strlen($geo["geoplugin_regionName"]) > 0) { $geoplugin_key = "geoplugin_regionName"; }
      elseif (isset($geo["geoplugin_countryName"]) && strlen($geo["geoplugin_countryName"]) > 0) { $geoplugin_key = "geoplugin_countryName"; }
      elseif (isset($geo["geoplugin_continentName"]) && strlen($geo["geoplugin_continentName"]) > 0) { $geoplugin_key = "geoplugin_continentName"; }
      $wikiRequestName = urlencode($geo[$geoplugin_key]);
      $wikiJson = file_get_contents("https://en.wikipedia.org/w/api.php?action=query&prop=extracts&titles=". $wikiRequestName ."&explaintext&format=json");
      // $trash_by_source[] = $wikiJson;
      $wiki = json_decode($wikiJson, true);

      $wikiText = null;
      if (isset($wiki["query"]) && isset($wiki["query"]["pages"])) {
        foreach ($wiki["query"]["pages"] as $key => $p) {
          if (isset($p["extract"]) && $wikiText === null) {
            $wikiText = $p["extract"]; // save text of first of returned pages
            $trash_by_source["wikipedia_api"] = [$wikiText];
          }
        }
      }
    } catch (Exception $e) {
      die("ERROR (23987592) " . $e->getMessage());
    }
/*
*/
  }

  // ------------------------------------------------------------
  // 1C. manually added nerdy strings (eg from git)
  // ------------------------------------------------------------

  $trash_by_source["hardcoded_git_create"] = [
    "create mode 100644 assets/lib/highlight/es/languages/abnf.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/accesslog.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/actionscript.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/ada.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/angelscript.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/apache.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/applescript.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/arcade.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/arduino.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/armasm.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/asciidoc.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/aspectj.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/autohotkey.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/autoit.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/avrasm.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/awk.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/axapta.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/bash.min.js",
    "create mode 129842 assets/css/index.scss",
    "create mode 129842 assets/css/_typography.scss",
    "create mode 129842 assets/css/_reset.scss",
    "create mode 129842 assets/css/hamburger-settings.css.map",
    "create mode 129842 assets/css/bootstrap-custom.css.map",
    "create mode 129842 assets/css/index.css",
    "create mode 129842 assets/css/index.css.map",
    "create mode 129842 assets/css/prepros.config",
    "create mode 129842 assets/css/bootstrap-custom.css",
    "create mode 129842 assets/css/hamburger-settings.css",
    "create mode 129842 assets/css/_mixins.scss",
    "create mode 129842 assets/css/bootstrap-custom.scss",
    "create mode 129842 assets/models/13.obj",
    "create mode 129842 assets/models/12.obj",
    "create mode 129842 assets/models/10.obj",
    "create mode 129842 assets/models/11.obj",
    "create mode 129842 assets/models/9.obj",
    "create mode 129842 assets/models/8.obj",
    "create mode 129842 assets/models/3.obj",
    "create mode 129842 assets/models/2.obj",
    "create mode 129842 assets/models/1.obj",
    "create mode 129842 assets/models/5.obj",
    "create mode 129842 assets/models/4.obj",
    "create mode 129842 assets/models/6.obj",
    "create mode 129842 assets/models/7.obj",
    "create mode 129842 assets/lib/jquery-3.6.0.min.js",
    "create mode 129842 assets/lib/es-module-shims.js",
    "create mode 129842 assets/lib/threejs",
    "create mode 129842 assets/lib/threejs/three.module.js",
    "create mode 129842 assets/data",
    "create mode 129842 assets/data/1-mergedObjectsDetected.json",
    "create mode 129842 assets/data/5-merge1-2-4-objectsDetected.json",
    "create mode 129842 assets/data/3-merge1-2-objectsDetected.json",
  ];
  $trash_by_source["hardcoded_git_edits"] = [
    "modified:   README.md",
    "modified:   assets/css/index.css",
    "deleted:    assets/css/index.css.map",
    "modified:   assets/css/index.scss",
    "modified:   content/2_summer-school-2023/events.txt",
    "modified:   site/snippets/creature.php",
    "modified:   site/snippets/inspector.php",
    "modified:   site/snippets/load-scripts.php",
    "modified:   site/templates/home.php",
    "assets/css/prepros.config",
    "assets/lib/es-module-shims.js",
  ];
  $trash_by_source["hardcoded_server"] = [
    "[REMOTE_ADDR] => 93.35.168.247",
    "[HTTP_ACCEPT_LANGUAGE] => en-US,en;q=0.9",
    "[HTTP_ACCEPT_ENCODING] => gzip, deflate, br",
    "[HTTP_SEC_FETCH_DEST] => document",
    "[HTTP_SEC_FETCH_USER] => ?1",
    "[HTTP_SEC_FETCH_MODE] => navigate",
    "[HTTP_SEC_FETCH_SITE] => none",
    "[HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,* /*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "[HTTP_USER_AGENT] => Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36",
    "[HTTP_DNT] => 1",
    "[HTTP_SEC_CH_UA_PLATFORM] => 'macOS'",
    "[HTTP_SEC_CH_UA_MOBILE] => ?0",
    "[HTTP_SEC_CH_UA] => 'Not A(Brand';v='24', 'Chromium';v='110'",
    "[HTTP_CACHE_CONTROL] => max-age=0",
    "[HTTP_CONNECTION] => close",
    "[HTTP_X_FORWARDED_PROTO] => https",
    "[HTTP_X_FORWARDED_PORT] => 443",
    "[HTTP_X_REAL_IP] => 93.35.168.247",
    "[HTTP_HOST] => www.transmediaresearch.institute",
    "[proxy-nokeepalive] => 1",
    "[HTTPS] => on",
    "[FCGI_ROLE] => RESPONDER",
    "[PHP_SELF] => /ktest/index.php",
    "[REQUEST_TIME_FLOAT] => 1676452827.7297",
    "[REQUEST_TIME] => 1676452827",
  ];

  $trash_by_source["hardcoded_all"] = array_merge(
    $trash_by_source["hardcoded_git_create"],
    $trash_by_source["hardcoded_git_edits"],
    $trash_by_source["hardcoded_server"]
  );

  // ------------------------------
  // 1D. apache_request_headers()
  // ------------------------------

  $trash_by_source["apache_request_headers"] = apache_request_headers();
// return [ "trash" => $trash_by_source ]; // return debug data from controller


  // kill($trash_by_source);




  /**
   * ========================================================================
   * ------------------------------------------------------------------------
   * X. TESTS/BKP of other trash data: 
   *    - server
   *    - request
   *    - binary image data
   * ------------------------------------------------------------------------
   * 
   * 
   * $_SERVER info we can use (shouldn't be dangerous to reveal)
   * 
   *    [REMOTE_ADDR] => 93.35.168.247
   *    [HTTP_ACCEPT_LANGUAGE] => en-US,en;q=0.9
   *    [HTTP_ACCEPT_ENCODING] => gzip, deflate, br
   *    [HTTP_SEC_FETCH_DEST] => document
   *    [HTTP_SEC_FETCH_USER] => ?1
   *    [HTTP_SEC_FETCH_MODE] => navigate
   *    [HTTP_SEC_FETCH_SITE] => none
   *    [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,* /*;q=0.8,application/signed-exchange;v=b3;q=0.7
   *    [HTTP_USER_AGENT] => Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36
   *    [HTTP_DNT] => 1
   *    [HTTP_SEC_CH_UA_PLATFORM] => "macOS"
   *    [HTTP_SEC_CH_UA_MOBILE] => ?0
   *    [HTTP_SEC_CH_UA] => "Not A(Brand";v="24", "Chromium";v="110"
   *    [HTTP_CACHE_CONTROL] => max-age=0
   *    [HTTP_CONNECTION] => close
   *    [HTTP_X_FORWARDED_PROTO] => https
   *    [HTTP_X_FORWARDED_PORT] => 443
   *    [HTTP_X_REAL_IP] => 93.35.168.247
   *    [HTTP_HOST] => www.transmediaresearch.institute
   *    [proxy-nokeepalive] => 1
   *    [HTTPS] => on
   *    [FCGI_ROLE] => RESPONDER
   *    [PHP_SELF] => /ktest/index.php
   *    [REQUEST_TIME_FLOAT] => 1676452827.7297
   *    [REQUEST_TIME] => 1676452827
   * 
   * */
  // ------------------------------
  $test = [];

  // Server
  /* IT MIGHT BE DANGEROUS TO REVEAL THESE INFO */ $test["server"] = $_SERVER; // these two are the same on Aruba server
  /* IT MIGHT BE DANGEROUS TO REVEAL THESE INFO */ $test["getenv"] = getenv(); // these two are the same on Aruba server
  $test["request"] = $_REQUEST; // is filled only when coming from an HTML form

  // Headers
  $test["apache_request_headers"] = apache_request_headers();

  // From Kirby CMS
  $file = page("landing/shape")->files()->shuffle()->first();
  $test["kirby_raw_file"] = $file->base64();
  $test["kirby_raw_file"] = substr($file->base64(), 0, rand(100, 1500));

  // kill($test);
  // ------------------------------------------------------------------------
  // End Test
  // ------------------------------------------------------------------------
  // ========================================================================




  /**
   * -------------------------------------------
   * 2. EDIT / MIX THE PIECES
   * 
   *    2A. Merge  - Single string
   *    2B. Mix    - Array of fragments
   * 
   * -------------------------------------------
   * 
   * */

  // 2A. Single string
  // dump (x1) the items array with all metadata
  // add (x4) to the string the text only
  $trashInOneString = var_export($trash_by_source, true); 

  // this was for kirby items / to make them more visible
  // foreach ($trash_by_source as $item) {
  //   $trashInOneString .= str_repeat($item["text"], 4); 
  // }

  // 2B. Array of fragments
  // compose an array of pieces
  $trash_fragments = [];
  $maxLen1 = 50;
  $maxLen2 = 500;
  for ($i = 0; $i < 110; $i++) {
    $maxLen = rand(0, 1000)/1000 < 0.05 ? $maxLen2 : $maxLen1;
    $from = rand(0, strlen($trashInOneString)-$maxLen);
    $length = rand(5, $maxLen);
    $piece = substr($trashInOneString, $from, $length);
    $trash_fragments[] = $piece ."\n\n\n";
  }

  $out = [
    "eventUid" => $eventUid,
    "trash" => [
      "by_source" => $trash_by_source,
      "fragments" => $trash_fragments,
    ],
  ];

  // var_dump($out);
  // echo json_encode($out);
  // die();

  return $out;

  // --- End of controller

}

?>








