<?php require 'views/templates/header.php' ?>

<div class="container">
    <?php
    $mensaje = "";
    echo $this->mensaje;
    if(isset($this->dataorder[0]->kit_title)){
        $kittitle = $this->dataorder[0]->kit_title;
    }else{
        $kittitle = "N/A";
    }
    //print_r($this->itemsmaintenance);
    //inputselect
    ?>

    <div class="card glass">
        <h5 class="card-header">Realizar Entrega</h5>
        <div class="card-body">
            <div class="justify-content-md-center">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="numOt"><b>Orden de trabajo: </b> <?php echo $this->dataorder[0]->num_ot ?></label><br>
                    </div>
                    <div class="col">
                        <label class="form-label" for="bus"><b>Bus: </b> <?php echo $this->dataorder[0]->idbus ?></label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label" for="bus"><b>Técnico: </b> <?php echo $this->dataorder[0]->id_user_order ?></label>
                    </div>
                    <?php
                    if(isset($this->alamcenista[0]->id_user_delivery)){
                    ?>
                    <div class="col-6">
                        <label class="form-label" for="bus"><b>Almacenista: </b> <?php echo $this->alamcenista[0]->id_user_delivery ?></label>
                    </div>
                    <?php
                    }
                    ?>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label" for="area"><b>Área de mantenimiento: </b> <?php echo $this->dataorder[0]->area ?></label>
                    </div>
                    <div class="col-6">

                        <div class="mb-3 ">
                                <label class="form-label" for="kit_title"><b>Kit: </b> <?php echo $kittitle ?></label>
                        </div>  
                    </div>
                </div>
                <div>
                    <h3 id="label_kit1" >Repuestos solicitados</h3>
                </div>
                <div class="card glass" id="section_kit">
                    <?php
                        if(isset($this->dataorder[0]->kit_title)){
                    ?>
                    <div class="card-header mb-3">
                        <b>Kit de Repuestos</b> 
                    </div>
                    <table class="col-md-1 col-sm-1 col-xl-12 align-middle ml-3" id="table3" style="margin-left: 3%;">
                        <thead>
                            <th class="col-1" scope="col">Codigo</th>
                            <th scope="col">Descripción</th>
                            <th class="col-2" scope="col">Cantidad</th>
                        </thead>
                        <tbody>
                            <?php 


                                foreach($this->dataorderelements1 as $row){
                                $dataorderelements1 = new Items();
                                $dataorderelements1 = $row;
                            
                            ?>
                            <tr>
                                <th scope="row"><?php echo $dataorderelements1->cod_items; ?></th>
                                <td><?php echo $dataorderelements1->description; ?></td>
                                <td><?php echo $dataorderelements1->cantidad; ?></td>

                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    
                    <div class="card-header mt-3 mb-3" id="section_kit_ped">
                        <b>Lista de pedido</b> 
                    </div>
                    <table class="col-md-1 col-sm-1 col-xl-12 align-middle ml-3" id="table3" style="margin-left: 3%; margin-bottom: 3%;">
                        <thead>
                            <th class="col-1" scope="col">Codigo</th>
                            <th scope="col">Descripción</th>
                            <th class="col-2" scope="col">Cantidad</th>
                        </thead>
                        <tbody>
                            <?php 


                                foreach($this->dataorderelements2 as $row){
                                $dataorderelements2 = new Items();
                                $dataorderelements2 = $row;
                            
                            ?>
                            <tr>
                                <th scope="row"><?php echo $dataorderelements2->cod_items; ?></th>
                                <td><?php echo $dataorderelements2->description; ?></td>
                                <td><?php echo $dataorderelements2->cantidad; ?></td>

                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                        }
                    ?> 
                    <?php
                        if(isset($this->dataorderelements3[0]->cod_items)){
                    ?>        
                    <div class="card-header mt-3 mb-3" id="section_kit_ped">
                        <b>Repuestos adicionales</b> 
                    </div>
                    <table class="col-md-1 col-sm-1 col-xl-12 align-middle ml-3" id="table3" style="margin-left: 3%; margin-bottom: 3%;">
                        <thead>
                            <th class="col-1" scope="col">Codigo</th>
                            <th scope="col">Descripción</th>
                            <th class="col-2" scope="col">Cantidad</th>
                        </thead>
                        <tbody>
                            <?php 


                                foreach($this->dataorderelements3 as $row){
                                $dataorderelements3 = new Items();
                                $dataorderelements3 = $row;
                            
                            ?>
                            <tr>
                                <th scope="row"><?php echo $dataorderelements3->cod_items; ?></th>
                                <td><?php echo $dataorderelements3->description; ?></td>
                                <td><?php echo $dataorderelements3->cantidad; ?></td>

                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                        }
                    ?> 
                </div><br>
                <form action="<?php echo constant('URL'); ?>almacen/saveDelivery" enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="idorder" id="idorder" value="<?php echo $this->dataorder[0]->id_order ?>">
                    <div class="form-group">
                        <label class="mt-3" for="exampleFormControlSelect1"><b>Entregado </b></label>
                        <input class="ms-2" type="checkbox" id="confirm" name="confirm" required><label class="ms-3" for=""><i><b>"Por favor marca la casilla para garantizar que se entregó los repuestos."</b></i> </label>
                    </div>
                    <div class="form-group">
                        <label class="mt-3" for="exampleFormControlSelect1"><b>Novedades (Si no aplica "N/A"):</b></label>
                        <input class="form-control" type="text" id="novelty" name="novelty" value="N/A">
                    </div>
                    <br>
                    <label for="imagen"><b>Evidencia fotografica recibido - Repuestos viejos:</b></label><br>
                    <div class="col-sm-12 col-md-12 evidence mt-3">
                        <input class="form-control mb-3" type="file" name="imagen" id="imagen" accept="image/*" required />
                    </div>
                    
                    <br>                
                    <div style="text-align: center;">
                        <input class="btn btn-primary" type="submit" value="Guardar">
                    </div>
                </form>
                
                
                    
                <div class="row">
                    <div class="col-9">
                        <a href="<?php echo constant('URL'); ?>almacen/inicio">
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