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
    <h1>Pedidos Realizados</h1>
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
                    <th scope="col">ID</th>
                    <th scope="col">Orden de Trabajo</th>
                    <th scope="col">Tecnico</th>
                    <th scope="col">Area </th>
                    <th scope="col">Bus</th>
                    <th scope="col">Kit Repuestos</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acción</th>
                </tr>
            </thead>
            <tbody>
                    <?php 


                        foreach($this->myorders as $row){
                        $myorders = new Orders();
                        $myorders = $row;
                    
                    ?>
                    <tr>
                        <th scope="row"><?php echo $myorders->id_order; ?></th>
                        <td><?php echo $myorders->num_ot; ?></td>
                        <td><?php echo $myorders->id_user_order; ?></td>
                        <td><?php echo $myorders->area; ?></td>
                        <td><?php echo $myorders->idbus; ?></td>
                        <td><?php echo $myorders->kit_title; ?></td>
                        <td><?php echo $myorders->status; ?></td>
                        
                        <td class="iconos">

                            <small>
                                <div class="row" style="margin-right: auto; margin-left: auto; place-content: center; min-inline-size: max-content;">
                                    <div>
                                        <?php
                                        if($myorders->status == "Creada"){
                                        ?>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'almacen/deliveryOrder/' . $myorders->id_order; ?>">
                                        edit
                                        </a> 
                                        <?php
                                        }elseif($myorders->status == "Entregada" || $myorders->status == "Recibido - Terminado"){
                                        ?>
                                        <a class="material-icons icon" href="<?php echo  constant('URL') . 'almacen/deliveryOrderDetail/' . $myorders->id_order; ?>">
                                        visibility
                                        </a> 
                                        <?php
                                        }
                                        ?>  
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