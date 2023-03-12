<?php

$json = [];

foreach ($page->children()->listed() as $event) {
  $eventMeta['title']       = (string)$event->title();
  $eventMeta['names']       = (string)$event->names();
  $eventMeta['dateText']    = (string)$event->dateText();
  $eventMeta['htmlPreview'] = snippet("inspector-event-preview", ["event" => $event], true);
  $json[] = $eventMeta;
}

echo json_encode($json);