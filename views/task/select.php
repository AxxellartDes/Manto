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

    <div class="card glass">
        <h5 class="card-header">Realizar Tarea</h5>
        <div class="card-body">
            <div class="justify-content-md-center">
            <form id="form" action="<?php echo constant('URL'); ?>task/saveTask" method="POST">
                <input type="hidden" value="<?php echo $comp; ?>" name="company" id="company">
                <style>
                    body {
                    font-family: 'Arial', sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    }

                    .cronometro {
                    text-align: center;
                    }

                    #display {
                    font-size: 2em;
                    margin-bottom: 10px;
                    }

                    button {
                    font-size: 1em;
                    padding: 5px 10px;
                    margin: 5px;
                    cursor: pointer;
                    }
                </style>

                <!-- <script>
                    //CRONOMETRO
                    let cronometro;
                    let isRunning = false;
                    let segundos = 0, minutos = 0, horas = 0;

                    function startStop() {
                    if (isRunning) {
                        clearInterval(cronometro);
                        document.getElementById("startStop").textContent = "Iniciar";
                    } else {
                        cronometro = setInterval(actualizarCronometro, 1000);
                        document.getElementById("startStop").textContent = "Detener";
                    }
                    isRunning = !isRunning;
                    }

                    function reset() {
                    clearInterval(cronometro);
                    isRunning = false;
                    segundos = 0;
                    minutos = 0;
                    horas = 0;
                    actualizarDisplay();
                    document.getElementById("startStop").textContent = "Iniciar";
                    }

                    function actualizarCronometro() {
                    segundos++;
                    if (segundos === 60) {
                        segundos = 0;
                        minutos++;
                        if (minutos === 60) {
                        minutos = 0;
                        horas++;
                        }
                    }
                    actualizarDisplay();
                    actualizarCampoOculto();
                    }

                    function actualizarDisplay() {
                    const display = document.getElementById("display");
                    display.textContent = formatTime(horas) + ":" + formatTime(minutos) + ":" + formatTime(segundos);
                    }

                    function actualizarCampoOculto() {
                    //const tiempo = horas * 3600 + minutos * 60 + segundos;
                    const tiempo = formatTime(horas) + ":" + formatTime(minutos) + ":" + formatTime(segundos);
                    document.getElementById("tiempo").value = tiempo;
                    }

                    function formatTime(time) {
                    return time < 10 ? "0" + time : time;
                    }
                </script>       -->
                <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label" for="bus"><b>Seleccione un bus</b></label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="bus" id="bus" required>
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
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-6">
                            <label class="form-label" for="area"><b>Seleccione el tipo de tarea</b></label>
                            <div class="input-group">
                                <select class="form-select" aria-label="Default select example" name="type_task" id="type_task" required>
                                    <option hidden value="" selected>Selecciona una opción</option>
                                    <?php foreach ($this->tasktype as $row) {
                                        $tasktype = new Task();
                                        $tasktype = $row;
                                    ?>
                                        <option value="<?php echo $tasktype->id_type_task ?>">
                                            <?php echo   $tasktype->description ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div> -->
                    </div>
                        
                <div class="table-responsive">
                    <table class="col-md-1 col-sm-1 col-xl-12 mb-3 align-middle" id="table1" >
                        <tbody id="myTableBody"> 
                            <tr>
                                <td>
                                    <div>
                                        <label class="mb-2" id="label_kit1" ><b>Tarea a realizar</b></label>
                                    </div>
                                    <input type="text" class="form-control mb-2" name="busc" id="busc" placeholder="Buscador... Seleccione primero el tipo de tarea">
                                </td>         
                            </tr>
                            <tr>
                                <td><b>Plan de tareas</b></td>
                                <td><b>Descripción</b></td>
                                <td><b>Horas estimadas</b></td>
                            </tr>
                            <script>
                                // $("#prueba").prop('disabled', true);
                                $(document).ready(function() {

                                    $('#type_task').change(function() {

                                        $('#task_plan option').remove();
                                        document.getElementById("task_plan").innerHTML +=
                                        "<option hidden value='0' selected>Plan Tareas</option>";

                                        $('#taskdes option').remove();
                                        document.getElementById("taskdes").innerHTML +=
                                        "<option hidden value='0' selected>Descripción</option>";

                                        $('#tasktime option').remove();
                                        document.getElementById("tasktime").innerHTML +=
                                        "<option hidden value='0' selected>Horas Estimadas</option>";

                                        document.getElementById("busc").value = "";
                                    });

                                    $('#busc').change(function() {
                                        // $('#event').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');
                                        // listEvent();

                                        $('#task_plan option').remove();
                                        document.getElementById("task_plan").innerHTML +=
                                        "<option hidden value='0' selected>Plan Tareas</option>";

                                        $('#taskdes option').remove();
                                        document.getElementById("taskdes").innerHTML +=
                                        "<option hidden value='0' selected>Descripción</option>";

                                        $('#tasktime option').remove();
                                        document.getElementById("tasktime").innerHTML +=
                                        "<option hidden value='0' selected>Horas Estimadas</option>";

                                        // document.getElementById("list_kit").innerHTML = "";
                                        // alert($('#type_task').val());
                                        var busc_data = $('#busc').val();
                                        var type_task_data = $('#type_task').val();
                                        $.ajax({
                                            url: "<?php echo constant('URL'); ?>task/search_task/" + busc_data + "," + type_task_data,
                                            dataType: 'json',
                                            contentType: "application/json; charset=utf-8",
                                            method: "POST",
                                            success: function(dataitems) {
                                                console.log(dataitems);
                                                var id_task = [];
                                                var task_plan = [];
                                                var description = [];
                                                var hours_estimated = [];
                                                // document.getElementById("itemsdes0").innerHTML +=
                                                //     "<option hidden value='0' selected>Selecciona una opcion</option>";
                                                for (var i in dataitems) {
                                                    id_task.push(dataitems[i].id_task);
                                                    task_plan.push(dataitems[i].task_plan);
                                                    description.push(dataitems[i].description);
                                                    hours_estimated.push(dataitems[i].hours_estimated);

                                                    // document.getElementById("event").empty();
                                                    document.getElementById("task_plan").innerHTML +=
                                                        "<option value='" + dataitems[i].id_task + "'>" +
                                                        dataitems[i].task_plan + "</option>";
                                                    document.getElementById("taskdes").innerHTML +=
                                                        "<option value='" + dataitems[i].id_task + "'>" +
                                                        dataitems[i].description + "</option>";
                                                    document.getElementById("tasktime").innerHTML +=
                                                        "<option value='" + dataitems[i].id_task + "'>" +
                                                        dataitems[i].hours_estimated + "</option>";
                                                }

                                            }
                                            
                                        });
                                    });

                                    $('#task_plan').change(function() {
                                        var id_selectcod = document.getElementById('task_plan').value;
                                        $("#taskdes").val(id_selectcod);
                                        $("#tasktime").val(id_selectcod);
                                        $("#task_plan").val(id_selectcod);
                                    });

                                    $('#taskdes').change(function() {
                                        var id_selectdes = document.getElementById('taskdes').value;
                                        $("#taskdes").val(id_selectdes);
                                        $("#tasktime").val(id_selectdes);
                                        $("#task_plan").val(id_selectdes);
                                    });
                                })
                            </script>
                            <tr class="align-middle" id="compromisosinputs" >
                                <td>
                                    <input type="hidden" name="itemsid" id="itemsid">
                                    <select class="form-select" aria-label="Default select example" name="task_plan" id="task_plan">
                                        <option hidden value="" selected>Código</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select" aria-label="Default select example" name="taskdes" id="taskdes">
                                        <option hidden value="" selected>Descripción</option>
                                    </select> 
                                </td>
                                <td>
                                    <select class="form-control" aria-label="Default select example" name="tasktime" id="tasktime">
                                        <option hidden value="" selected>Horas Estimadas</option>
                                    </select> 
                                </td>
                            </tr>
                            <!-- <tr>
                                <td style="text-align: center;"><b>Temporizador</b></td>
                                <td style="text-align: center;">
                                    <input type="hidden" id="tiempo" name="tiempo" value="">
                                    <div class="row">
                                        <div class="col mt-2">
                                            <elemento class="btn btn-primary" id="startStop" onclick="startStop()">Iniciar</elemento>
                                        </div>
                                        <div class="col">
                                            <div id="display">00:00:00</div>
                                        </div>
                                    </div>                                
                                </td>
                            </tr> -->
                        </tbody>                  
                    </table>
                </div>
                <div class="row">
                    <div class="col-9">
                        <button class="btn btn-success" type="submit">Empezar</button>
                    </div>
                </div>
            </form>
            </div>
            <br>
        </div>
    </div>
          
    <!-- ------------------------------------------------------------------------------------------ -->
    <br>

</div>



<?php require 'views/templates/footer.php' ?>