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



    <form id="form" action="<?php echo constant('URL'); ?>almacen/saveorder" method="POST">
        <input type="hidden" value="<?php echo $comp; ?>" name="company" id="company">
        <div class="card glass">
            <h5 class="card-header">Realizar Pedido</h5>
            <div class="card-body">
                <div class="justify-content-md-center">
                    <div class="row mb-3 table-responsive">
                        <div class="col-lg-auto">
                            <label class="form-label" id="numOtdes" for="numOt">Digite el número de orden de trabajo</label><br>
                            <input type="number" class="form-control" name="numOt" id="numOt" min="100000" max="9999999999" required>
                        </div>
                        <?php
                            if($comp == 2){
                        ?>
                        <div class="col-md-auto">
                            <label class="form-label" for="numOt">Selecciones la ubicación del almacen</label><br>
                            <select class="form-select" aria-label="Default select example" name="store" id="store" required>
                                <option value="2">Almacen Bachue</option>    
                                <option value="4" selected>Almacen Patio 183</option>    
                            </select>     
                        </div>
                        <?php
                            }
                        ?>
                        <div class="col-md-auto">
                            <label class="form-label" for="bus">Bus</label>
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
                        <div class="col-md-auto">
                            <label class="form-label" for="area">Área de mantenimiento</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="area" id="area" readonly>
                                <!-- <select class="form-select" aria-label="Default select example" name="area" id="area" required>
                                    <option hidden value="" selected>Selecciona una opción</option>
                                    <?php foreach ($this->areamaintenance as $row) {
                                        $areamaintenance = new AreaMaintenance();
                                        $areamaintenance = $row;
                                    ?>
                                        <option value="<?php echo $areamaintenance->id_area ?>">
                                            <?php echo   $areamaintenance->description ?></option>
                                    <?php
                                    }
                                    ?>
                                </select> -->
                            </div>
                        </div>
                        <div class="col-md-auto">
                            <label class="form-label" for="area">Tipo de evento</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="type" id="type" readonly>
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

                        <script>
                                // $("#prueba").prop('disabled', true);
                                $(document).ready(function() {
                                    $('#numOt').change(function() {
                                        // $('#event').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');
                                        // listEvent();
                                        $('#bus').val('');
                                        $('#area').val('');
                                        $('#otdes').val('');
                                        $('#type').val('');

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
                                                    $('#area').val(data[0].type_req);
                                                    $('#otdes').val(data[0].description);
                                                    $('#type').val(data[0].event_req);
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
                        <!-- <div class="col-4">
                            <label class="form-label" for="bus">Seleccione el tipo de mantenimiento</label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="bus" id="bus" required>
                                    <option hidden value="" selected>Selecciona una opción</option>
                                    <?php foreach ($this->maintenance as $row) {
                                        $maintenance = new Maintenance();
                                        $maintenance = $row; 
                                    ?>
                                        <option value="<?php echo $maintenance->id_maintenance ?>">
                                            <?php echo   $maintenance->description ?></option>
                                    <?php 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div> -->
                         <div class="col-6">

                            <div class="mb-3 ">
                                    <label class="form-label" for="kit_title">Seleccione el Kit: </label>
                                        <select class="form-select ml-3" aria-label="Default select example" name="kit_title" id="kit_title" required>
                                            <option hidden value="0" selected>Primero Ingresa un Número de OT</option>
                                        </select>
                            </div>
                            <script>
                                // $("#prueba").prop('disabled', true);
                                function getKitsData() {
                                        // $('#event').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');
                                        // listEvent();
                                        document.getElementById("kit_title").innerHTML = "";
                                        $.ajax({
                                            url: "<?php echo constant('URL'); ?>almacen/listtitlekit/" + $(
                                                '#bus').val(),
                                            dataType: 'json',
                                            contentType: "application/json; charset=utf-8",
                                            method: "POST",
                                            success: function(datakit) {
                                                console.log(datakit);
                                                var id_kit = [];
                                                var description = [];
                                                document.getElementById("kit_title").innerHTML +=
                                                    "<option hidden value='0' selected>Selecciona una opcion</option>";
                                                for (var i in datakit) {
                                                    id_kit.push(datakit[i].id_kit);
                                                    description.push(datakit[i].description);
                                                    // document.getElementById("event").empty();
                                                    document.getElementById("kit_title").innerHTML +=
                                                        "<option value='" + datakit[i].id_kit + "'>" +
                                                        datakit[i].description + "</option>";
                                                }

                                            }
                                        });
                                    };
                            </script>        



                            <!-- <label class="form-label" for="bus">Seleccione el kit de repuesto</label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="kit_title" id="kit_title" required>
                                    <option value="" hidden selected>Selecciona una opción</option>
                                    <option value="0" >SIN KIT DE REPUESTOS</option>
                                    <?php foreach ($this->kitmaintenance as $row) {
                                        $kitmaintenance = new TitleItems();
                                        $kitmaintenance = $row; 
                                    ?>
                                        <option value="<?php echo $kitmaintenance->id_kit ?>">
                                            <?php echo   $kitmaintenance->description ?></option>
                                    <?php 
                                    }
                                    ?>
                                </select>
                            </div> -->
                        </div>
                    </div>
                    <div class="card glass" id="section_kit" style="display:none;">
                        <div class="card-header mb-3">
                            <b>Kit de Repuestos</b> 
                        </div>
                        <table class="col-md-1 col-sm-1 col-xl-12 align-middle" id="table2" style="margin-left: 3%;">
                            <thead>
                                <th class="col-1" scope="col">Codigo</th>
                                <th scope="col">Descripción</th>
                                <th class="col-1" scope="col">Cantidad</th>
                            </thead>
                            <tbody id="myTableBodyKit"> 
                            </tbody>
                        </table>
                        <div id="list_kit" class="mb-3">
                        </div>
                        <div id="section_kit_ped" style="display:none;">
                            <div class="card-header mb-3">
                                <b>Lista de pedido</b> 
                            </div>
                            <table class="col-md-1 col-sm-1 col-xl-12 align-middle ml-3" id="table3" style="margin-left: 3%; margin-bottom: 3%;">
                                <thead>
                                    <th class="col-1" scope="col">Codigo</th>
                                    <th scope="col">Descripción</th>
                                    <th class="col-1" scope="col">Cantidad</th>
                                </thead>
                                <tbody id="myTableBodyKitDelete"> 
                                </tbody>
                            </table>
                            <div id="list_kit_pedido">
                            </div>
                        </div>
                        
                    </div>
                    
                    <div>
                        <label id="label_kit1" ><b>Repuestos a utilizar</b></label>
                        <label id="label_kit2" class="mt-3" style="display:none;"><b>Agregar repuestos a utilizar</b></label>
                    </div>

                    <script>
                        // $("#prueba").prop('disabled', true);
                        $(document).ready(function() {
                            $('#kit_title').change(function() {
                                // $('#event').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');
                                // listEvent();
                                if ($(this).val() > "0") {
                                    $("#section_kit").css("display", "block");
                                    $("#label_kit1").css("display", "none");
                                    $("#label_kit2").css("display", "block");
                                } else {
                                    $("#section_kit").css("display", "none");
                                    $("#label_kit1").css("display", "block");
                                    $("#label_kit2").css("display", "none");
                                }

                                const node1 = document.getElementById("myTableBodyKit");
                                while (node1.firstChild) {
                                    node1.removeChild(node1.firstChild);
                                }

                                const node2 = document.getElementById("myTableBodyKitDelete");
                                while (node2.firstChild) {
                                    node2.removeChild(node2.firstChild);
                                }
                                
                                document.getElementById("list_kit").innerHTML = "";
                                $.ajax({
                                    url: "<?php echo constant('URL'); ?>almacen/list_kit/" + $(
                                        '#kit_title').val(),
                                    dataType: 'json',
                                    contentType: "application/json; charset=utf-8",
                                    method: "POST",
                                    success: function(data) {

                                        console.log(data);
                                        var iditem = [];
                                        var id_kit_item = [];
                                        var id_title_kit = [];
                                        var id_items = [];
                                        var description = [];
                                        var cantidad = [];
                                        for (var i in data) {
                                            id_kit_item.push(data[i].id_kit_item);
                                            id_title_kit.push(data[i].id_title_kit);
                                            id_items.push(data[i].id_items);
                                            description.push(data[i].description);
                                            cantidad.push(data[i].cantidad);
                                            createInputs(data[i].id_kit_item, data[i].id_title_kit, data[i].id_items, data[i].description, data[i].cantidad, i);
                                            createInputsI(i);
                                        }

                                        function createInputsI(i) {
                                            const nuevoInput = document.createElement("input");
                                            nuevoInput.type = "hidden";
                                            nuevoInput.name = "numrep";
                                            nuevoInput.value = i;
                                            list_kit.appendChild(nuevoInput);
                                        }

                                        function createInputs(id_kit_item, id_title_kit, id_items, description, cantidad, i) {
                                            var tableBody = document.getElementById("myTableBodyKit");
                                            var newRow = tableBody.insertRow();
                                            var cell1 = newRow.insertCell(0);
                                            var cell2 = newRow.insertCell(1);
                                            var cell3 = newRow.insertCell(2);
                                            var cell4 = newRow.insertCell(3);

                                            cell1.innerHTML = '<td  class="col-1"><input type="text" class="form-control" name="kit_'+i+'" id="kit_'+i+'" value="'+id_items+'">';
                                            cell2.innerHTML = '<td><input type="text" class="form-control" name="kit_des'+i+'" id="kit_des'+i+'" value="'+description+'">';
                                            cell3.innerHTML = '<td><input type="text" class="form-control" name="kit_cant'+i+'" id="kit_cant'+i+'" value="'+cantidad+'">';
                                            cell4.innerHTML = '<td class="col-1" style="text-align:center;"><button onclick="deleteRows(this)" class="material-icons icon">delete</button></td>';
                                        }        
                                    }
                                    
                                });

                                document.getElementById("list_kit_pedido").innerHTML = "";
                                $.ajax({
                                    url: "<?php echo constant('URL'); ?>almacen/list_kit_pedido/" + $(
                                        '#kit_title').val(),
                                    dataType: 'json',
                                    contentType: "application/json; charset=utf-8",
                                    method: "POST",
                                    success: function(data) {

                                        console.log(data);
                                        var iditem = [];
                                        var id_kit_item = [];
                                        var id_title_kit2 = [];
                                        var id_items = [];
                                        var description = [];
                                        var cantidad = [];

                                        if(data.length != 0) {
                                            $("#section_kit_ped").css("display", "block");
                                        }else {
                                            $("#section_kit_ped").css("display", "none");
                                        }

                                        for (var i in data) {
                                            id_kit_item.push(data[i].id_kit_item);
                                            id_title_kit2.push(data[i].id_title_kit2);
                                            id_items.push(data[i].id_items);
                                            description.push(data[i].description);
                                            cantidad.push(data[i].cantidad);
                                            createInputs2(data[i].id_kit_item, data[i].id_title_kit2, data[i].id_items, data[i].description, data[i].cantidad, i);
                                            createInputsI2(i);
                                        }

                                        function createInputsI2(i) {
                                            const nuevoInput = document.createElement("input");
                                            nuevoInput.type = "hidden";
                                            nuevoInput.name = "numrep2";
                                            nuevoInput.value = i;
                                            list_kit.appendChild(nuevoInput);
                                        }

                                        function createInputs2(id_kit_item, id_title_kit, id_items, description, cantidad, i) {
                                            var tableBody = document.getElementById("myTableBodyKitDelete");
                                            var newRow = tableBody.insertRow();
                                            var cell1 = newRow.insertCell(0);
                                            var cell2 = newRow.insertCell(1);
                                            var cell3 = newRow.insertCell(2);
                                            var cell4 = newRow.insertCell(3);

                                            cell1.innerHTML = '<td><input type="text" class="form-control" name="kit2_'+i+'" id="kit2_'+i+'" value="'+id_items+'">';
                                            cell2.innerHTML = '<td><input type="text" class="form-control" name="kit2des_'+i+'" id="kit2des_'+i+'" value="'+description+'">';
                                            cell3.innerHTML = '<td><input type="text" class="form-control" name="kit2_cant'+i+'" id="kit2_cant'+i+'" value="'+cantidad+'">';
                                            cell4.innerHTML = '<td class="col-1" style="text-align:center;"><button onclick="deleteRows(this)" class="material-icons icon">delete</button></td>';
                                        }  
                                    }
                                });
                            });
                        })
                    </script>
                        
                    <table class="col-md-1 col-sm-1 col-xl-12 mb-3 align-middle" id="table1" >
                        <tbody id="myTableBody"> 
                                <?php  
                                    $id_itemss=[];
                                    $cod_itemss=[];
                                    $descriptions=[];
                                    $amounts=[];
                                    $num = 0;
                                    foreach($this->itemsmaintenance as $row){
                                        $itemsmaintenance = new itemsMaintenance();
                                        $itemsmaintenance = $row;

                                        $id_itemss[$num] = $itemsmaintenance->id_items;
                                        $descriptions[$num] = $itemsmaintenance->description;
                                        $cod_itemss[$num] = $itemsmaintenance->cod_items;
                                        $num++;
                                        }
                                ?>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="busc0" id="busc0" placeholder="Buscador...">
                                </td>         
                            </tr>
                            <script>
                                // $("#prueba").prop('disabled', true);
                                $(document).ready(function() {
                                    $('#busc0').change(function() {
                                        // $('#event').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');
                                        // listEvent();

                                        <?php
                                            if($comp == 1){
                                                $store = 'SUB';
                                            }elseif($comp == 3){
                                                $store = 'C80';
                                            }elseif($comp == 2){
                                        ?>
                                            var storetemp = $('#store').val();

                                            if(storetemp == 1){
                                                <?php
                                                    $store = 'NOT';
                                                ?>
                                            }else{
                                                <?php
                                                    $store = 'NOR';
                                                ?>
                                            }
                                        <?php
                                            }
                                        ?>

                                        var store = $('#busc0').val()+'¿<?php echo $store ?>';

                                        $('#itemsdes0 option').remove();
                                        document.getElementById("itemsdes0").innerHTML +=
                                        "<option hidden value='0' selected>Descripción</option>";

                                        $('#itemscod0 option').remove();
                                        document.getElementById("itemscod0").innerHTML +=
                                        "<option hidden value='0' selected>Código</option>";

                                        // document.getElementById("list_kit").innerHTML = "";
                                        $.ajax({
                                            url: "<?php echo constant('URL'); ?>almacen/search_item/" + store,
                                            dataType: 'json',
                                            contentType: "application/json; charset=utf-8",
                                            method: "POST",
                                            success: function(dataitems) {
                                                // console.log(dataitems);
                                                var id_items = [];
                                                var cod_items = [];
                                                var description = [];
                                                var stock = [];
                                                var store = [];

                                                for (var i in dataitems) {
                                                    document.getElementById("itemsdes0").innerHTML +=
                                                        "<option value='" + dataitems[i].cod_items + "'>" +
                                                        dataitems[i].description + " - " + Math.trunc(dataitems[i].stock) + " - " + dataitems[i].store + "</option>";
                                                    document.getElementById("itemscod0").innerHTML +=
                                                        "<option value='" + dataitems[i].cod_items + "'>" +
                                                        dataitems[i].cod_items + "</option>";
                                                }

                                            }
                                            
                                        });
                                    });

                                    $('#itemscod0').change(function() {
                                            var id_selectcod = document.getElementById('itemscod0').value;
                                            $("#itemsdes0").val(id_selectcod);
                                            $("#itemsid0").val(id_selectcod);
                                        });

                                        $('#itemsdes0').change(function() {
                                            var id_selectdes = document.getElementById('itemsdes0').value;
                                            $("#itemscod0").val(id_selectdes);
                                            $("#itemsid0").val(id_selectdes);
                                    });
                                })
                            </script>
                           <tr class="align-middle" id="compromisosinputs" >
                            <!-- <td>
                                <select class="form-select" aria-label="Default select example" name="items0" id="items0" required>
                                    <option hidden value="" selected>Selecciona una opción</option>
                                    <?php foreach ($this->itemsmaintenance as $row) {
                                        $itemsmaintenance = new itemsMaintenance();
                                        $itemsmaintenance = $row;
                                    ?>
                                        <option value="<?php echo $itemsmaintenance->id_items ?>">
                                            <?php echo   $itemsmaintenance->cod_items." - ".$itemsmaintenance->description." - ".$itemsmaintenance->amount ?></option>
                                    <?php
                                    }
                                    
                                    ?>
                                </select> 
                            </td> -->
                            <td>
                                <input type="hidden" name="itemsid0" id="itemsid0">
                                <select class="form-select" aria-label="Default select example" name="itemscod0" id="itemscod0">
                                    <option hidden value="" selected>Código</option>
                                    <?php 
                                    // foreach ($this->itemsmaintenance as $row) {
                                    //     $itemsmaintenance = new itemsMaintenance();
                                    //     $itemsmaintenance = $row;
                                    ?>
                                        <!-- <option value="<?php echo $itemsmaintenance->id_items ?>">
                                            <?php echo   $itemsmaintenance->cod_items?></option> -->
                                    <?php
                                    // }
                                    
                                    ?>
                                </select>
                                <script>
                                    $(document).ready(function() {
                                        
                                    });
                                </script>
                            </td>
                            <td>
                                <select class="form-select" aria-label="Default select example" name="itemsdes0" id="itemsdes0">
                                    <option hidden value="" selected>Descripción</option>
                                    <?php 
                                    // foreach ($this->itemsmaintenance as $row) {
                                    //     $itemsmaintenance = new itemsMaintenance();
                                    //     $itemsmaintenance = $row;
                                    ?>
                                        <!-- <option value="<?php echo $itemsmaintenance->id_items ?>">
                                            <?php echo  $itemsmaintenance->description ?></option> -->
                                    <?php
                                    //}
                                    
                                    ?>
                                </select> 
                            </td>
                            <td>
                                <input type="number" class="form-control" name="itemscant0" id="itemscant0" min="1" placeholder="Cantidad" value="1">
                            </td>
                            <td class="col-1"></td>
                                                                     
                            </tr> <br>
                        </tbody>                  
                     
                    </table>
                    <div class="row">
                        <div class="col-9">
                            <button class="btn btn-success" type="submit">Enviar</button>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <button onclick="addInput()" class="btn btn-primary justify-content-md-end">Agregar repuesto</button>  
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </form>

    <!-- ------------------------------------------------------------------------------------------ -->
    <br>
        
    <!-- <div>
        src="http://172.18.60.41/manto/public/images/si18.png" 
        <img src="http://172.18.60.41/manto/public/images/si18.png" alt="">
        <img src="//172.18.60.40/Repuestos/1.jpg" alt="">
    </div>   -->

</div>

<script type="text/javascript">
    var cont = 0;
    var id_itemss       = <?php echo json_encode($id_itemss); ?>;
    var descriptions    = <?php echo json_encode($descriptions); ?>;
    var cod_itemss      = <?php echo json_encode($cod_itemss); ?>;

    function addInput() {
        cont++;
        var position = "user_responsable"+cont;
        var tableBody = document.getElementById("myTableBody");
        var newRow2 = tableBody.insertRow();
        var cell1b = newRow2.insertCell(0);
        var newRow = tableBody.insertRow();
        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);
                                
        cell1b.innerHTML = '<td><input type="text" class="form-control" name="busc'+cont+'" id="busc'+cont+'" placeholder="Buscador..."></td>';
        cell1.innerHTML = '<td><select class="form-select" aria-label="Default select example" name="itemscod'+cont+'" id="itemscod'+cont+'" required><option hidden value="" selected>Código</option></td>';
        cell2.innerHTML = '<td><select class="form-select" aria-label="Default select example" name="itemsdes'+cont+'" id="itemsdes'+cont+'" required><option hidden value="" selected>Descripción</option></td>';
        cell3.innerHTML = '<td><input type="number" min="1" class="form-control" name="itemscant'+cont+'" id="itemscant'+cont+'" placeholder="Cantidad" value="1"><input type="hidden" class="form-control" name="itemsid'+cont+'" id="itemsid'+cont+'"><input type="hidden" name="totitems" id="totitems" value="'+cont+'">';
        cell4.innerHTML = '<td class="col-1" style="text-align:center;"><button onclick="deleteRows(this)" class="material-icons icon">delete</button></td>';
        
    for(let ind = 0; ind < id_itemss.length; ind++){
        insertCategory(id_itemss[ind], descriptions[ind], cod_itemss[ind])
    }
        
    function insertCategory(id_itemss,descriptions,cod_itemss){
        const selectElement = document.getElementById("itemscod"+cont);
        let htmlToInsert = '<option value="'+cod_itemss+'">'+cod_itemss+'</option>'
        selectElement.insertAdjacentHTML("beforeend",htmlToInsert)

        const selectElement2 = document.getElementById("itemsdes"+cont);
        let htmlToInsert2 = '<option value="'+id_itemss+'">'+descriptions+'</option>'
        selectElement2.insertAdjacentHTML("beforeend",htmlToInsert2)
    }

    $('#busc'+cont).change(function() {
        // $('#event').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');
        // listEvent();

        var store = $('#busc'+cont).val()+'¿<?php echo $store ?>';

        // alert(store);

        $('#itemsdes'+cont+' option').remove();
        document.getElementById("itemsdes"+cont).innerHTML +=
        "<option hidden value='0' selected>Descripción</option>";

        $('#itemscod'+cont+' option').remove();
        document.getElementById("itemscod"+cont).innerHTML +=
        "<option hidden value='0' selected>Código</option>";

        // document.getElementById("list_kit").innerHTML = "";
        $.ajax({
            url: "<?php echo constant('URL'); ?>almacen/search_item/" + store,
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            method: "POST",
            success: function(dataitems) {
                console.log(dataitems);
                var id_items = [];
                var cod_items = [];
                var description = [];
                var stock = [];
                var store = [];
                // document.getElementById("itemsdes0").innerHTML +=
                //     "<option hidden value='0' selected>Selecciona una opcion</option>";
        
                for (var i in dataitems) {
                    document.getElementById("itemsdes"+cont).innerHTML +=
                        "<option value='" + dataitems[i].cod_items + "'>" +
                        dataitems[i].description + " - " + Math.trunc(dataitems[i].stock) + " - " + dataitems[i].store + "</option>";
                    document.getElementById("itemscod"+cont).innerHTML +=
                        "<option value='" + dataitems[i].cod_items + "'>" +
                        dataitems[i].cod_items + "</option>";
                }

            }
            
        });
    });
        
    $(document).ready(function() {
        $('#itemscod'+cont).change(function() {
            var id_selectcod = document.getElementById('itemscod'+cont).value;
            $("#itemsdes"+cont).val(id_selectcod);
            $("#itemsid"+cont).val(id_selectcod);
        });

        $('#itemsdes'+cont).change(function() {
            var id_selectdes = document.getElementById('itemsdes'+cont).value;
            $("#itemscod"+cont).val(id_selectdes);
            $("#itemsid"+cont).val(id_selectdes);
        });
    });
        
    }      
    
    function deleteRows(button){
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        $('#busc'+cont).remove();
    }


</script> 


<?php require 'views/templates/footer.php' ?>