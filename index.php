<?php
include("funciones.php");
$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
    $dormitorios = filter_input(INPUT_POST, 'dormitorios', FILTER_SANITIZE_NUMBER_INT);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $tamano = filter_input(INPUT_POST, 'tamano', FILTER_VALIDATE_FLOAT);
    $tipo = $_POST["tipo"];
    $zona = $_POST["zona"];
    $extras = isset($_POST["extras"]) ? $_POST["extras"] : [];
    $foto =isset($_POST ["foto"]);
    $observaciones = $_POST["observaciones"];


    //En esta parte se calculan los beneficios de la vivienda
    if ((!empty($zona))&&(!empty($tamano))&&(!empty($precio))) {
    $beneficio=calcularPrecio($zona, $tamano, $precio);
    }

    // Validación de la dirección
    if (empty($direccion)) {
        $errores['direccion'] = 'La dirección es un campo requerido.';
    } elseif (!preg_match('/^[\p{L}\p{N} \.,\-áéíóúÁÉÍÓÚüÜ]+$/u', $direccion)) {
        $errores['direccion'] = 'La dirección contiene caracteres no válidos.';
    }

    // Validación del número de dormitorios
    if (empty($dormitorios)) {
        $errores["dormitorios"] = "OBLIGATORIO. El número de dormitorios es un campo requerido.";
    }

    // Validación del precio
    if (!is_numeric($precio) || $precio <= 0 || empty($precio)) {
        $errores["precio"] = "OBLIGATORIO. El precio debe ser un valor numérico mayor que 0.";
    }

    // Validación del tamaño
    if (!is_numeric($tamano) || $tamano <= 0 || empty($tamano)) {
        $errores["tamano"] = "OBLIGATORIO. El tamaño en metros cuadrados debe ser un valor numérico mayor que 0.";
    }

    // Validación de la foto
    if ($_FILES["foto"]["error"] == UPLOAD_ERR_NO_FILE) {
        $errores["foto"] = "OBLIGATORIO. Debes seleccionar una foto.";
    } elseif ($_FILES["foto"]["error"] != UPLOAD_ERR_OK) {
        $errores["foto"] = "Error al subir la foto.";
    } elseif ($_FILES["foto"]["size"] > 102400) {  
        $errores["foto"] = "La foto no debe exceder los 100KB.";
    }

    if (empty($errores)) {
        if (file_exists('viviendas.xml')) {
            $xml = simplexml_load_file('viviendas.xml');
        } else {
            // Si el archivo no existe, crea un nuevo objeto SimpleXMLElement
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><viviendas></viviendas>');
        }

        // Crear un nombre único para la imagen
    $imagenNombre = time() . "_" . $_FILES["foto"]["name"];
    
    // Mover la imagen al directorio de imágenes
    $rutaImagen = "img/" . $imagenNombre;
    move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaImagen);
        
        // Crear un nuevo elemento vivienda
        $vivienda = $xml->addChild('vivienda');
        $vivienda->addChild('tipo', $tipo);
        $vivienda->addChild('zona', $zona);
        $vivienda->addChild('direccion', $direccion);
        $vivienda->addChild('dormitorios', $dormitorios);
        $vivienda->addChild('precio', $precio);
        $vivienda->addChild('tamano', $tamano);
        
        // Procesar extras (asumiendo que "extras" es un array)
        if (isset($_POST['extras'])) {
            $extrasElement = $vivienda->addChild('extras');
            foreach ($_POST['extras'] as $extra) {
                $extrasElement->addChild('extra', $extra);
            }
        }
        $vivienda->addChild('foto', $imagenNombre);
        $vivienda->addChild('observaciones', $_POST['observaciones']);
        $vivienda->addChild('beneficio', $beneficio);
        // Guardar el XML en un archivo
        $xml->asXML('viviendas.xml');
        header("Location: inmobiliariaprocesa.php");
        
    }
}
?>
    


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Vivienda</title>
    <link rel="stylesheet" href="css/inmobiliaria.css">
