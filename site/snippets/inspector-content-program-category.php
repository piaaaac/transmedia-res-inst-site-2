<?php
/**
 * 
 * @param $text   - kirby textarea field / yellow title
 * @param $text2  - kirby textarea field / additional grey text
 * @param $events - kirby pages
 * 
 * */
?>


<div class="font-sans-m color-uicolor"><?= $text->kt() ?></div>
<div class="font-sans-m pl-3"><?= $text2->kt() ?></div>
<div class="pl-3">
<?php 
$links = [];
foreach ($events->toPages()->listed() as $event): 
  $structure = [
    "name" => $event->names()->kti(),
    "title" => $event->title(),
  ];
  ?>
  <p>
    <a class="font-sans-m no-u hover-red pointer" onclick="loadEvent('<?= $event->uid() ?>');"><span class="color-white-20">&#123;name&#58; &ldquo;</span><span><?= $event->names()->kti() ?></span><span class="color-white-20">&rdquo;, title&#58; &ldquo;</span><span><?= $event->title() ?></span><span class="color-white-20">&rdquo;&#125;</span></a><span class="color-white-20">, </span>
  </p>
<?php endforeach ?>
</div>
