<?php require 'views/templates/header.php' ?>

<div class="container">
    <?php
    if (!isset($_SESSION)) {
        session_start();
    }
    $comp = $_SESSION['company'];
    $mensaje = "";
    echo $this->mensaje;
    //print_r($this->itemsmaintenance);
    //inputselect
    ?>



    <form id="form" action="<?php echo constant('URL'); ?>ot/saveot" method="POST">
        <input type="hidden" value="<?php echo $comp; ?>" name="company" id="company">
        <div class="card glass">
            <h5 class="card-header">Realizar Registro</h5>
            <div class="card-body">
                <div class="justify-content-md-center">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label" for="numOt">Digite el número de orden de trabajo</label><br>
                            <input type="number" class="form-control" name="numOt" id="numOt" min="100000" max="9999999999">
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="bus">Seleccione el movil</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="bus" id="bus" readonly>

                                <!-- <select class="form-select" aria-label="Default select example" name="bus" id="bus" required>
                                    <option hidden value="" selected>Selecciona una opción</option>
                                    <?php foreach ($this->buses as $row) {
                                        $bus = new Buses();
                                        $bus = $row;
                                    ?>
                                        <option value="<?php echo $bus->idbus ?>">
                                            <?php echo   $bus->number_bus ?></option>
                                    <?php
                                    }
                                    ?>
                                </select> -->
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="bus">Descripción OT</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="otdes" id="otdes" readonly>
                            </div>
                        </div>
                    </div>
                    <script>
                        // $("#prueba").prop('disabled', true);
                        $(document).ready(function() {
                            $('#numOt').change(function() {
                                // $('#event').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');
                                // listEvent();
                                $('#bus').val('');
                                $('#otdes').val('');

                                $.ajax({
                                    url: "<?php echo constant('URL'); ?>almacen/getdataot/" + $(
                                        '#numOt').val(),
                                    dataType: 'json',
                                    contentType: "application/json; charset=utf-8",
                                    method: "POST",
                                    success: function(data) {

                                        if(data != ""){
                                            console.log(data);
                                            $('#bus').val(data[0].bus);
                                            $('#otdes').val(data[0].description);
                                            getKitsData();
                                        }else{
                                            alert("No se encuentran registros para el numero de OT "+$(
                                        '#numOt').val()+", por favor ingresa un número de OT valido.");
                                        }
                                    }
                                });
                                
                            });
                        })
                    </script>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label" for="area">Seleccione el proveedor</label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="suppliers" id="suppliers" required>
                                    <option hidden value="" selected>Selecciona una opción</option>
                                    <?php foreach ($this->supplier as $row) {
                                        $supplier = new Supplier();
                                        $supplier = $row;
                                    ?>
                                        <option value="<?php echo $supplier->idusers ?>">
                                            <?php echo   $supplier->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <table class="col-md-1 col-sm-1 col-xl-12 mb-3 align-middle" id="table1" >
                        <tbody id="myTableBody">
                            <tr>
                                <td style="display:none;" id="busc">
                                    Para usar el buscador por favor ingrese el Id o Referencia del item a buscar
                                    <input type="text" class="form-control" name="busc1" id="busc1" placeholder="Buscador...">
                                </td>         
                            </tr>
                            <script>
                                // $("#prueba").prop('disabled', true);
                                $(document).ready(function() {

                                    $('#busc1').change(function() {                                        
                                        $('#type_service1 option').remove();
                                        
                                        var store = $('#busc1').val() + '¿' + $('#suppliers').val();

                                        // document.getElementById("list_kit").innerHTML = "";
                                        $.ajax({
                                            url: "<?php echo constant('URL'); ?>ot/search_service/" + store,
                                            dataType: 'json',
                                            contentType: "application/json; charset=utf-8",
                                            method: "POST",
                                            success: function(dataitems) {
                                                console.log(dataitems);
                                                var id_service = [];
                                                var name_service = [];

                                                for (var i in dataitems) {
                                                    document.getElementById("type_service1").innerHTML +=
                                                        "<option value='" + dataitems[i].id_service + "'>" +
                                                        dataitems[i].name_service + "</option>";
                                                }

                                            }
                                            
                                        });
                                    });
                                })
                            </script>
                            <tr class="align-middle" id="compromisosinputs" >
                                <td class="col-7">
                                    <label class="form-label" for="type_service">Seleccione el tipo de Servicio: </label>
                                    <select class="form-select ml-3" aria-label="Default select example" name="type_service1" id="type_service1">
                                        <option hidden value="0" selected>Selecciona primero un proveedor</option>
                                    </select>
                                </td> 
                                <script>
                                    // $("#prueba").prop('disabled', true);
                                    $(document).ready(function() {
                                        $('#suppliers').change(function() {

                                            $("#busc").css("display", "block");
                                            $("#busc1").val("");

                                            document.getElementById("type_service1").innerHTML +=
                                            "<option hidden value='0' selected>Selecciona una opcion</option>";

                                            document.getElementById("type_service1").innerHTML = "";
                                            $.ajax({
                                                url: "<?php echo constant('URL'); ?>ot/listtypeservice/" + $(
                                                    '#suppliers').val(),
                                                dataType: 'json',
                                                contentType: "application/json; charset=utf-8",
                                                method: "POST",
                                                success: function(dataservice) {
                                                    console.log(dataservice);
                                                    var id_service = [];
                                                    var name_service = [];
                                                    var description = [];
                                                    document.getElementById("type_service1").innerHTML +=
                                                        "<option hidden value='0' selected>Selecciona una opcion</option>";
                                                        $("#buttonagregar").css("display", "inline-block");
                                                    for (var i in dataservice) {
                                                        id_service.push(dataservice[i].id_service);
                                                        name_service.push(dataservice[i].name_service);
                                                        description.push(dataservice[i].description);
                                                        // document.getElementById("event").empty();
                                                        document.getElementById("type_service1").innerHTML +=
                                                            "<option value='" + dataservice[i].id_service + "'>" +
                                                            dataservice[i].name_service + "</option>";
                                                    }
                                                }
                                                
                                            });
                                        });
                                    })
                                </script>
                                <td class="col-1">
                                    <label class="form-label" for="number">Cantidad: </label>
                                    <input class="form-control" type="number" step="0.01" min="0" id="cantidad1" name="cantidad1" value="1">
                                    <input class="form-control" type="hidden" id="totitems" name="totitems" value="1">
                                </td>         
                                <td class="col-1">
                                </td>         
                        </tbody>
                    </table>
                    <!-- aqui -->
                        
                    <div class="row mt-3">
                        <div class="col-9">
                            <button class="btn btn-success" type="submit">Enviar</button>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <button onclick="addInput()" style="display:none;" id="buttonagregar" class="btn btn-primary justify-content-md-end">Agregar Servicio</button>  
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </form>

    <!-- ------------------------------------------------------------------------------------------ -->
    <br>
    <script type="text/javascript">
    $(document).ready(function() {
        $("form").keypress(function(e) {
            if (e.which == 13) {
                e.preventDefault();
            }
        });
    });
    </script>

    <script type="text/javascript">
        var cont = 1;

        function addInput() {
            cont++;
            // var position = "user_responsable"+cont;
            var tableBody = document.getElementById("myTableBody");
            var newRow2 = tableBody.insertRow();
            var cell1b = newRow2.insertCell(0);
            var newRow = tableBody.insertRow();
            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            var cell3 = newRow.insertCell(2);

            cell1b.innerHTML = '<td><input type="text" class="form-control" name="busc'+cont+'" id="busc'+cont+'" placeholder="Buscador..."></td>';
                                    
            cell1.innerHTML = '<td><select class="form-select" aria-label="Default select example" name="type_service'+cont+'" id="type_service'+cont+'" required><option hidden value="" selected>Seleccione una opción</option></td>';
            cell2.innerHTML = '<td><input type="number" min="0" class="form-control" step="0.01" name="cantidad'+cont+'" id="cantidad'+cont+'" value="1"><input class="form-control" type="hidden" id="totitems" name="totitems" value="'+cont+'"></td>';
            cell3.innerHTML = '<td class="col-1" style="text-align:center;"><button onclick="deleteRows(this)" class="material-icons icon">delete</button></td>';
        
            document.getElementById("type_service"+cont).innerHTML = "";
            $.ajax({
                url: "<?php echo constant('URL'); ?>ot/listtypeservice/" + $(
                    '#suppliers').val(),
                dataType: 'json',
                contentType: "application/json; charset=utf-8",
                method: "POST",
                success: function(dataservice) {
                    console.log(dataservice);
                    var id_service = [];
                    var name_service = [];
                    var description = [];
                    document.getElementById("type_service"+cont).innerHTML +=
                        "<option hidden value='0' selected>Selecciona una opcion</option>";
                    for (var i in dataservice) {
                        id_service.push(dataservice[i].id_service);
                        name_service.push(dataservice[i].name_service);
                        description.push(dataservice[i].description);
                        // document.getElementById("event").empty();
                        document.getElementById("type_service"+cont).innerHTML +=
                            "<option value='" + dataservice[i].id_service + "'>" +
                            dataservice[i].name_service + "</option>";
                    }
                }
            });

            $('#busc'+cont).change(function() {

                $('#type_service'+cont+' option').remove();

                var store = $('#busc'+cont).val() + '¿' + $('#suppliers').val();

                // document.getElementById("list_kit").innerHTML = "";
                $.ajax({
                    url: "<?php echo constant('URL'); ?>ot/search_service/" + store,
                    dataType: 'json',
                    contentType: "application/json; charset=utf-8",
                    method: "POST",
                    success: function(dataitems) {
                        console.log(dataitems);
                        var id_service = [];
                        var name_service = [];

                        for (var i in dataitems) {
                            document.getElementById("type_service"+cont).innerHTML +=
                                "<option value='" + dataitems[i].id_service + "'>" +
                                dataitems[i].name_service + "</option>";
                        }

                    }
                    
                });
            });
        }       
        
        function deleteRows(button){
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            $('#busc'+cont).remove();
            cont = cont-1;
        }


    </script> 
        
</div>


<?php require 'views/templates/footer.php' ?>