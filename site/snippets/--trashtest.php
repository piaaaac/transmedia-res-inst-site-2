<?php

return function ($page, $site, $kirby) {

  // do something with the page, site and kirby

  /**
   * ----------------------------------------------
   * 1. PREPARE TRASH PIECES
   * 
   *    Trash pieces are stored in the $trash_from_sources[] array
   *    and are produced using these sources:
   * 
   *    1A. site contents
   *    1B. location data
   *    1C. manually added nerdy strings (eg from git)
   *    1D. apache_request_headers()
   * 
   * ----------------------------------------------
   */

  $trash_from_sources = [];

  // ------------------------------
  // 1A. Site contents
  // ------------------------------
  foreach (page("summer-school-2023")->children()->listed() as $event) {
    $trash_from_sources[] = [
      "names"             => (string)$event->names(),
      "dateText"          => (string)$event->dateText(),
      "shortDescription"  => (string)$event->shortDescription(),
      "fullDescription"   => (string)$event->fullDescription(),
    ];
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
  $testIps = ["209.142.68.29", "69.162.81.155", "192.199.248.75", "162.254.206.227", "207.250.234.100", "108.163.153.6", "206.71.50.230", "65.49.22.66", "23.81.0.59", "207.228.238.7", "200.7.98.19", "131.255.7.26", "95.142.107.181", "185.206.224.67", "195.201.213.247", "5.152.197.179", "195.12.50.155", "92.204.243.227", "46.248.187.100", "197.221.23.194", "185.229.226.83", "103.159.84.142", "47.94.129.116", "47.108.182.80", "8.134.33.121", "103.1.14.238", "47.104.1.98", "106.14.156.213", "47.119.149.69", "110.50.243.6", "185.235.10.211", "223.252.19.130", "101.0.86.43", "207.250.235.10"];
  $rk = array_rand($testIps);
  $ipAddress = $testIps[$rk];

  // Real IP
  // $ipAddress = $_SERVER['REMOTE_ADDR'];

  $geo = [];
  try {
    $geoRaw = file_get_contents('http://www.geoplugin.net/php.gp?ip='. $ipAddress);
    $trash_from_sources[] = $geoRaw;
    $geo = unserialize($geoRaw);
  } catch (Exception $e) {
    $geo["geoplugin_status"] = 666;
    $geo["exception"] = 'Error getting location: '. $e->getMessage();
  }

  if (isset($geo["geoplugin_status"]) && $geo["geoplugin_status"] === 200) { // if successfully got location info

    // ------------------------------------------------------
    // Wikipedia.org API - https://en.wikipedia.org/w/api.php

    /*
    // debug (Hawaii)
    $wiki = json_decode(file_get_contents("https://en.wikipedia.org/w/api.php?action=query&prop=extracts&titles=hawaii&explaintext&format=json"), true);
    */

    // City, region, country or continent
    $geoplugin_key = null;
    if (isset($geo["geoplugin_city"]) && strlen($geo["geoplugin_city"]) > 0) { $geoplugin_key = "geoplugin_city"; }
    elseif (isset($geo["geoplugin_regionName"]) && strlen($geo["geoplugin_regionName"]) > 0) { $geoplugin_key = "geoplugin_regionName"; }
    elseif (isset($geo["geoplugin_countryName"]) && strlen($geo["geoplugin_countryName"]) > 0) { $geoplugin_key = "geoplugin_countryName"; }
    elseif (isset($geo["geoplugin_continentName"]) && strlen($geo["geoplugin_continentName"]) > 0) { $geoplugin_key = "geoplugin_continentName"; }
    $wikiRequestName = urlencode($geo[$geoplugin_key]);
    $wikiJson = file_get_contents("https://en.wikipedia.org/w/api.php?action=query&prop=extracts&titles=". $wikiRequestName ."&explaintext&format=json");
    $trash_from_sources[] = $wikiJson;
    $wiki = json_decode($wikiJson, true);

    $wikiText = null;
    if (isset($wiki["query"]) && isset($wiki["query"]["pages"])) {
      foreach ($wiki["query"]["pages"] as $key => $p) {
        if (isset($p["extract"]) && $wikiText === null) {
          $wikiText = $p["extract"]; // save text of first of returned pages
        }
      }
    }
  }

  // ------------------------------------------------------------
  // 1C. manually added nerdy strings (eg from git)
  // ------------------------------------------------------------

  $trash_from_sources["hardcoded_1"] = "
  assets/css/index.css          |  17 ++++--
  assets/css/index.css.map      |   2 +-
  assets/css/index.scss         |  14 ++++-
  assets/js/digestion-logic.js  |  52 ++++++++---------
  ";

  // ------------------------------
  // 1D. apache_request_headers()
  // ------------------------------

  $trash_from_sources["apache_request_headers"] = apache_request_headers();


  // kill($trash_from_sources);




  /**
   * ------------------------------
   * X. TESTS/BKP of other trash data: 
   *    - server
   *    - request
   *    - binary image data
   * 
   * ------------------------------
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
  $file = $site->pages()->listed()->files()->shuffle()->first();
  $test["kirby_raw_file"] = $file->base64();
  $test["kirby_raw_file"] = substr($file->base64(), 0, rand(100, 1500));

  // kill($test);
  // ------------------------------
  // End Test
  // ------------------------------








  /**
   * -------------------------------------------
   * 2. EDIT / MIX THE PIECES
   * 
   *    2A. Merge  - Single string
   *    2B. Mix    - Array of fragments
   *    2C. Tweak  - Add parts to keep intact
   * 
   * -------------------------------------------
   * 
   * */

  // 2A. Single string
  // dump (x1) the items array with all metadata
  // add (x4) to the string the text only
  $trashInOneString = var_export($trash_from_sources, true); 

  // this was for kirby items / to make them more visible
  // foreach ($trash_from_sources as $item) {
  //   $trashInOneString .= str_repeat($item["text"], 4); 
  // }

  // 2B. Array of fragments
  // compose an array of pieces
  $trash_fragments = [];
  $maxLen = 50;
  for ($i = 0; $i < 100; $i++) {
    $from = rand(0, strlen($trashInOneString)-$maxLen);
    $length = rand(5, $maxLen);
    $piece = substr($trashInOneString, $from, $length);
    $trash_fragments[] = $piece;
  }

  // 2C. Here add parts you want to keep intact
  $additions = [];
  $additions[] = $trash_from_sources["hardcoded_1"];
  $trash_fragments = array_merge($trash_fragments, $additions);


  return [
    "trash_fragments" => $trash_fragments,
  ];

  // --- End of controller

?>



<?php

// --- BKP


/**
 * --------------------------------------------------
 * 3. Put all in a single string
 * 
 *    adding random spaces in between 
 *    to create a landscape
 * 
 * --------------------------------------------------
 * */

$minLength = 600000;
$spaces = 0;

$density = c::get('trash_style');
$density = rand(1, 3);

if ($density === 1) $spaces = 1000;
if ($density === 2) $spaces = 500;
if ($density === 3) $spaces = 150;
$trashString = "";
while (strlen($trashString) < $minLength) {
  $trashString .= shakeTrash($trash_fragments, $spaces);
}

// -------------------------
// functions
// -------------------------

function shakeTrash ($pieces, $spacesMaxNum) {
  $out = "";
  foreach ($pieces as $piece) {
    $spaces = str_repeat("&nbsp;", rand(0, $spacesMaxNum));
    $out .= $spaces . $piece;
  }
  $out = str_replace(" ", "&nbsp;", $out);
  
  $index = rand(0, strlen($out));
  $out = substr_replace($out, "&nbsp;", $index);
  // $out = str_replace("&nbsp;", "<span class='trash-separator'></span>", $out);
  // $out = "<span>". str_replace("&nbsp;", "</span><span>", $out) ."</span>";

  return $out;
}


<div id="trash" style="word-break: break-all;" aria-hidden="true"><?= strlen($trashString) ." --- $trashString" ?></div>











