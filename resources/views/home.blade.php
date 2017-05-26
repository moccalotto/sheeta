<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sheeta</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
</head>
<body>
    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Sheeta
                </h1>
                <h2 class="subtitle">
                    All your stuff on pretty paper.
                </h2>
            </div>
        </div>
    </section>
    <section class="section">
        <section class="container" id="app">
            <router-view></router-view>
            <flash-list></flash-list>
        </section>
    </section>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