</head>
<body>
    <h1>Formulario de Vivienda</h1>
  
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
    
        <label for="tipo">Tipo de Vivienda:</label>
    <select name="tipo" id="tipo">
        <option value="Piso" <?php echo (isset($tipo) && $tipo == "Piso") ? "selected" : ""; ?>>Piso</option>
        <option value="Adosado" <?php echo (isset($tipo) && $tipo == "Adosado") ? "selected" : ""; ?>>Adosado</option>
        <option value="Chalet" <?php echo (isset($tipo) && $tipo == "Chalet") ? "selected" : ""; ?>>Chalet</option>
        <option value="Casa" <?php echo (isset($tipo) && $tipo == "Casa") ? "selected" : ""; ?>>Casa</option>
    </select>
    <br>

    <label for="zona">Zona:</label>
    <select name="zona" id="zona">
        <option value="Centro" <?php echo (isset($zona) && $zona == "Centro") ? "selected" : ""; ?>>Centro</option>
        <option value="Zaidín" <?php echo (isset($zona) && $zona == "Zaidín") ? "selected" : ""; ?>>Zaidín</option>
        <option value="Chana" <?php echo (isset($zona) && $zona == "Chana") ? "selected" : ""; ?>>Chana</option>
        <option value="Albaicín" <?php echo (isset($zona) && $zona == "Albaicín") ? "selected" : ""; ?>>Albaicín</option>
        <option value="Sacromonte" <?php echo (isset($zona) && $zona == "Sacromonte") ? "selected" : ""; ?>>Sacromonte</option>
        <option value="Realejo" <?php echo (isset($zona) && $zona == "Realejo") ? "selected" : ""; ?>>Realejo</option>
    </select>
    <br>


        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" value="<?php echo isset($direccion) ? htmlspecialchars($direccion) : ''; ?>"><br>
        <span class="error"><?php echo isset($errores['direccion']) ? $errores['direccion'] : ''; ?></span>
        <br>

        <label for="dormitorios">Número de Dormitorios:</label>
        <?php
        $dormitoriosOptions = ['1', '2', '3', '4', '5'];
        foreach ($dormitoriosOptions as $option) {
            echo '<input type="radio" name="dormitorios" value="' . $option . '"';
            if (isset($dormitorios) && $dormitorios == $option) {
                echo ' checked';
            }
            echo '> ' . $option . ' ';
        }
        ?>
        <br><span class="error"><?php echo isset($errores['dormitorios']) ? $errores['dormitorios'] : ''; ?></span>
        <br>

        <label for="precio">Precio:</label>
        <input type="text" name="precio" id="precio" value="<?php echo isset($precio) ? htmlspecialchars($precio) : ''; ?>"> €
        <br><span class="error"><?php echo isset($errores['precio']) ? $errores['precio'] : ''; ?></span>
        <br>

        <label for="tamano">Tamaño:</label>
        <input type="text" name="tamano" id="tamano" value="<?php echo isset($tamano) ? htmlspecialchars($tamano) : ''; ?>"> metros cuadrados
        <br><span class="error"><?php echo isset($errores['tamano']) ? $errores['tamano'] : ''; ?></span>
        <br>

        <label>Extras:</label>
        <?php
        $extrasOptions = ['Piscina', 'Jardín', 'Garage'];
        foreach ($extrasOptions as $option) {
            echo '<input type="checkbox" name="extras[]" value="' . $option . '"';
            if (isset($extras) && in_array($option, $extras)) {
                echo ' checked';
            }
            echo '> ' . $option . ' ';
        }
        ?>
        <br><br>

        <label for="foto">Foto:</label>
        <input type="file" name="foto" id="foto">
        <br><span class="error"><?php echo isset($errores['foto']) ? $errores['foto'] : ''; ?></span>
        <br>

        <label for="observaciones">Observaciones:</label>
        <textarea name="observaciones" id="observaciones" rows="4" cols="50"><?php echo isset($observaciones) ? htmlspecialchars($observaciones) : ''; ?></textarea>
        
        <br>
        
        <input type="submit" name="submit" value="Guardar Vivienda">
    </form>
</body>
</html>