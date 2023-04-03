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

$json["program"] = [];
foreach ($page->program()->toStructure() as $category) {
  if ($category->categoryVisibility()->toBool()) {
    $categoryHtml = snippet("inspector-content-program-category", [
      "text" => $category->categoryText(), 
      "text2" => $category->categoryText2(), 
      "events" => $category->categoryEvents()
    ], true);
    $json["htmlProgramCategories"][] = $categoryHtml;
  }
}


echo json_encode($json);