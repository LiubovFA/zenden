<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/app/views/css/style.css" title="default styles">
    <title>Zenden Data</title>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <p class="navbar-brand">ZENDEN</p>
        </div>
        <ul class="nav navbar-nav">
            <div class="search-container">
                <form class="form-inline" method="post" action="/show" style="">
                    <div class="form-group">
                        <label class="form-label" for="url">URL: </label>
                        <input type="url" name="url" class="form-control" id="url" placeholder="Enter url">
                    </div>
                    <button id="get_data" type="submit" name="submit" class="btn btn-default">Get</button>
                </form>
            </div>
        </ul>
    </div>
    <div class="green-line">
        <label></label>
    </div>
</nav>

<?php echo $content; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>