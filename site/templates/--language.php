<?php
$ass = $kirby->url("assets");
$bg1 = $page->files()->shuffle()->first();
$bg2 = $page->files()->shuffle()->first();

$txt = $page->text()->value();
$txtPieces = [];
while (strlen($txt > 0)) {
  $length = rand(0, 50);
  if (strlen($txt) < $length) {
    $length = strlen($txt);
  }
  $piece = substr($txt, 0, $length);
  $txtPieces[] = $piece;
  $txt = substr($txt, $length);
  echo "<br />-----------------<br />";
  echo $piece;
  echo "<br />---<br />";
  echo $txt;
  echo "<br />---<br />";
  echo strlen($txt);
}
kill($txtPieces);
?>

<?php snippet("header") ?>

<div id="language-bg">
  <div class="layer" style="background-image: url('<?= $bg1->url() ?>');"></div>
  <div class="layer" style="background-image: url('<?= $bg2->url() ?>');"></div>
</div>

<div class="text-r">
  <?= $page->text()->kt() ?>
</div>

<?php snippet("footer") ?>
