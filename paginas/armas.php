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

    .weapon-grid {
        display: grid;
        grid-template-columns: repeat(5, 100px);
        grid-template-rows: repeat(4, 100px);
        gap: 10px;
        justify-content: center;
    }

    .weapon-slot {
        
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

    .weapon-slot:hover {
        border: 2px solid #f00;
        box-shadow: 0px 0px 15px #f00;
        transform: scale(1.30);
    }

    .weapon-slot img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.btn-ff {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        width: 220px;
        padding: 12px 18px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        color: #fff;
        border: none;
        border-radius: 6px;
        background: linear-gradient(90deg, #2b2b2b, #1c1c1c);
        box-shadow: 0 4px 8px rgba(0,0,0,0.6);
        transition: all 0.2s ease;
    }
    .btn-ff:hover {
        transform: scale(1.05);
        background: linear-gradient(90deg, #790d0dff, #2c080bff);
    }
    </style>
</head>
<body>

    <div class="weapon-container">
    <h1 class="title">Selecciona tu arma</h1>
    <div class="weapon-grid">
        <div class="weapon-slot"> <img src="../img/armas/katana.png" alt="katana"></div>
        <div class="weapon-slot"> <img src="../img/armas/kunai.png" alt="kunai"></div>
        <div class="weapon-slot"> <img src="../img/armas/macuahuitl.png" alt=""></div>
        <div class="weapon-slot"> <img src="../img/armas/pistola.png" alt=""></div>
        <div class="weapon-slot"> <img src="../img/armas/sombrero.png" alt=""></div>
        <div class="weapon-slot"> <img src="../img/armas/triblade.png" alt=""></div>
        
    </div>
    <button class="btn-ff" onclick="window.location.href='../lobby.php'"> <i class="fas fa-gun"></i> Volver</button>
    </div>

</body>
</html>
