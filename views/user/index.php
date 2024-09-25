<?php require 'views/templates/header.php' ?>

<br>
<br>

<div class="container">

    <?php
    $mensaje = "";
    echo $this->mensaje;
    ?>

    <div class="modal fade" id="newuser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form name="formsavecol" action="<?php echo constant('URL'); ?>user/save" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Colaborador</h5>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <label for="name" class="form-label">Nombre y Apellido</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nombre del colaborador" maxlength="50" aria-label="Nombre del usuario" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                            </div>

                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label for="iduser" class="form-label">Cedula</label>
                                <input type="number" name="iduser" id="iduser" class="form-control" placeholder="Cedula del usuario" maxlength="10" aria-label="Cedula del usuario" required>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group no_border">
                                    <label for="company" class="form-label">Empresa</label>
                                    <select class="form-select" aria-label="Default select example" name="company" id="company" required>
                                        <option hidden value="" selected>Selecciona una opción</option>
                                        <?php foreach ($this->companies as $row) {
                                            $company = new Companies();
                                            $company = $row;
                                        ?>
                                            <option value="<?php echo $company->idcompany ?>">
                                                <?php echo   $company->description ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label for="phone" class="form-label">Telefono</label>
                                <input type="number" name="phone" id="phone" class="form-control" placeholder="Telefono del usuario" maxlength="50" aria-label="Telefono del usuario" required>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <label for="email" class="form-label">Correo electronico</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Correo del usuario" maxlength="50" aria-label="Correo del usuario" required>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button id="btnsavecol" type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php foreach ($this->users as $row) {
        $user = new Users();
        $user = $row;
    ?>

        <div class="modal fade" id="newchildren<?php echo $user->iduser ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="<?php echo constant('URL'); ?>user/addChildren" method="POST" name="formsave">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Hijo</h5>
                        </div>
                        <div class="modal-body">

                            <p>Adicionar Hijo a <?php echo $user->name ?></p>
                            <input type="hidden" name="iduser" value="<?php echo $user->iduser ?>">

                            <div class="row">
                                <div class="col-sm-12 col-md-9">
                                    <label for="nameChildren" class="form-label">Nombre y Apellido del menor</label>
                                    <input type="text" name="nameChildren" id="nameChildren" class="form-control" placeholder="Nombre del hijo" maxlength="50" aria-label="Nombre del usuario" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <label for="ageChildren" class="form-label">Edad</label>
                                    <input type="number" name="ageChildren" id="ageChildren" class="form-control" placeholder="edad" min="0" max="13" step="1" aria-label="Nombre del usuario" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                            <button id="btnsave" type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="newpass<?php echo $user->iduser ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="<?php echo constant('URL'); ?>user/addChildren" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Reestablecer
                                contraseña
                            </h5>
                        </div>
                        <div class="modal-body">
                            Realmente desea reestablecer la contraseña del usuario
                            <?php echo $user->name ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" onclick="location.href='<?php echo constant('URL') . 'user/restorepass/' . $user->iduser; ?>'" class="btn btn-danger">Reestablecer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php
    }
    ?>

    <?php foreach ($this->childrens as $row) {
        $children = new Childrens();
        $children = $row;
    ?>

        <div class="modal fade" id="newpass<?php echo $children->idchildren ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Reestablecer
                            seleccion</h5>
                    </div>
                    <div class="modal-body">
                        Realmente desea reestablecer opcion seleccionada para el menor
                        <?php echo $children->name ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" onclick="location.href='<?php echo constant('URL') . 'user/restoreSelection/' . $children->idchildren.'-'. $children->gift_idgift; ?>'" class="btn btn-danger">Reestablecer</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <div class="card glass">
        <div class="card-header">
            <legend>Usuarios</legend>
        </div>
        <div class="card-body">

            <div class="container mb-3">
                <div class="row">
                    <div class="col-12 col-md-6  mt-1">
                        <form class="form" action="<?php echo constant('URL'); ?>user/searchById" method="POST">
                            <div class="input-group mb-3">
                                <input type="text" name="iduser" id="iduser" class="form-control" placeholder="Número de cedula" aria-label="Recipient's username" aria-describedby="button-addon2" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit" id="button-addon2">Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-md-6  mt-1">
                        <div class="d-grid">
                            <button type="button" class="btn btn-secondary btn" data-bs-toggle="modal" data-bs-target="#newuser">Agregar colaborador</button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="mb-3">

                <div class="accordion" id="accordionExample">
                    <?php foreach ($this->companies as $row) {
                        $company = new Companies();
                        $company = $row;
                    ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?php echo $company->idcompany ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $company->idcompany ?>" aria-expanded="false" aria-controls="collapse<?php echo $company->idcompany ?>">
                                    <strong> <?php echo $company->description ?> </strong></option>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $company->idcompany ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $company->idcompany ?>" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="accordion" id="accordionExample_1">
                                        <?php foreach ($this->users as $row) {
                                            $user = new Users();
                                            $user = $row;
                                            if ($user->company == $company->description) {


                                        ?>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingUser<?php echo $user->iduser ?>">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUser<?php echo $user->iduser ?>" aria-expanded="false" aria-controls="collapseUser<?php echo $user->iduser ?>">
                                                            <?php echo $user->iduser . " - " . $user->name ?></option>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseUser<?php echo $user->iduser ?>" class="accordion-collapse collapse" aria-labelledby="headingUser<?php echo $user->iduser ?>" data-bs-parent="#accordionExample_1">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-12 col-md-6">
                                                                    <div class="d-grid mt-2">
                                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newchildren<?php echo $user->iduser ?>">Agregar hijo</button>
                                                                    </div>

                                                                </div>
                                                                <div class="col-12 col-md-6">
                                                                    <div class="d-grid mt-2">
                                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#newpass<?php echo $user->iduser ?>">Reestablecer contraseña</button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class=" accordion-body">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">ID</th>
                                                                        <th scope="col">Nombre</th>
                                                                        <th scope="col">Edad</th>
                                                                        <th scope="col">Regalo seleccionado</th>
                                                                        <th scope="col">Accion</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($this->childrens as $row) {
                                                                        $children = new Childrens();
                                                                        $children = $row;
                                                                        if ($children->user_iduser == $user->iduser) {
                                                                    ?>
                                                                            <tr>
                                                                                <th scope="row"><?php echo $children->idchildren; ?></th>
                                                                                <td><?php echo $children->name; ?></td>
                                                                                <td><?php echo $children->age; ?></td>
                                                                                <td><?php echo $children->gift_idgift == "" ? "Pendiente por seleccion" : $children->gift_idgift; ?></td>
                                                                                <td>
                                                                                    <div class="row" style="margin-right: auto; margin-left: auto; place-content: center; min-inline-size: max-content;">
                                                                                        <div class="col">
                                                                                            <a class="material-icons icon" href="" data-bs-toggle="modal" data-bs-target="#newpass<?php echo $children->idchildren ?>">
                                                                                                settings_backup_restore
                                                                                            </a>
                                                                                        </div>
                                                                                        <!-- <div class="col">
                                                                                            <a class="material-icons icon" download id="btn_<?php echo $user->iduser; ?>" href="<?php echo $user->iduser; ?>">
                                                                                                redeem
                                                                                            </a>
                                                                                        </div> -->

                                                                                    </div>
                                                                                </td>


                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
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
                        </div>

                    <?php
                    }
                    ?>
                </div>


            </div>
        </div>
    </div>

</div>

<script>
        $(document).ready(function(){
            $("#btnsave").click(function(event){
                event.preventDefault();
            
            document.formsave.submit()
            $("#btnsave").prop('disabled',true)
            
            return false;
            })
        })

        $(document).ready(function(){
            $("#btnsavecol").click(function(event){
                event.preventDefault();
            
            document.formsavecol.submit()
            $("#btnsavecol").prop('disabled',true)
            
            return false;
            })
        })
</script>

<?php require 'views/templates/footer.php' ?>