<?php
/**
 * @var \Swoft\View\Base\View $this
 */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Demo for layout</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
</head>
<body>
    <?php $this->include('layouts/default/header') ?>

    <div class="container">
        <!-- Content here -->
        <div id="page-content" style="padding: 15px 0;">{_CONTENT_}</div>
        <?php $this->include('layouts/default/footer') ?>
    </div>
</body>
</html>