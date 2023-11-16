<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/cssddephp.css">
</head>
<body>
<?php

// Cargar el archivo XML
$xml = simplexml_load_file('viviendas.xml');

// Acceder a los datos del XML
foreach ($xml->vivienda as $vivienda) {
    $tipo = (string)$vivienda->tipo;
    $zona = (string)$vivienda->zona;
    $direccion = (string)$vivienda->direccion;
    $dormitorios = (int)$vivienda->dormitorios;
    $precio = (float)$vivienda->precio;
    $tamano = (float)$vivienda->tamano;
    $extras = (array)$vivienda->extras;
    $foto = (string)$vivienda->foto;
    $observaciones = (string)$vivienda->observaciones;
    $beneficio=(float)$vivienda->beneficio;

    // Realiza las operaciones que necesites con estos datos
    // Por ejemplo, mostrarlos en la página o realizar cálculos
    echo "<h2>Datos de la Vivienda:</h2>";
        echo "<p>Estos son los datos introducidos</p>";
        echo"<ul>";
        echo "<li><p><strong>Tipo de Vivienda:</strong> $tipo</p></li>";
        echo "<li><p><strong>Zona:</strong> $zona</p></li>";
        echo "<li><p><strong>Dirección:</strong> $direccion</p></li>";
        echo "<li><p><strong>Número de Dormitorios:</strong> $dormitorios</p></li>";
        echo "<li><p><strong>Precio:</strong> $precio €</p></li>";
        echo "<li><p><strong>Tamaño en Metros Cuadrados:</strong> $tamano metros cuadrados</p></li>";
        echo "<li><p><strong>Extras:</strong> ". implode(', ', $extras) ."</p></li>";
        echo "<li><p><strong>Foto:</strong> <a href='img/$foto' target='_blank'>$foto</a></p></li>";
        echo "<li><p><strong>Observaciones:</strong> $observaciones</p></li>";
        echo "<li><p><strong>Ganancias:</strong>$beneficio</p></li>";
        echo "</ul>";
       


    
}
echo "<a href='index.php'>Insertar otra vivienda</a>";
    ?>
</body>
</html>

   
