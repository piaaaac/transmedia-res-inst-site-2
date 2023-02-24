<?php

// foreach($projects as $project) {
//   $html .= snippet('project', ['project' => $project], true);
// }

$images = [];
foreach ($page->files() as $file) {
  $images[] = $file->url();
}

$json['title']            = (string)$page->title();
$json['names']            = (string)$page->names();
$json['dateText']         = (string)$page->dateText();
$json['htmlFullDescription']  = kt($page->fullDescription());
$json['images']           = $images;

echo json_encode($json);