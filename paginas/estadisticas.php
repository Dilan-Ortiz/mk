<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: radial-gradient(#291111, #000);
        font-family: Arial, sans-serif;
    }

    .stats-container {
        background: rgba(50, 50, 50, 0.9);
        border: 4px solid #aaa;
        border-radius: 8px;
        box-shadow: 0px 0px 20px rgba(255, 0, 0, 0.6);
        padding: 20px;
        width: 90%;
        max-width: 1000px;
    }

    .title {
        color: #c00;
        text-align: center;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px #000;
        font-size: 1.8rem;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
        color: #fff;
        background: rgba(0, 0, 0, 0.6);
        border-radius: 6px;
        overflow: hidden;
    }

    .table thead {
        background: #1c1c1c;
        color: white;
    }

    .table th, .table td {
        padding: 12px;
        border-bottom: 1px solid #444;
    }

    .table tbody tr {
        background: rgba(40, 0, 0, 0.7);
        transition: background 0.3s;
    }

    .table tbody tr:nth-child(even) {
        background: rgba(60, 0, 0, 0.6);
    }

    .table tbody tr:hover {
        background: rgba(120, 0, 0, 0.8);
    }

    .btn-ff {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 220px;
        padding: 12px 18px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        color: #ee1818ff;
        border: none;
        border-radius: 6px;
        background: linear-gradient(90deg, #2b2b2b, #1c1c1c);
        box-shadow: 0 4px 8px rgba(0,0,0,0.6);
        transition: all 0.2s ease;
        margin: 20px auto 0 auto;
        cursor: pointer;
    }

    .btn-ff:hover {
        transform: scale(1.05);
        background: linear-gradient(90deg, #790d0dff, #2c080bff);
    }

    .table-responsive {
        overflow-x: auto;
    }

    </style>
</head>
<body>
    <div class="stats-container">
    <h2 class="title">Estadísticas por Partida</h2>

    <div class="table-responsive">
        <table class="table">
        <thead>
            <tr>
            <th>Nivel</th>
            <th>Puntos Actuales</th>
            <th>Avatar</th>
            <th>Arma Utilizada</th>
            <th>Cantidad Balas</th>
            <th>Puntos Ganados</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
        <tr>
        </tbody>
        </table>
    </div>

    <div class="text-center">
        <button class="btn-ff" onclick="window.location.href='/mk/lobby.php'">Volver al Lobby</button>
    </div>
    </div>
</body>
</html>
