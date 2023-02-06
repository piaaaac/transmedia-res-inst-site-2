<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta http-equiv="content-language" content="en">

  <?php echo $page->metaTags() ?>
  <?php snippet("favicon") ?>
  <?php snippet("load-scripts") ?>

  <script>
    window.currentPage = '<?= $page->uid() ?>';
    window.currentTemplate = '<?= $page->template() ?>';
  </script>

</head>

<body>
  <main>

  <?php snippet("menu") ?>
