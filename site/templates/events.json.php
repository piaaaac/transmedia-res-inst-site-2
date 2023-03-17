<?php

$json = [];
// $json["htmlText"] = kt($page->text());
$json["htmlText"] = snippet("inspector-content-about", ["text" => $page->text()], true);
$json["events"] = [];

foreach ($page->children()->listed() as $event) {
  $eventMeta['title']       = (string)$event->title();
  $eventMeta['names']       = (string)$event->names();
  $eventMeta['dateText']    = (string)$event->dateText();
  $eventMeta['htmlPreview'] = snippet("inspector-content-event-preview", ["event" => $event], true);
  $json["events"][] = $eventMeta;
}

echo json_encode($json);