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
    <h1>Mis Registros - Registros En Proceso</h1>
    <div class="ml-3 mb-3">
        <?php
                if ($this->mensaje != "") 
                echo $this->mensaje;
        ?>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID Tarea</th>
                    <th scope="col">Bus</th>
                    <th scope="col">Tipo Tarea</th>
                    <th scope="col">Tarea</th>
                    <th scope="col">Fecha Inicial </th>
                    <th scope="col">Acción</th>
                </tr>
            </thead>
            <tbody>
                    <?php 


                        foreach($this->registers_On as $row){
                        $registers_On = new Task();
                        $registers_On = $row;
                    
                    ?>
                    <tr>
                        <th scope="row"><?php echo $registers_On->id_task; ?></th>
                        <td><?php echo $registers_On->id_bus; ?></td>
                        <td><?php echo $registers_On->id_type_task; ?></td>
                        <td><?php echo $registers_On->description; ?></td>
                        <td><?php echo $registers_On->date_initial; ?></td>
                        
                        <td class="iconos">

                            <small>
                                <div class="row" style="margin-right: auto; margin-left: auto; place-content: center; min-inline-size: max-content;">
                                    <div>
                                        <a title="Finalizar Tarea" class="material-icons icon" href="<?php echo  constant('URL') . 'task/endTask/' . $registers_On->id_register; ?>">
                                         task
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