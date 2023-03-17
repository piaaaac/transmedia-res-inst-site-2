<?php
/**
 * 
 * @param $event" - kirby ppage
 * 
 * */

$computerVision = Json::decode($event->computerVisionJson()->value());

?>

<?php foreach ($event->files() as $f): ?>
  <img class="event-image" src="<?= $f->url() ?>" />
<?php endforeach ?>
  