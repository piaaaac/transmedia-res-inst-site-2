<?php
/**
 * 
 * @param $event" - kirby page
 * 
 * */

$computerVision = Json::decode($event->computerVisionJson()->value());
?>

<?php foreach ($event->files()->sorted() as $f):
  $probHighlight = 1;
  $amtHighlightTexts = rand(0, 1000)/1000;
  $thisImageFilename = $f->filename();
  $objects = A::filter($computerVision, function ($val, $key) use ($thisImageFilename) {
    return $val["imageFilename"] === $thisImageFilename;
  });
  ?>

  <div class="full-width-content">
    <img src="<?= $f->url() ?>" />
    <?php snippet("computer-vision-highlights", [
      "probHighlight" => $probHighlight,
      "amtHighlightTexts" => $amtHighlightTexts,
      "objects" => $objects,
    ]) ?>
  </div>

<?php endforeach ?>
