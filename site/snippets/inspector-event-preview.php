<?php
/**
 * 
 * @param $event - Kirby page
 * 
 * */
?>

<div class="inspector-event-preview">
  <h2 class="font-sans-l">
    <a class="pointer" onclick="loadEvent('<?= $event->uid() ?>');"><?= $event->title()->kti() ?></a>
    <br />
    <a class="pointer" onclick="loadEvent('<?= $event->uid() ?>');"><?= $event->names()->kti() ?></a>
    <!--  
    <br />
    <a onclick="loadEvent('<?= $event->uid() ?>');"><?= $event->dateText()->kti() ?></a>
    -->
  </h2>
  <div class="font-sans-m"><?= $event->dateText()->kti() ?></div>
</div>
