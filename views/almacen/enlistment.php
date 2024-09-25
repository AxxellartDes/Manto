<?php require 'views/templates/header.php'; ?>


<div class="container">

    <?php

    // if (!isset($this->bus)) {
    //     header("location:" . constant('URL') . "index/select");
    // }
    $mensaje = "";
    echo $this->mensaje;
    $number_groups = count($this->igroups);
    $number_steps  = $number_groups + 2;
    $total_steps  = $number_steps + 5; 
    $count = 1;
    ?>



    <form id="form" action="<?php echo constant('URL'); ?>index/save" enctype='multipart/form-data' method="POST">


        <!-- Modal Fi2 -->

        <?php
        foreach ($this->pieces as $row) {
            $piece = new Pieces();
            $piece = $row;
        ?>
            <div class="modal fade" id="<?php echo $piece->abr ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><?php echo $piece->description ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <label for="tipo_danio"><b>Tipo de daño</b></label>
                                <?php
                                foreach ($this->hurts as $row) {
                                    $hurt = new Hurts();
                                    $hurt = $row;
                                ?>

                                    <div class="form-check" id="tipo_danio">
                                        <input class="form-check-input" type="radio" name="<?php echo $piece->abr ?>" id="<?php echo $piece->description . "_" . $hurt->idhurt ?>" value="<?php echo $hurt->idhurt ?>">
                                        <label class="form-check-label" for="<?php echo $piece->description . "_" . $hurt->idhurt ?>">
                                            <?php echo $hurt->description ?>
                                        </label>
                                    </div>
                                    <script>
                                        document.getElementById('<?php echo $piece->description . "_" . $hurt->idhurt ?>').addEventListener('click', function(e) {
                                        var boton = file_pc_<?php echo $piece->abr ?>;
                                        console.log(boton);
                                        boton.disabled = false;
                                        $("#info<?php echo $piece->abr ?>").css("display", "none");
                                        });
                                    </script>
                                <?php
                                }
                                ?>
                                <br>
                                <label for="file_pc_<?php echo $piece->abr ?>"><b>Evidencia fotografica</b></label><br>
                                <div class="col-sm-12 col-md-12 evidence mt-3">
                                <?php
                                        $boton = $piece->abr;
                                        if($boton[0]=="P"){
                                    ?>
                                    <input class="form-control mb-3" type="file" name="file_pc_<?php echo $piece->abr ?>" id="file_pc_<?php echo $piece->abr ?>" accept="image/*" required />
                                    <?php
                                    }else{
                                    ?>
                                    <label id="info<?php echo $piece->abr ?>" name="info<?php echo $piece->abr ?>" style="color:red;">*Selecciones un tipo de daño primero</label>
                                    <input disabled class="form-control mb-3" type="file" name="file_pc_<?php echo $piece->abr ?>" id="file_pc_<?php echo $piece->abr ?>" accept="image/*" required />         
                                    <?php
                                    }
                                    ?>   
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

        <div class="card glass">
            <h5 class="card-header">Agregar nuevo reporte de flota - Articulado</h5>
            <div class="card-body">
                <div id="smartwizard">
                    <ul class="nav">
                        <?php
                        for ($i = 1; $i <= $total_steps; $i++) {
                        ?>
                            <li>
                                <a class="nav-link" href="#step-<?php echo $i ?>">
                                </a>
                            </li>
                        <?php
                        }
                        ?>

                    </ul>

                    <div class="tab-content">
                        <div id="step-1" class="tab-pane" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <input type="hidden" name="bus" id="bus" value="<?php echo $this->bus->idbus ?>" />
                                <input type="hidden" name="turn" id="turn" value="<?php echo $this->turn ?>" />
                                <div class="col-sm-12 col-md-4">
                                    <label for="number_bus">Numero del vehiculo</label>
                                    <div id="number_bus">
                                        <?php echo $this->bus->number_bus; ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="vehicle_plate">Placa</label>
                                    <div id="vehicle_plate">
                                        <?php echo $this->bus->vehicle_plate; ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="bus_type">tipo de carroceria</label>
                                    <div id="bus_type">
                                        <?php echo $this->bus->desc_type; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- <br>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <label for="service" class="form-label">Servicio</label>
                                    <input class="form-control" type="text" name="service" id="service" placeholder="servicio">
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <label for="exit_hour" class="form-label">Hora inicio de turno</label>
                                    <input class="form-control" type="time" name="exit_hour" id="exit_hour" required>
                                </div>
                            </div> -->
                            <br>
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group no_border">
                                        <label for="yard" class="form-label">Patio de la inspeccion</label>
                                        <select class="form-select" aria-label="Default select example" name="yard" id="yard" required>
                                            <option hidden value="" selected>Selecciona una opción</option>
                                            <?php foreach ($this->yards as $row) {
                                                $yard = new Yards();
                                                $yard = $row;
                                            ?>
                                                <option value="<?php echo $yard->idyard ?>">
                                                    <?php echo   $yard->description ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="exit_hour" class="form-label">Hora inicio de turno</label>
                                    <input class="form-control" type="time" name="exit_hour" id="exit_hour" required>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="iduser" class="form-label">Km de salida</label>
                                    <input type="number" name="exit_km" id="exit_km" class="form-control" placeholder="<?php echo $this->bus->total_km; ?>" min="0" maxlength="10" aria-label="Kilometraje de salida" required>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="fuel_level" class="form-label">Nivel de Combustible</label>
                                    <select class="form-select" name="fuel_level" id="fuel_level" required>
                                        <option hidden value="" selected>Selecciona una opción</option>
                                        <option value="FULL">FULL</option>
                                        <option value="3/4">3/4</option>
                                        <option value="1/2">1/2</option>
                                        <option value="1/4">1/4</option>
                                    </select>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group" id="div1">
                                        <label for="input_1">¿Como me siento hoy para operar?</label>
                                        <?php foreach ($this->moods as $row) {
                                            $mood = new Moods();
                                            $mood = $row;
                                        ?>
                                            <div class="input-group" id="input_1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="mood" value="<?php echo $mood->idmood ?>" id="Check_mood<?php echo $mood->idmood ?>" <?php if ($mood->idmood == "1") echo 'checked'; ?>>
                                                    <label class="form-check-label" for="Check_mood<?php echo $mood->idmood ?>">
                                                        <span class="material-icons">
                                                            <?php echo $mood->description ?></option>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group" id="div1">
                                        <label for="input_1">Aseo del habitaculo</label>
                                        <?php foreach ($this->cleanings as $row) {
                                            $cleaning = new Cleanings();
                                            $cleaning = $row;
                                        ?>
                                            <div class="input-group" id="input_1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="cleaning" value="<?php echo $cleaning->idcleaning ?>" id="Check_mood<?php echo $cleaning->idcleaning ?>" <?php if ($cleaning->idcleaning == "1") echo 'checked'; ?>>
                                                    <label class="form-check-label" for="Check_mood<?php echo $cleaning->idcleaning ?>">
                                                        <?php echo $cleaning->description ?></option>
                                                    </label>
                                                </div>
                                            </div>

                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        for ($i = 2; $i < $number_steps; $i++) {
                        ?>
                            <div id="step-<?php echo $i ?>" class="tab-pane" role="tabpanel">
                                <div class="form-group" id="div1">
                                    <?php foreach ($this->igroups as $row) {
                                        $igroup = new ItemsGroups();
                                        $igroup = $row;
                                        if ($igroup->iditem_group == $count) {
                                    ?>
                                            <h6><b><?php echo $igroup->description ?></b></h6>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <small class="text-danger mb-3"><strong>Desmarque las casillas para reportar fallas</strong></small>
                                    <br>
                                    <div class="row">
                                        <?php foreach ($this->items as $row) {
                                            $item = new Items();
                                            $item = $row;
                                            if ($item->item_group_iditem_group == $count) {
                                        ?>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="input-group" id="input_1">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="item[]" value="<?php echo $item->iditem ?>" id="Check_item<?php echo $item->iditem ?>" checked>
                                                            <label class="form-check-label" for="Check_item<?php echo $item->iditem ?>">
                                                                <?php echo $item->description ?></option>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $count++;
                        }
                        ?>

                        <div id="step-10" class="tab-pane" role="tabpanel" style="overflow: scroll">
                            <div class="content_lat_ar_i">
                                <?php foreach ($this->pieces as $row) {
                                    $piece = new Pieces();
                                    $piece = $row;
                                    if ($piece->position_idposition == 1) {
                                ?>
                                        <button type="button" class="<?php echo $piece->abr ?> articulado" data-bs-toggle="modal" data-bs-target="#<?php echo $piece->abr ?>">
                                        </button>
                                <?php
                                    }
                                }
                                ?>
                            </div>

                        </div>
                        <div id="step-11" class="tab-pane" role="tabpanel" style="overflow: scroll">
                            <div class="content_lat_ar_d">
                                <?php foreach ($this->pieces as $row) {
                                    $piece = new Pieces();
                                    $piece = $row;
                                    if ($piece->position_idposition == 2) {
                                ?>
                                        <button type="button" class="<?php echo $piece->abr ?> articulado" data-bs-toggle="modal" data-bs-target="#<?php echo $piece->abr ?>">
                                        </button>
                                <?php
                                    }
                                }
                                ?>
                            </div>

                        </div>
                        <div id="step-12" class="tab-pane" role="tabpanel" style="overflow: scroll">
                            <div class="content_front">
                                <?php foreach ($this->pieces as $row) {
                                    $piece = new Pieces();
                                    $piece = $row;
                                    if ($piece->position_idposition == 3 || $piece->position_idposition == 4) {
                                ?>
                                        <button type="button" class="<?php echo $piece->abr ?> articulado" data-bs-toggle="modal" data-bs-target="#<?php echo $piece->abr ?>">
                                        </button>
                                <?php
                                    }
                                }
                                ?>
                            </div>

                        </div>
                        <div id="step-13" class="tab-pane" role="tabpanel" style="overflow: scroll">
                            <div class="content_inf_ar">
                                <?php foreach ($this->pieces as $row) {
                                    $piece = new Pieces();
                                    $piece = $row;
                                    if ($piece->position_idposition == 5) {
                                ?>
                                        <button type="button" class="<?php echo $piece->abr ?> articulado" data-bs-toggle="modal" data-bs-target="#<?php echo $piece->abr ?>">
                                        </button>
                                <?php
                                    }
                                }
                                ?>
                            </div>

                        </div>

                        <div id="step-14" class="tab-pane" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-md-center">


                                <div class="col-sm-12 col-md-10 evidence mt-3">
                                    <input class="form-control mb-3" type="file" name="file[]" id="file" accept="image/*" />
                                </div>

                            </div>
                            <div class="row justify-content-md-center">

                                <div class="col-5 mt-3" style="text-align: center;">
                                    <a class="add_field icon">
                                        <span class="material-icons">
                                            add_a_photo
                                        </span> Agregar Imagen
                                    </a>
                                </div>
                                <div class="col-5 mt-3" style="text-align: center;">
                                    <a class="remove_field icon">
                                        <span class="material-icons">
                                            no_photography
                                        </span> Eliminar Ultimo
                                    </a>

                                </div>

                            </div>
                            <br>
                        </div>
                        <div id="step-15" class="tab-pane" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Observaciones adicionales</label>
                                    <textarea class="form-control" name="observation" id="exampleFormControlTextarea1" rows="3"></textarea>
                                </div>
                            </div>
                            <br>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>

</div>

<script>
    $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
        var stepIndex = $('#smartwizard').smartWizard("getStepIndex");
        if (stepIndex == "13" && stepDirection == "forward") {
            $(".end").show(); // show the button extra only in the last page
        } else {
            $(".end").hide();
        }
    });

    $('.add_field').click(function() {

        var input = $('#file');
        var clone = input.clone(true);
        clone.removeAttr('id');
        clone.val('');
        clone.appendTo('.evidence');

    });

    $('.remove_field').click(function() {

        if ($('.evidence input:last-child').attr('id') != 'file') {
            $('.evidence input:last-child').remove();
        }

    });
</script>

<?php require 'views/templates/footer.php' ?>