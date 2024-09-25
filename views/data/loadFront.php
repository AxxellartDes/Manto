<?php require 'views/templates/header.php' ?>

<br>
<br>

<div class="container">

    <div class="card">
        <div class="card-header">
            <legend>Cargar Mantenimientos Preventivos Programados:</legend>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <?php
                    echo $this->mensaje;
                ?>
            </div>
        </div>
        <div class="card-body">
            <form action="<?php echo constant('URL'); ?>dataindicator/loadProgrammed" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="input-group mb-3">

                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="filem" name="filem">
                            <label class="custom-file-label" for="customFileLangHTML" data-browse="Seleccione">Seleccione un documento</label>
                        </div>
                    </div>
                </div>
                <div style="text-align: center;">
                    <input class="btn btn-primary" id="inputGroupFileAddon03" type="submit" value="Cargar programacion">
                </div>
            </form>
        </div>
    </div>

</div>