<?php require 'views/templates/header.php' ?>

<br>
<br>

<div class="container">

    <?php
    $mensaje = "";
    echo $this->mensaje;
    ?>

    <div class="card glass">
        <div class="card-header">
            <div class="row">
                <div class="col-8">
                  <legend>Entrega de Anchetas</legend>  
                </div>
                   <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <td scope="col"><b>Empresa</b></td>
                                            <td scope="col"><b>Total Anchetas</b></td>
                                            <td scope="col"><b>Ancheta Entregadas</b></td>
                                            <td scope="col"><b>Ancheta Faltantes</b></td>
                                        </tr>
                                    </thead>
                   <?php 
                                    $total = 0;
                                    $totalentregas = 0;
                                    $totalfaltantes = 0;

                                    foreach ($this->numanchetas as $row) {
                                        $numanchetas = new Companies();
                                        $numanchetas = $row;

                                        if($numanchetas->idcompany == "1"){
                                            $comp = "Si18 Calle 80";          
                                        }elseif($numanchetas->idcompany == "2"){
                                            $comp = "Si18 Norte";          
                                        }elseif($numanchetas->idcompany == "3"){
                                            $comp = "Si18 Suba";          
                                        }elseif($numanchetas->idcompany == "4"){
                                            $comp = "Si18 Transversal";          
                                        }
                                    ?>
                                    
                                        <tr>
                                            <td><?php echo $comp  ?></td>
                                            <td><?php echo $numanchetas->comment ?></td>
                                            <td><?php echo $numanchetas->description ?></td>
                                            <td><?php echo $numanchetas->comment - $numanchetas->description ?></td>
                                        </tr>
                                    <?php
                                    $total = $total + $numanchetas->comment;
                                    $totalentregas = $totalentregas + $numanchetas->description;
                                    $totalfaltantes = $totalfaltantes + ($numanchetas->comment - $numanchetas->description);
                   }
                                    ?>
                                        <tfoot>
                                            <tr>
                                                <td scope="col"><b>Total</b></td>
                                                <td scope="col"><b><?php echo $total  ?></b></td>
                                                <td scope="col"><b><?php echo $totalentregas  ?></b></td>
                                                <td scope="col"><b><?php echo $totalfaltantes  ?></b></td>
                                            </tr>
                                        </tfoot>
                                    </table>
            
            
            </div>
            
        </div>
        <div class="card-body">
            <div class="row">
                <form action="<?php echo constant('URL'); ?>delivery/search" method="post">

                    <label for="iduser" class="form-label"><b>Digite la cedula del usuario sin puntos ni comas</b></label>
                    <div class="input-group mb-3">
                        <input type="number" name="iduser" id="iduser" class="form-control" placeholder="Cedula de ciudadania" required>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>

            <?php
            if (isset($this->user)) {
                if($this->user->status != 1 AND $this->user->status != 3){

            ?>
                <div class="alert alert-danger" role="alert">
                    El usuario no recibe ancheta.
                </div>
                <?php
                }
                ?>
                <hr>
                
                <form action="<?php echo constant('URL'); ?>delivery/save" method="post">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <input type="hidden" name="iduser" value="<?php echo $this->user->idusers ?>">
                            <label for=""><b>Nombre</b></label>
                            <p><?php echo $this->user->name ?></p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for=""><b>Empresa</b></label>
                            <p><?php echo $this->user->company ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label for=""><b>Telefono</b></label>
                            <p><?php echo $this->user->phone ?></p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="company"><b>Lugar de entrega</b></label>
                            <?php
                            if ($this->user->status == 3) {
                            ?>
                                <p><?php echo $this->user->installation ?></p>
                            <?php
                            } else {
                            ?>
                                <select class="form-select" name="company" id="company" required>
                                    <option hidden value="" selected>Selecciona una opción</option>
                                    <?php foreach ($this->company as $row) {
                                        $company = new Companies();
                                        $company = $row;
                                    ?>
                                        <option value="<?php echo $company->idcompany ?>">
                                            <?php echo $company->description ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for=""><b>Observaciones</b></label>
                            <?php
                            if ($this->user->status == 3) {
                            ?>
                                <p><?php echo $this->user->comment ?></p>
                        </div>
                        <div class="col">
                            <label for=""><b>Entregado por</b></label>
                            <p><?php echo $this->user->manager ?></p>
                        </div>
                    </div>
                            <?php
                            } else {
                            ?>
                                <textarea class="form-control" name="comment" maxlength="512" placeholder="Maximo 512 caracteres" aria-label=""></textarea>
                                <br>
                        </div>
                    </div>
                            <?php
                            }
                            ?>
                    <?php
                    if ($this->user->status == 3) {
                    ?>
                        <hr>
                        <div class="alert alert-success" role="alert">
                            El colaborador ya recibio su obsequio
                        </div>
                    <?php
                    } elseif($this->user->status == 1) {
                    ?>
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" type="submit">Entregado</button>
                        </div>
                    <?php
                    }
                    ?>
                </form>
            <?php
            }
            ?>
        </div>

    </div>

    <br>

    <div class="alert alert-secondary" role="alert">
        <strong>¿necesitas soporte? comunicate Con Bienestar al
            <a href="https://api.whatsapp.com/send?phone=573242817865" target="_blank">324 281 7865</a>
        </strong>
    </div>
    <!-- Adicionalmente recuerde que despues del xx-xx-xxxx no se podran realizar modificaciones a al eleccion realizada -->

</div>

<?php require 'views/templates/footer.php' ?>