<?php
/**
 * 
 * @param $event" - kirby page
 * 
 * */

$computerVision = Json::decode($event->computerVisionJson()->value());

foreach ($event->files() as $f) {
  $probHighlight = 1;
  $amtHighlightTexts = rand(0, 1000)/1000;
  $thisImageFilename = $f->filename();
  $objects = A::filter($computerVision, function ($val, $key) use ($thisImageFilename) {
    return $val["imageFilename"] === $thisImageFilename;
  });
  snippet("computer-vision-image", [
    "className" => "full-width-content",
    "imageUrl" => $f->url(),
    "probHighlight" => $probHighlight,
    "amtHighlightTexts" => $amtHighlightTexts,
    "objects" => $objects,
  ]);
}

?>
