<?php

// foreach($projects as $project) {
//   $html .= snippet('project', ['project' => $project], true);
// }

$images = [];
foreach ($page->files() as $file) {
  $images[] = $file->url();
}

$computerVision = Json::decode($page->computerVisionJson()->value());
// kill($computerVision);

$json['title']            = (string)$page->title();
$json['names']            = (string)$page->names();
$json['dateText']         = (string)$page->dateText();
// $json['htmlFullDescription']  = kt($page->fullDescription());
$json['htmlDescription']  = snippet("inspector-content-event-desc", ["event" => $page], true);
$json['htmlPreview']      = snippet("inspector-content-event-preview", ["event" => $page], true);
$json['images']           = $images;
$json['imagesHighlights'] = $computerVision;
$json['htmlImages']       = snippet("body-content-event-preview", ["event" => $page, "computerVision" => $computerVision], true);

echo json_encode($json);