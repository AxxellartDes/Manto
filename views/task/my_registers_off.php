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
    <h1>Mis Registros - Registros Finalizados</h1>
    <div class="ml-3 mb-3">
        <?php
                if ($this->mensaje != "") 
                echo $this->mensaje;
        ?>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID Tarea</th>
                    <th scope="col">Bus</th>
                    <th scope="col">Tipo Tarea</th>
                    <th scope="col">Tarea</th>
                    <th scope="col">Fecha Inicial</th>
                    <th scope="col">Fecha Final</th>
                    <th scope="col">Tiempo Usado</th>
                    <!-- <th scope="col">Acción</th> -->
                </tr>
            </thead>
            <tbody>
                    <?php 


                        foreach($this->registers_Off as $row){
                        $registers_Off = new Task();
                        $registers_Off = $row;
                    
                    ?>
                    <tr>
                        <th scope="row"><?php echo $registers_Off->id_task; ?></th>
                        <td><?php echo $registers_Off->id_bus; ?></td>
                        <td><?php echo $registers_Off->id_type_task; ?></td>
                        <td><?php echo $registers_Off->description; ?></td>
                        <td><?php echo $registers_Off->date_initial; ?></td>
                        <td><?php echo $registers_Off->date_final; ?></td>
                        <td><?php echo $registers_Off->time; ?></td>
                        
                        <!-- <td class="iconos">

                            <small>
                                <div class="row" style="margin-right: auto; margin-left: auto; place-content: center; min-inline-size: max-content;">
                                    <div>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'task/endTask/' . $registers_Off->id_register; ?>">
                                            visibility
                                        </a>
                                    </div>
                                </div>
                            </small>

                        </td> -->

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