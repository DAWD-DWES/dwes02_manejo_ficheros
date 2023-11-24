<?php
define('RUTA_FICHEROS', '.\ficheros');

if (empty($_POST)) {
    $ficheros = array_filter(scandir(RUTA_FICHEROS), fn($fichero) => $fichero != "." && $fichero != "..");
} else {
    if (filter_has_var(INPUT_POST, 'enviar')) {
        if (isset($_FILES["fichero"]) && is_uploaded_file($_FILES["fichero"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["fichero"]["tmp_name"], RUTA_FICHEROS . "\\" . $_FILES["fichero"]["name"])) {
                $ficheroSubido = [$_FILES["fichero"]["name"]];
            }
        }
    }
    $ficherosSeleccionados = filter_input(INPUT_POST, 'ficherosSeleccionados', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
    $ficherosFormulario = filter_input(INPUT_POST, 'ficheros', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
    foreach ($ficherosSeleccionados as $ficheroBorrar) {
        unlink(RUTA_FICHEROS . "\\" . $ficheroBorrar);
    }
    $ficheros = array_unique(array_diff(array_merge($ficherosFormulario, ($ficheroSubido ?? []),), $ficherosSeleccionados));
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="stylesheet.css">
        <title>Gestión de ficheros</title>
    </head>
    <body>
        <div class="page">
            <h1>Gestión de ficheros</h1>
            <form class="form" name="form_cambio_de_base" 
                  action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="fichero">Nuevo Fichero:</label> 
                    <input id="fichero" type="file" name="fichero" />
                </div>
                <fieldset>         
                    <?php if (empty($ficheros)): ?>
                        <p>El directorio está vacío</p>
                    <?php else: ?>
                        <legend>Seleccione para borrar:</legend>
                        <?php foreach ($ficheros as $fichero): ?>
                            <label><input id="ficheros" type="checkbox" name="ficherosSeleccionados[]" value="<?= $fichero ?>" />
                                <?= $fichero ?></label><br>
                            <input type="hidden" name="ficheros[]" value="<?= $fichero ?>" />
                        <?php endforeach ?>
                    <?php endif ?>
                </fieldset>
                <div class="submit-seccion">
                    <input class="submit" type="submit" 
                           value="Enviar" name="enviar" /> 
                </div>
            </form> 
        </div>  
    </body>
</html>
</html>

