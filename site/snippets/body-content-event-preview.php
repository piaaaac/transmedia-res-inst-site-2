<?php
/**
 * 
 * @param $event - kirby page
 * 
 * */

$computerVision = Json::decode($event->computerVisionJson()->value());
?>

<?php foreach ($event->files()->sorted() as $f):

  if ($f->type() === "image") {

    $probHighlight = 1;
    $amtHighlightTexts = rand(0, 1000)/1000;
    $thisImageFilename = $f->filename();
    $objects = A::filter($computerVision, function ($val, $key) use ($thisImageFilename) {
      return $val["imageFilename"] === $thisImageFilename;
    });

    // add objects from file field 'computerVisionJson'
    $fileComputerVision = Json::decode($f->computerVisionJson()->value());
    foreach ($fileComputerVision as $obj) {
      if (!isset($obj["label"])) { $obj["label"] = ""; }
      if (!isset($obj["confidence"])) { $obj["confidence"] = ""; }
      $objects[] = $obj;
    }

    ?>

    <div class="full-width-content">
      <img src="<?= $f->url() ?>" />
      <?php snippet("computer-vision-highlights", [
        "probHighlight" => $probHighlight,
        "amtHighlightTexts" => $amtHighlightTexts,
        "objects" => $objects,
      ]) ?>
    </div>
    <?php 
  
  } elseif ($f->type() === "video" && $f->extension() === "mp4") {

    $name = $f->name();
    ?>

    <div class="full-width-content">
      <?php snippet("htmlvideo", [
        "event" => $event,
        "videoFilename" => $name,
      ]) ?>
    </div>

    <?php 
  } ?>
<?php endforeach ?>
