<?php snippet("header") ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <?= $page->text()->kt() ?>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <?php foreach ($page->children()->listed() as $event): ?>
      <div class="col-sm-6 col-lg-4">
        <p class="font-sans-l"><?= $event->title()->kti() ?></p>
        <p class="font-sans-l"><?= $event->shortDescription()->kti() ?></p>
      </div>
    <?php endforeach ?>
  </div>
</div>

<?php snippet("footer") ?>
