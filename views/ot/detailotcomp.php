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
    if (!isset($_SESSION)) {
        session_start();
    }

    $idot = $this->dataorder[0]->num_ot;

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
                }if($this->dataorder[0]->status == "C"){
                ?>
                <form id="form" action="<?php echo constant('URL'); ?>ot/saveotcomp" method="POST">
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label" for="type_service"><b>Orden de Compra:</b> </label>
                                <input class="form-control" type="number" id="ordenc" name="ordenc" value="<?php echo $this->dataorder[0]->ordenc ?>">
                            </div>
                            <div class="col">
                                <label class="form-label" for="type_service"><b>Descripción OC:</b> </label>
                                <input class="form-control" type="text" id="desoc" name="desoc" value="<?php echo $this->dataorder[0]->desoc ?>" readonly>
                            </div>
                            <div class="col-2">
                                <label class="form-label" for="type_service"><b>Precio:</b> </label>
                                <input class="form-control" type="text" id="valor" name="valor" value="$<?php echo number_format($this->dataorder[0]->valor) ?>" readonly>
                                <input class="form-control" type="hidden" id="valordb" name="valordb" value="<?php echo $this->dataorder[0]->valor ?>" readonly>
                            </div>
                            <div class="col-2">
                                <label class="form-label" for="area"><b>Estado:</b></label>
                                <input class="form-control" type="text" id="status" name="status" value="<?php echo $this->dataorder[0]->statusdes ?>" readonly>
                                <input class="form-control" type="hidden" id="idstatus" name="idstatus" value="<?php echo $this->dataorder[0]->status ?>" readonly>
                                <input class="form-control" type="hidden" id="id_ot_m" name="id_ot_m" value="<?php echo $this->idorder ?>">
                            </div>
                            <div class="col-2">
                                <label class="form-label" for="area"><b>Fecha Creación:</b></label>
                                <input class="form-control" type="text" id="created" name="created" value="<?php echo $this->dataorder[0]->created ?>" readonly>
                            </div>
                        </div>
                        <script>
                            // $("#prueba").prop('disabled', true);
                            $(document).ready(function() {
                                $('#ordenc').change(function() {

                                    var datasend = $('#ordenc').val()+'¿<?php echo $idot ?>';

                                    // alert (datasend)
                                    $.ajax({
                                        url: "<?php echo constant('URL'); ?>ot/verifydataoc/" + datasend,
                                        dataType: 'json',
                                        contentType: "application/json; charset=utf-8",
                                        method: "POST",
                                        success: function(data) {
                                            // alert(data);
                                            console.log(data);
                                            if(data[0] > 0){
                                                getData($('#ordenc').val());
                                            }else{

                                                $('#valordb').val('');
                                                $('#valor').val('');
                                                $('#status').val('');
                                                $('#desoc').val('');
                                                $('#created').val('');
                                                $('#idstatus').val('');

                                                alert("La orden de compra "+$(
                                            '#ordenc').val()+" NO se encuantra asociado a la Orden de Trabajo <?php echo $idot ?>. Intenta de nuevo.");
                                            }
                                        }
                                    });
                                    
                                });
                            })

                            function getData(id_oc){
                                // alert("Entra");
                                $.ajax({
                                        url: "<?php echo constant('URL'); ?>ot/getdataoc/" + $('#ordenc').val(),
                                        dataType: 'json',
                                        contentType: "application/json; charset=utf-8",
                                        method: "POST",
                                        success: function(data) {
                                            // console.log(data);
                                            var price = data.price;
                                            price = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'COP' }).format(price, )
                                            $('#valordb').val(data.price);
                                            $('#valor').val('$'+price);
                                            $('#status').val(data.status);
                                            $('#desoc').val(data.description);
                                            $('#created').val(data.created);
                                            $('#idstatus').val(data.idstatus);
                                        }
                                    });
                            }

                        </script>
                        <div class="row mt-3">
                            <div class="col">
                                <a class="btn btn-success" href="<?php echo constant('URL'); ?>almacen/inicio">
                                    Atras
                                </a>
                            </div>
                            <div class="col" style="text-align: end;">
                                <button class="btn btn-success" type="submit">Enviar</button>
                            </div>
                        </div>
                    </form>
                <?php
                }elseif($this->dataorder[0]->ordenc != ""){
                ?>
                <form id="form" action="<?php echo constant('URL'); ?>ot/saveotcomp" method="POST">
                    <div class="row">
                        <div class="col-2">
                            <label class="form-label" for="type_service"><b>Orden de Compra:</b> </label>
                            <label class="form-control" for="type_service"><?php echo $this->dataorder[0]->ordenc ?></label>
                        </div>
                        <div class="col">
                            <label class="form-label" for="type_service"><b>Descripción OC:</b> </label>
                            <label class="form-control" for="type_service"><?php echo $this->dataorder[0]->desoc ?></label>
                        </div>
                        <div class="col-2">
                            <label class="form-label" for="type_service"><b>Precio:</b> </label>
                            <label class="form-control" for="type_service"><?php echo $this->dataorder[0]->valor ?></label>
                        </div>
                        <div class="col-2">
                            <label class="form-label" for="area"><b>Estado:</b></label>
                            <label class="form-control" for="type_service"><?php echo $this->dataorder[0]->statusdes ?></label>
                        </div>
                        <div class="col-2">
                            <label class="form-label" for="area"><b>Fecha Creación:</b></label>
                            <label class="form-control" for="type_service"><?php echo $this->dataorder[0]->created ?></label>
                        </div>
                    </div>
                </form>
                    
                    <div class="row mt-3">
                        <div class="col">
                            <a href="<?php echo constant('URL'); ?>almacen/inicio">
                                <button class="btn btn-primary">Atras</button>
                            </a>
                        </div>
                    </div>
                <?php
                }elseif(1==1){
                ?>
                    <form id="form" action="<?php echo constant('URL'); ?>ot/saveotcomp" method="POST">
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label" for="type_service"><b>Orden de Compra:</b> </label>
                                <input class="form-control" type="number" id="ordenc" name="ordenc">
                            </div>
                            <div class="col">
                                <label class="form-label" for="type_service"><b>Descripción OC:</b> </label>
                                <input class="form-control" type="text" id="desoc" name="desoc" readonly>
                            </div>
                            <div class="col-2">
                                <label class="form-label" for="type_service"><b>Precio:</b> </label>
                                <input class="form-control" type="text" id="valor" name="valor" readonly>
                                <input class="form-control" type="hidden" id="valordb" name="valordb" readonly>
                            </div>
                            <div class="col-2">
                                <label class="form-label" for="area"><b>Estado:</b></label>
                                <input class="form-control" type="text" id="status" name="status" readonly>
                                <input class="form-control" type="hidden" id="idstatus" name="idstatus" readonly>
                                <input class="form-control" type="hidden" id="id_ot_m" name="id_ot_m" value="<?php echo $this->idorder ?>">
                            </div>
                            <div class="col-2">
                                <label class="form-label" for="area"><b>Fecha Creación:</b></label>
                                <input class="form-control" type="text" id="created" name="created" readonly>
                            </div>
                        </div>
                        <script>
                            // $("#prueba").prop('disabled', true);
                            $(document).ready(function() {
                                $('#ordenc').change(function() {

                                    var datasend = $('#ordenc').val()+'¿<?php echo $idot ?>';

                                    // alert (datasend)
                                    $.ajax({
                                        url: "<?php echo constant('URL'); ?>ot/verifydataoc/" + datasend,
                                        dataType: 'json',
                                        contentType: "application/json; charset=utf-8",
                                        method: "POST",
                                        success: function(data) {
                                            // alert(data);
                                            // console.log(data);
                                            if(data[0] > 0){
                                                getData($('#ordenc').val());
                                            }else{

                                                $('#valordb').val('');
                                                $('#valor').val('');
                                                $('#status').val('');
                                                $('#desoc').val('');
                                                $('#created').val('');
                                                $('#idstatus').val('');

                                                alert("La orden de compra "+$(
                                            '#ordenc').val()+" NO se encuantra asociado a la Orden de Trabajo <?php echo $idot ?>. Intenta de nuevo.");
                                            }
                                        }
                                    });
                                    
                                });
                            })

                            function getData(id_oc){
                                // alert("Entra "+$('#ordenc').val());
                                // $.ajax({
                                //         url: "<?php echo constant('URL'); ?>ot/getdataoc/" + $('#ordenc').val(),
                                //         dataType: 'json',
                                //         contentType: "application/json; charset=utf-8",
                                //         method: "POST",
                                //         success: function(data) {
                                //             console.log(data);
                                //             var price = data.price;
                                //             price = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'COP' }).format(price, )
                                //             $('#valordb').val(data.price);
                                //             $('#valor').val('$'+price);
                                //             $('#status').val(data.status);
                                //             $('#desoc').val(data.description);
                                //             $('#created').val(data.created);
                                //             $('#idstatus').val(data.idstatus);
                                //         }
                                //     });
                                $.ajax({
                                    url: "<?php echo constant('URL'); ?>ot/getdataoc/" + $('#ordenc').val(),
                                    dataType: 'json',
                                    contentType: "application/json; charset=utf-8",
                                    method: "POST",
                                    success: function(data) {
                                        console.log(data);
                                        var price = data.price;
                                        price = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'COP' }).format(price);
                                        $('#valordb').val(data.price);
                                        $('#valor').val(price);
                                        $('#status').val(data.status);
                                        $('#desoc').val(data.description);
                                        $('#created').val(data.created);
                                        $('#idstatus').val(data.idstatus);
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("Error: " + error);
                                    },
                                    complete: function(xhr, status) {
                                        console.log("Complete: ", xhr.responseText);
                                    }
                                });


                            }

                        </script>
                        <div class="row mt-3">
                            <div class="col">
                                <a class="btn btn-success" href="<?php echo constant('URL'); ?>almacen/inicio">
                                    Atras
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
                        <a href="<?php echo constant('URL'); ?>ot/listot">
                            <button class="btn btn-primary">Atras</button>
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