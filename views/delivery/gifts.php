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
            <legend>Entrea Juguetes</legend>
            <div class="col">
                   <table class="table table-sm table-striped">
                                        <tr>
                                            <td>Empresa</td>
                                            <td>No Regalos</td>
                                        </tr>
                   <?php foreach ($this->numGift as $row) {
                                        $numGift = new Companies();
                                        $numGift = $row;

                                        if($numGift->description == "1"){
                                            $comp = "Si18 Calle 80";          
                                        }elseif($numGift->description == "2"){
                                            $comp = "Si18 Norte";          
                                        }elseif($numGift->description == "3"){
                                            $comp = "Si18 Suba";          
                                        }elseif($numGift->description == "4"){
                                            $comp = "Si18 Transversal";          
                                        }elseif($numGift->description == "7"){
                                            $comp = "NNfood";          
                                        }
                                    ?>
                                    
                                        <tr>
                                            <td><?php echo $numGift->idcompany ?></td>
                                            <td><?php echo $comp  ?></td>
                                        </tr>
                                    <?php
                   }
                                    ?>
                            
                                    </table>
                </div>
        </div>
        <div class="card-body">
            <div class="row">
                <form action="<?php echo constant('URL'); ?>delivery/searchgift" method="post">

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
                $num = "1";
            ?>
                <hr>

                <form action="<?php echo constant('URL'); ?>delivery/savegift" method="post">
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
                            <p><?php echo $this->user->phone;?></p>
                        </div>
                    </div>
                    <div class="row">
                        <h4>Hijos</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Edad </th>
                                    <th scope="col">Regalo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($this->children as $row){
                                    $children = new Users();
                                    $children = $row;
                                ?>
                                <tr>
                                    <td scope="row"><?php echo $num ?></td>
                                    <td><?php echo $children->name ?></td>
                                    <td><?php echo $children->age ?></td>
                                    <td><?php echo $children->email ?></td>
                                </tr>
                                <?php
                                    $num++;
                                }
                                ?>
                            </tbody>
                        </table>
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
                            El colaborador ya recibio su obsequio
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