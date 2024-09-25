<?php
require 'views/templates/header.php';

$mensaje = '';
$mensaje_1 = '';
$mensaje_2 = '';

if (!isset($_SESSION)) {
    session_start();
}

$rol = $_SESSION['rol_idrol'];

// echo "<pre>";
// print_r($this->myots);
// echo "</pre>";
?>

<div class="container glass">
    <br>
    <div class="row ml-5">
    </div>
    <h1>Solicitudes Compra Directa Realizados</h1>
    <div class="ml-3 mb-3">
        <?php
                if ($this->mensaje != "") 
                echo $this->mensaje;
        ?>
    </div>
    <ul class="mb-4">
        <li>Si no se ingresan fechas se toma todas las fechas.</li>
        <li>Ver todos los estados - Lista todos los registros sin filtro de estado de OC.</li>
    </ul> 
    <div class="col-12 col-lg-12">
        <form class="form" action="" method="POST">
            <div class="row">
                <div class="col">
    
                </div>
                <div class="col-12 col-md-3">
                    <label for=""><b>Estado de OC:</b></label>
                    <select class="form-select" name="id_status" id="id_status" required>
                        <option value="1">Ver todos los estados </option>
                        <option value="4">En espera de envío a proveedor</option>
                        <option value="A">Aprobado</option>
                        <option value="AG">Compra Aprobada</option>
                        <option value="AR">Requerimiento Aprobado</option>
                        <option value="C">Cancelado</option>
                        <option value="CR">Completamente recibido</option>
                        <option value="R">Esperando Aprobación</option>
                        <option value="RP">Parcialmente Recibido</option>
                        <option value="RZ">Rechazada</option>
                        <option value="U">Incompleto</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="number_bus"><strong>Fecha/Inicial</strong></label>
                    <input type="date" id="seachByDate" name="initial_date" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2" required>
                </div>
                <div class="col-12 col-md-3">
                    <label for="number_bus"><strong>Fecha/Final</strong></label>
                    <input type="date" id="seachByDate2" name="final_date" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2" required>
                </div>
                <div class="col-12 col-md-2 align-self-end">
                    <?php
                        if($rol == 5){
                    ?>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" type="submit" onclick=this.form.action="<?php echo constant('URL'); ?>ot/MyOtCompFil" id="button-addon2">Buscar</button>
                        <button class="btn btn-success btn-sm" type="submit" onclick=this.form.action="<?php echo constant('URL'); ?>ot/exportMyOtCompFil" id="button-addon2">Exportar</button>
                    </div>
                    <?php
                        }elseif($rol == 6){
                    ?>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" type="submit" onclick=this.form.action="<?php echo constant('URL'); ?>ot/MyOtMantFil" id="button-addon2">Buscar</button>
                        <button class="btn btn-success btn-sm" type="submit" onclick=this.form.action="<?php echo constant('URL'); ?>ot/exportMyOtMantFil" id="button-addon2">Exportar</button>
                    </div>
                    <?php
                        }
                    ?>
                </div>
                <div class="col">
    
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Orden de Trabajo</th>
                    <th scope="col">Orden de Compra</th>
                    <th scope="col">Bus </th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha Solicitud</th>
                    <th scope="col">Fecha OC</th>
                    <th scope="col">Acción</th>
                </tr>
            </thead>
            <tbody>
                    <?php 
                        foreach($this->myots as $row){
                        $myots = new Ots();
                        $myots = $row;
                    ?>
                    <tr>
                        <th scope="row"><?php echo $myots->id_ot_missing; ?></th>
                        <td><?php echo $myots->num_ot; ?></td>
                        <?php
                            if($myots->ordenc != ""){
                        ?>
                        <td><?php echo $myots->ordenc; ?></td>
                        <?php
                            }else{
                        ?>
                        <td>Sin Asignar</td>
                        <?php
                            }
                        ?>
                        <td><?php echo $myots->id_bus; ?></td>
                        <td><?php echo $myots->status; ?></td>
                        <td><?php echo $myots->created; ?></td>
                        <td><?php echo $myots->createdoc; ?></td>
                        
                        <td class="iconos">
                            <?php
                            if($rol == 6){
                            ?>
                            <small>
                                <div class="row" style="margin-right: auto; margin-left: auto; place-content: center; min-inline-size: max-content;">
                                    <div>
                                        <?php
                                        if($myots->status == 'Enviado a proveedor'){
                                        ?>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'ot/detailOt/' . $myots->id_ot_missing; ?>">
                                        visibility
                                        </a> 
                                        <?php
                                        }elseif($myots->status == 'Finalizada'){
                                        ?>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'ot/detailOtfin/' . $myots->id_ot_missing; ?>">
                                        visibility
                                        </a> 
                                        <?php
                                        }else{
                                        ?>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'ot/detailOt/' . $myots->id_ot_missing; ?>">
                                        visibility
                                        </a> 
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </small>
                            <?php
                                }elseif($rol == 5){
                            ?>
                            <small>
                                <div class="row" style="margin-right: auto; margin-left: auto; place-content: center; min-inline-size: max-content;">
                                    <div>
                                        <?php
                                        if($myots->status == 'Enviado a proveedor'){
                                        ?>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'ot/detailOt/' . $myots->id_ot_missing; ?>">
                                        visibility
                                        </a> 
                                        <?php
                                        }elseif($myots->status == 'Finalizada'){
                                        ?>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'ot/detailOtfin/' . $myots->id_ot_missing; ?>">
                                        visibility
                                        </a> 
                                        <?php
                                        }else{
                                        ?>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'ot/detailOtComp/' . $myots->id_ot_missing; ?>">
                                        visibility
                                        </a> 
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </small>
                            <?php
                            }
                            ?>                
                        </td>

                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <br>
</div>

<?php require 'views/templates/footer.php' ?>