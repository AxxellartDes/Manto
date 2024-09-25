<?php require 'views/templates/header.php';
// print_r($this->dataserviceorder);
?>

<div class="container">
    <?php
    $mensaje = "";
    echo $this->mensaje;
    if(isset($this->dataorder[0]->kit_title)){
        $kittitle = $this->dataorder[0]->kit_title;
    }else{
        $kittitle = "N/A";
    }
    if (!isset($_SESSION)) {
        session_start();
    }
    //print_r($this->itemsmaintenance);
    //inputselect
    ?>

    <div class="card glass">
        <div class="col">
            <h5 class="card-header">Detalles Pedido - <?php echo $this->dataorder[0]->statusdes ?></h5>
        </div>
        <div class="card-body">
            <div class="justify-content-md-center">
                <div class="row">
                    <div class="col">
                        <label class="form-control" for="numOt"><b>Orden de trabajo: </b> <?php echo $this->dataorder[0]->num_ot ?></label><br>
                    </div>
                    <div class="col">
                        <label class="form-control" for="bus"><b>Bus: </b> <?php echo $this->dataorder[0]->id_bus ?></label>
                    </div>
                    <div class="col">
                        <label class="form-control" for="bus"><b>Almacenista: </b> <?php echo $this->dataorder[0]->id_user_alm ?></label>
                    </div>
                    <div class="col">
                        <label class="form-control" for="bus"><b>Fecha Registro: </b> <?php echo $this->dataorder[0]->date_register ?></label>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-control" for="area"><b>Proveedor: </b> <?php echo $this->dataorder[0]->suppliers ?></label>
                    </div>
                </div>
                <h3>Servicios Solicitados:</h3>
                <div class="row">
                    <div class="col mb-3 ">
                        <label class="form-label" for="kit_title"><b>Tipo de Servicio: </b></label>
                    </div>
                    <div class="col">
                        <label class="form-label" for="kit_title"><b>Cantidad: </b></label>
                    </div> 
                    <div class="col mb-3 ">
                        <label class="form-label" for="kit_title"><b>Descripción del servicio: </b></label>
                    </div> 
                </div>
                <?php 
                    foreach($this->dataserviceorder as $row){
                    $dataserviceorder = new Ots();
                    $dataserviceorder = $row;
                ?>
                <div class="row">
                    <div class="col mb-3 ">
                        <label class="form-control" for="kit_title"><?php echo $dataserviceorder->type_service ?></label>
                    </div>
                    <div class="col">
                        <label class="form-control" for="kit_title"><?php echo $dataserviceorder->cantidad ?></label>
                    </div> 
                    <div class="col mb-3 ">
                        <label class="form-control" for="kit_title"><?php echo $dataserviceorder->description ?></label>
                    </div> 
                </div>
                <?php
                }
                if($_SESSION['rol_idrol'] == 5 and $this->dataorder[0]->status == 5){
                ?>
                <form id="form" action="<?php echo constant('URL'); ?>ot/saveotcomp" method="POST">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="type_service">Orden de Compra: </label>
                            <label class="form-control" for="kit_title"><b>Orden de Compra: </b> <?php echo $this->dataorder[0]->ordenc ?></label>
                            <input class="form-control" type="hidden" id="ordenc" name="ordenc" value="<?php echo $this->dataorder[0]->ordenc ?>">
                        </div>
                        <div class="col">
                            <label class="form-label" for="type_service">Precio: </label>
                            <input class="form-control" type="hidden" id="valor" name="valor" value="<?php echo $this->dataorder[0]->valor ?>">
                            <label class="form-control" for="kit_title"><b>Precio: </b> <?php echo $this->dataorder[0]->valor ?></label>
                            <input class="form-control" type="hidden" id="id_ot" name="id_ot" value="<?php echo $this->dataorder[0]->id_ot_missing ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="area">Estado</label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="status" id="status" required>
                                    <option hidden value="<?php echo $this->dataorder[0]->status ?>" selected><?php echo $this->dataorder[0]->statusdes ?></option>
                                    <option  value="5">Enviado a proveedor</option>
                                    <option  value="6">Finalizada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <a href="<?php echo constant('URL'); ?>ot/listotcomp">
                                <button class="btn btn-primary" type="submit">Atras</button>
                            </a>
                        </div>
                        <div class="col" style="text-align: end;">
                            <button class="btn btn-success" type="submit">Enviar</button>
                        </div>
                    </div>
                </form>
                <?php
                }elseif($_SESSION['rol_idrol'] == 5 and $this->dataorder[0]->status == 4){
                    ?>
                    <form id="form" action="<?php echo constant('URL'); ?>ot/saveotcomp" method="POST">
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="type_service">Orden de Compra: </label>
                                <input class="form-control" type="number" id="ordenc" name="ordenc">
                            </div>
                            <div class="col">
                                <label class="form-label" for="type_service">Precio: </label>
                                <input class="form-control" type="number" id="valor" name="valor">
                                <input class="form-control" type="hidden" id="id_ot" name="id_ot" value="<?php echo $this->dataorder[0]->id_ot_missing ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="area">Estado</label>
                                <div class="input-group">
                                    <select class="form-select" aria-label="Default select example" name="status" id="status" required>
                                        <option hidden value="<?php echo $this->dataorder[0]->status ?>" selected><?php echo $this->dataorder[0]->statusdes ?></option>
                                        <option  value="5">Enviado a proveedor</option>
                                        <option  value="6">Finalizada</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <a href="<?php echo constant('URL'); ?>ot/listotcomp">
                                    <button class="btn btn-primary" type="submit">Atras</button>
                                </a>
                            </div>
                            <div class="col" style="text-align: end;">
                                <button class="btn btn-success" type="submit">Enviar</button>
                            </div>
                        </div>
                    </form>
                    <?php
                    }else{
                    ?>
                <div class="row mt-3">
                    <div class="col">
                        <a href="<?php echo constant('URL'); ?>almacen/inicio">
                            <button class="btn btn-primary" type="submit">Atras</button>
                        </a>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
            <br>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------ -->
    <br>

</div>

<?php require 'views/templates/footer.php' ?>