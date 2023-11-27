<?php
define('RUTA_FICHEROS', '.\ficheros');

if (empty($_POST)) {
    $ficheros = array_filter(scandir(RUTA_FICHEROS), fn($fichero) => $fichero != "." && $fichero != "..");
} else {
    if (isset($_FILES["fichero"]) && is_uploaded_file($_FILES["fichero"]["tmp_name"])) {
        if (move_uploaded_file($_FILES["fichero"]["tmp_name"], RUTA_FICHEROS . "\\" . $_FILES["fichero"]["name"])) {
            $ficheroSubido = [$_FILES["fichero"]["name"]];
        }
    }
    $ficherosSeleccionados = $_POST['ficherosseleccionados'] ?? [];
    $ficherosFormulario = $_POST['ficheros'] ?? [];
    array_walk($ficherosSeleccionados, function ($ficheroBorrar) {
        unlink(RUTA_FICHEROS . "\\" . $ficheroBorrar);
    });
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
            <form class="form" name="gestión_ficheros" 
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
                            <label><input id="ficheros" type="checkbox" name="ficherosseleccionados[]" value="<?= $fichero ?>" />
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

