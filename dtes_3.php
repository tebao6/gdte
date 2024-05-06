<?php
function realizarLlamadaCurl($url, $headers) {
    $session = curl_init($url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($session);
    $err = curl_error($session);
    curl_close($session);

    if ($err) {
        throw new Exception("cURL error: " . $err);
    }

    return json_decode($response, true);
}

function procesarTokens($tokens) {
    foreach ($tokens as $token) {
        $token = trim($token);
        echo "<tr>";
        mostrarDatos2($token);
        mostrarDatos($token);
        echo "</tr>";
    }
}

function mostrarDatos2($var) {
    $url = "https://credential.bsale.io/v1/instances/basic/$var.json";
    $headers = ['Accept: application/json', 'Content-Type: application/json', 'Cache: no-store'];

    try {
        $resultado = realizarLlamadaCurl($url, $headers);
        if (isset($resultado['id'], $resultado['name'])) {
            echo "<td>" . $resultado['id'] . "</td><td>" . $resultado['name'] . "</td>";
        } else {
            echo "<td colspan='2'>No hay datos para el token</td>";
        }
    } catch (Exception $e) {
        echo "<td colspan='2'>Error: " . $e->getMessage() . "</td>";
    }
}

function mostrarDatos($var) {
    $url = 'https://api.bsale.cl/v1/documents.json?generationdaterange=[1709251200,1711929599]';
    $headers = ["access_token: $var", 'Accept: application/json', 'Content-Type: application/json'];

    try {
        $resultado = realizarLlamadaCurl($url, $headers);
        if (isset($resultado['count'])) {
            echo "<td>" . $resultado['count'] . "</td><td>" . $var . "</td>";
        } else {
            echo "<td colspan='2'>Sin documentos generados en este periodo para el token</td>";
        }
    } catch (Exception $e) {
        echo "<td colspan='2'>Error: " . $e->getMessage() . "</td>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Extraer</title>
    <script src="./copy.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 20px;
        }

        .table {
            margin-top: 20px;
        }

        th, td {
            text-align: center;
        }

        .text-center {
            margin-bottom: 20px;
        }

        input[type="text"] {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
    <h3>DTE's EMITIDOS</h3>
        <div class="text-center">
            <form action="" method="GET">
                <input type="text" name="tokens" placeholder="Ingrese tokens separados por comas" />
                <input type="submit" name="Buscar" >
            </form>
        </div>
        <table class="table table-sm table-hover">
        <thead> 
                <tr> 
                    <td> cpn </td>
                    <td>Empresa </td>
                    <th>Dte's </th>
                    <td>token </td>
                </tr>
            </thead>  
            <tbody>
                <?php
                if (isset($_GET['tokens'])) {
                    $tokens = explode(',', $_GET['tokens']);
                    procesarTokens($tokens);
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
