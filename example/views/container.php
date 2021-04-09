<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($view['title']) ? $view['title'] : 'Engine view' ?></title>
    <link rel="stylesheet" href="http://localhost/friendly-router/example/public/css/style.css">
</head>
<body>
    <div id="root">
        <?php include_once $view['content'] ?>
    </div>
</body>
</html>