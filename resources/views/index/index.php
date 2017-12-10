<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $name ?></title>

    <!-- Fonts -->
    <link href="https://fonts.geekzu.org/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #48cfad;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 100px;
        }

        .notes {
            font-size: 25px;
            margin-bottom: 5px;
        }

        .links {
            margin-top: 30px;
        }

        .links > a {
            color: #48cfad;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-sm {
            margin-bottom: 5px;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="content">
        <div class="title m-b-md">
            <?= $name ?>
        </div>

        <?php foreach ($notes as $note): ?>
            <div class="notes m-b-sm">
                <?= $note ?>
            </div>
        <?php endforeach; ?>

        <div class="links">
            <?php foreach ($links as $link): ?>
                <a href="<?= $link['link'] ?>"><?= $link['name'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>