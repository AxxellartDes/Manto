<?php
require 'views/templates/header.php';

$mensaje = '';
$mensaje_1 = '';
$mensaje_2 = '';
?>

<div class="container glass">
    <br>
    <div class="row ml-5">
    </div>
    <h1>Registros de Alistamiento Nocturno</h1>
    <div class="ml-3 mb-3">
        <?php
                if ($this->mensaje != "") 
                echo $this->mensaje;
        ?>
    </div>
    <ul class="mb-4">
        <li>Si no se ingresa <b>Bus</b> se tomara el listado de los registros de todos los buses.</li>
    </ul>
    <div class="col-12 col-lg-12">
        <form class="form" action="" method="POST">
            <div class="row">
                <div class="col-1">
                    <label for=""><b>Bus:</b></label>
                    <input class="form-control" id="seachbus" name="bus" type="text">
                </div>
                <div class="col-2">
                    <label for="number_bus"><strong>Fecha/Inicial</strong></label>
                    <input type="date" id="seachByDate" name="initial_date" class="form-control" aria-label="Recipient's username" aria-describedby="button-addon2" required>
                </div>
                <div class="col-2">
                    <label for="number_bus"><strong>Hora/Inicial</strong></label>
                    <input type="time" id="initial_hour" name="initial_hour" class="form-control" aria-describedby="button-addon2"required>
                </div>
                <div class="col">
                    <label for="number_bus"><strong>Fecha/Final</strong></label>
                    <input type="date" id="seachByDate2" name="final_date" class="form-control" aria-label="Recipient's username" aria-describedby="button-addon2" required>
                </div>
                <div class="col">
                    <label for="number_bus"><strong>Hora/Final</strong></label>
                    <input type="time" id="seachByDate2" name="final_hour" class="form-control" aria-label="Recipient's username" aria-describedby="button-addon2" required>
                </div>
                <div class="col col-md-2 align-self-end">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" type="submit" onclick=this.form.action="<?php echo constant('URL'); ?>enlistment/listRegisterDate" id="button-addon2">Buscar</button>
                        <button class="btn btn-success btn-sm" type="submit" onclick=this.form.action="<?php echo constant('URL'); ?>enlistment/listRegisterDateExp" id="button-addon2">Exportar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-1" scope="col">ID Registro</th>
                    <th class="col-1" scope="col">Id User</th>
                    <th class="col-3" scope="col">User</th>
                    <th class="col-1" scope="col">Bus</th>
                    <th class="col-1" scope="col">Fecha</th>
                    <th class="col-1" scope="col">Acción</th>
                </tr>
            </thead>
            <tbody>
                    <?php 

                        foreach($this->registers as $row){
                        $registers = new Enlistment();
                        $registers = $row;
                    
                    ?>
                    <tr>
                        <th scope="row"><?php echo $registers->id_enlistment; ?></th>
                        <td><?php echo $registers->id_user; ?></td>
                        <td><?php echo $registers->user; ?></td>
                        <td><?php echo $registers->bus; ?></td>
                        <td><?php echo $registers->date; ?></td>
                        
                        <td class="iconos">

                            <small>
                                <div class="row" style="margin-right: auto; margin-left: auto; place-content: center; min-inline-size: max-content;">
                                    <div>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'enlistment/detailRegister/' . $registers->id_enlistment; ?>">
                                            visibility
                                        </a>
                                    </div>
                                </div>
                            </small>

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