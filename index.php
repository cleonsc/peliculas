<!DOCTYPE html>
<html lang="es">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="/styles/images/favicon.ico" />
    <title>Peliculas 1.0</title>
    <link href="/styles/bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />      
    <link href="/styles/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />      
    <link href="/styles/index.css" rel="stylesheet" type="text/css" />      
</head>
<body>
    <div class="carousel fade-carousel slide" data-ride="carousel" data-interval="4000" id="bs-carousel">
        <!-- Overlay -->
        <div class="overlay"></div>

        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#bs-carousel" data-slide-to="0" class="active"></li>
            <li data-target="#bs-carousel" data-slide-to="1"></li>
            <li data-target="#bs-carousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item slides active">
                <div class="slide-1"></div>
                <div class="hero">
                    <hgroup>
                        <h1>Somos creativos</h1>        
                        <h3>Empecemos con nuestro próximo proyecto en Angularjs</h3>
                    </hgroup>
                    <button id="inicio" class="btn btn-hero btn-lg" role="button">Inicio</button>
                </div>
            </div>
            <div class="item slides">
                <div class="slide-2"></div>
                <div class="hero">        
                    <hgroup>
                        <h1>Somos capaces</h1>        
                        <h3>Empecemos con nuestro próximo proyecto en Angularjs</h3>
                    </hgroup>       
                    <button id="inicio" class="btn btn-hero btn-lg" role="button">Inicio</button>
                </div>
            </div>
            <div class="item slides">
                <div class="slide-3"></div>
                <div class="hero">        
                    <hgroup>
                        <h1>Somos asombrosos</h1>        
                        <h3>Empecemos con nuestro próximo proyecto en Angularjs</h3>
                    </hgroup>
                    <button id="inicio" class="btn btn-hero btn-lg" role="button">Inicio</button>
                </div>
            </div>
        </div> 
        <script src="/js/angular.min.js" type="text/javascript"></script>
        <script src="/js/angular-route.min.js" type="text/javascript"></script>
        
        <script src="/js/jquery-1.11.1.min.js" type="text/javascript"></script>
        <script src="/js/routing.js" type="text/javascript"></script>
        <script src="/styles/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script>
            $(".btn").each(function () {
                $(".btn").click(function () {
                    window.location.href = "home.html";
                });
            });

        </script>
</body>
</html>





