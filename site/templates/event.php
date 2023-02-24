<?php snippet("header") ?>

<div>
  <h2 class="font-sans-l">
    <a onclick="loadEvent('<?= $page->uid() ?>');"><?= $page->title()->kti() ?></a>
    <br />
    <a onclick="loadEvent('<?= $page->uid() ?>');"><?= $page->names()->kti() ?></a>
  </h2>
  <div class="font-sans-m"><?= $page->dateText()->kti() ?></div>

  <div class="font-sans-m">
    <?= $page->fullDescription()->kt() ?>
  </div>
</div>

<?php snippet("footer") ?>
