<?php require 'views/templates/header.php' ?>

<div class="container">
    <?php
    $mensaje = "";
    echo $this->mensaje;
    //print_r($this->itemsmaintenance);
    //inputselect
    ?>

    <div class="card glass">
        <h5 class="card-header">Detalle de registro</h5>
        <div class="card-body">
            <div class="justify-content-md-center">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-control" for="numOt"><b>Número de bus: </b> <?php echo $this->dataregister[0]->bus ?></label><br>
                    </div>
                    <div class="col">
                        <label class="form-control" for="bus"><b>Fecha: </b> <?php echo $this->dataregister[0]->date ?></label>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-control" for="area"><b>ID Usuario: </b> <?php echo $this->dataregister[0]->id_user ?></label>
                    </div>
                    <div class="col-6">

                        <div class="mb-3 ">
                                <label class="form-control" for="kit_title"><b>Usuario: </b> <?php echo $this->dataregister[0]->user ?></label>
                        </div>  
                    </div>
                </div>
                <div>
                    <h3 id="label_kit1" >Novedades:</h3>
                </div>
                
                <table class="align-middle table table-condensed table-hover table-light table-striped table-bordered table-sm" id="table3">
                    <thead>
                        <th class="col-1" scope="col">Id</th>
                        <th scope="col">Item</th>
                        <th class="col-2" scope="col">Observación</th>
                        <th class="col-2" scope="col">Tiempo</th>
                    </thead>
                    <tbody>
                        <?php 

                            foreach($this->dataregister as $row){
                            $dataregister = new Enlistment();
                            $dataregister = $row;
                        
                        ?>
                        <tr>
                            <th scope="row"><?php echo $dataregister->id_data; ?></th>
                            <td><?php echo $dataregister->item; ?></td>
                            <td><?php echo $dataregister->observation; ?></td>
                            <td><?php echo $dataregister->time; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>

                <div class="mb-3">
                    <div>
                        <label class="form-label" for="area"><b>Faltantes: </b></label>
                    </div>
                    <div>
                        <label class="form-control" for="kit_title"><?php echo $this->dataregister[0]->missing ?></label>  
                    </div>
                </div>
                    
                <div class="row mt-3">
                    <div class="col-9">
                        <a href="<?php echo constant('URL'); ?>enlistment/listRegister">
                            <button class="btn btn-success" type="submit">Atras</button>
                        </a>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------ -->
    <br>

</div>

<?php require 'views/templates/footer.php' ?>