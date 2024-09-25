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
            
            <div class="col-8">
            <legend>Entrega Manillas</legend> 
                </div>
                <div class="col">
                   <!-- <?php echo $this->numhandles[0]->idcompany ?>  -->
                   <table class="table table-sm table-striped">
                                        <tr>
                                            <td>Empresa</td>
                                            <td>No Manillas</td>
                                        </tr>
                   <?php foreach ($this->numhandles as $row) {
                                        $numhandles = new Companies();
                                        $numhandles = $row;

                                        if($numhandles->description == "1"){
                                            $comp = "Si18 Calle 80"; 
                                            $mas = "17";        
                                        }elseif($numhandles->description == "2"){
                                            $comp = "Si18 Norte";
                                            $mas = "0";         
                                        }elseif($numhandles->description == "3"){
                                            $comp = "Si18 Suba"; 
                                            $mas = "23";         
                                        }elseif($numhandles->description == "4"){
                                            $comp = "Si18 Transversal"; 
                                            $mas = "0";         
                                        }elseif($numhandles->description == "7"){
                                            $comp = "NNFod"; 
                                            $mas = "0";         
                                        }
                                    ?>
                                    
                                        <tr>
                                            
                                            <td><?php echo $comp  ?></td>
                                            <td><?php echo ($numhandles->idcompany)+$mas ?></td>
                                        </tr>
                                    <?php
                   }
                                    ?>
                            
                                    </table>
                </div>
        </div>
        <div class="card-body">
            <div class="row">
                <form action="<?php echo constant('URL'); ?>delivery/searchhandles" method="post">

                    <label for="iduser" class="form-label"><b>Digite la cédula del usuario sin puntos ni comas</b></label>
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
            ?>
                <hr>

                <form action="<?php echo constant('URL'); ?>delivery/savehandles" method="post">
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
                            <input type="hidden" name="numhandles" value="<?php echo $this->user->handles ?>">
                            <label for="company"><b>Número de manillas a entregar:</b></label>
                            <p><?php echo $this->user->handles ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for=""><b>Observaciones</b></label>
                            <?php
                            if ($this->validation > 0) {
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
                    if ($this->validation > 0) {
                    ?>
                        <hr>
                        <div class="alert alert-success" role="alert">
                            El colaborador ya recibio sus manillas
                        </div>
                    <?php
                    } else {
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