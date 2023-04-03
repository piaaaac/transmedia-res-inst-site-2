<?php

/**
 *  @param $event         - Kirby page
 *  @param $videoFilename - string
 * 
 * */

// --- video files

$mp4Filename = "$videoFilename.mp4";
$webmFilename = "$videoFilename.webm";
if($mp4File = $event->files()->findBy('filename', $mp4Filename)) {
  $mp4Src = $mp4File->url();
} else {
  return "<p>File ". $mp4Filename ." not found in page uploads.</p>";
}
if($webmFile = $event->files()->findBy('filename', $webmFilename)) {
  $webmSrc = $webmFile->url();
} else {
  return "<p>File ". $webmFilename ." not found in page uploads.</p>";
}
?>

<video 
  class='play-in-viewport' 
  poster='' 
  loop muted autoplay playsinline
>
  <source src="<?= $mp4Src ?>" type="video/mp4" />
  <source src="<?= $webmSrc ?>" type="video/webm" />
  Sorry, your browser doesn't support embedded videos.
</video>