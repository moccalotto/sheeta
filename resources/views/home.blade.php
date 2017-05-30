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
    <div id="app" v-cloak>
        <section class="hero is-primary">

            <div class="hero-body">
                <div class="container">
                    <div class="columns is-vcentered">
                        <div class="column">
                            <p class="title">
                                Sheeta
                            </p>
                            <p class="subtitle">
                                All your stuff on pretty paper
                            </p>
                        </div>
                        <div class="column is-narrow">
                            <auth-box></auth-box>
                        </div>
                    </div>
                </div>
            </div>
            <router-view name="tabs"></router-view>
        </section>
        <section class="section">
            <section class="container">
                <router-view></router-view>
                <flash-list></flash-list>
            </section>
        </section>
        <footer class="footer">
            <div class="container">
                <div class="content has-text-centered">
                    <p>
                        <strong>Sheeta</strong> by <a href="https://moccalotto.github.io">Kim Ravn Hansen</a>.
                    </p>
                    <p>
                        <span class="icon">
                            <i class="fa fa-copyright"></i>
                        </span>
                        2017 Kim Ravn Hansen
                    </p>
                </div>
            </div>
</footer>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
