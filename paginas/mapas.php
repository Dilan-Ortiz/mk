<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Weapon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css" rel="stylesheet">

    <style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: radial-gradient(#291111, #000);
    }

    .weapon-container {
        background: rgba(50, 50, 50, 0.9);
        border: 4px solid #aaa;
        border-radius: 8px;
        box-shadow: 0px 0px 20px rgba(255, 0, 0, 0.6);
        padding: 20px;
    }

    .title {
        color: #c00;
        text-align: center;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px #000;
    }

    .card-img-top {
        border: 2px solid #666;
        border-radius: 6px;
        box-shadow: 0px 0px 10px #000;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden; 
    }

    .card-img-top:hover {
        border: 2px solid #f00;
        box-shadow: 0px 0px 15px #f00;
        transform: scale(1.30);
    }

    .card-img-top img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    </style>
</head>
<body>

    <div class="weapon-container">
        <h1 class="title">Selecciona un mapa</h1>
        <div class="d-flex gap-4 m-4 justify-content-center">
            <div class="card" style="width: 26rem;">
                <img src="../img/mapa1.png" class="card-img-top" alt="">
            </div>
            <div class="card" style="width: 26rem;">
                <img src="../img/mapa2.png" class="card-img-top" alt="">
            </div>
        </div>
    </div>

</body>
</html>
