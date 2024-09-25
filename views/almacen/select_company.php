<?php
require 'views/templates/headerloginalm.php';

$mensaje = '';
$mensaje_1 = '';
$mensaje_2 = '';
?>

<div class="container glass">
    <br>
    <div class="row ml-5">
    </div>
    <h1>Selección de Almacen</h1>
    <div class="ml-3 mb-3">
        <?php
                if ($this->mensaje != "") 
                echo $this->mensaje;
        ?>
    </div>
    <div class="table-responsive">
        <form action="<?php echo constant('URL'); ?>almacen/listOrderAlm" method="POST">
            <div class="col">
                <label class="form-label" for="numOt">Selecciona la ubicación del almacen</label><br>
                <select class="form-select" aria-label="Default select example" name="store" id="store" required>
                    <option value="2" selected>Almacén Bachue</option>    
                    <option value="4" selected>Almacén Patio 183</option>    
                    <option value="1" selected>Almacén Suba</option>    
                    <option value="3" selected>Almacén 80</option>    
                </select>     
            </div>
            
            <input class="btn btn-success mt-5" type="submit" value="Continuar">
        </form>
    </div>
    
    <br>
</div>

<?php require 'views/templates/footer.php' ?>