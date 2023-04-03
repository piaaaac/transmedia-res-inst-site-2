<?php

/**
 * 
 * Die and inspect variable
 * 
 */
function kill ($var, $continue = false) {
  $msg = "<pre>". print_r($var, true) ."</pre>";
  if (isset($continue) && $continue === true) {
    echo $msg;
  } else {
    die($msg);
  }
}


/**
 * 
 * Map from a range to another
 * via https://stackoverflow.com/a/7743244/2501713
 * 
 */
function map ($value, $fromLow, $fromHigh, $toLow, $toHigh) {
  $fromRange = $fromHigh - $fromLow;
  $toRange = $toHigh - $toLow;
  $scaleFactor = $toRange / $fromRange;

  // Re-zero the value within the from range
  $tmpValue = $value - $fromLow;
  // Rescale the value to the to range
  $tmpValue *= $scaleFactor;
  // Re-zero back to the to range
  return $tmpValue + $toLow;
}


/**
 * 
 * Random string
 * via https://stackoverflow.com/a/4356295
 * 
 */
function randomString($length = 10, $insertAlso = "", $insertProb = 0.1) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ     ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[random_int(0, $charactersLength - 1)];
    if ($insertAlso !== "" && rand(0, 1000)/1000 < $insertProb){
      $randomString .= $insertAlso;
    }
  }
  return $randomString;
}


/**
 * Avoid misterious errors
 * 
 * via https://stackoverflow.com/a/35791780
 * 
 * */
function safe_json_encode($value){
  if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
    $encoded = json_encode($value, JSON_PRETTY_PRINT);
  } else {
    $encoded = json_encode($value);
  }
  switch (json_last_error()) {
    case JSON_ERROR_NONE:
      return $encoded;
    case JSON_ERROR_DEPTH:
      return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
    case JSON_ERROR_STATE_MISMATCH:
      return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
    case JSON_ERROR_CTRL_CHAR:
      return 'Unexpected control character found';
    case JSON_ERROR_SYNTAX:
      return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
    case JSON_ERROR_UTF8:
      $clean = utf8ize($value);
      return safe_json_encode($clean);
    default:
      return 'Unknown error'; // or trigger_error() or throw new Exception()
  }
}
/* utility for safe_json_encode */
function utf8ize($mixed) {
  if (is_array($mixed)) {
    foreach ($mixed as $key => $value) {
      $mixed[$key] = utf8ize($value);
    }
  } else if (is_string ($mixed)) {
    return utf8_encode($mixed);
  }
  return $mixed;
}
