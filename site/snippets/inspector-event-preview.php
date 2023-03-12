<?php
/**
 * 
 * @param $event - Kirby page
 * 
 * */

$padding = (rand(0, 9) < 5) ? "padding-left: " : "padding-right: ";
$padding .= (rand(0, 25)) ."%;";
?>

<div class="inspector-event-preview">
  <h2 class="font-sans-l mt-1">
    <a class="pointer no-u" onclick="loadEvent('<?= $event->uid() ?>');">
      <span class="d-block text-uppercase"><?= $event->title()->kti() ?></span>
      <span class="d-block text-uppercase text-center"><?= $event->names()->kti() ?></span>
      <span class="d-block font-sans-m text-center" style="<?= $padding ?>"><?= $event->dateText()->kti() ?></span>
    </a>
  </h2>
</div>
