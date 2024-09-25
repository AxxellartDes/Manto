<?php

require 'views/templates/header.php';
?>

<div class="container" style="background-color: rgba(255,255,255,0.94);">
    <br>

    <?php
    // print_r($this->childrens[0]->status);
    ?>
    <h1>Informacion General</h1>
    <div class="row">
        <div class="col">
            <div class="card glass">
                <div class="card-header">
                    <b>Estado de seleccion</b>
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                        <div class="table-responsive">
                            <table class="table table table-sm table-striped table-condensed table-hover">

                                <thead>
                                    <tr>
                                        <th rowspan="2" scope="row"><small>Descripción</small></th>
                                        <th colspan="4" style="text-align: center;"><small>Colaboradores</small></th>
                                        <!-- <th><small>Hora</small></th> -->
                                    </tr>
                                    <tr>
                                        <?php foreach ($this->companies as $row) {
                                            $company = new Companies();
                                            $company = $row;
                                        ?>
                                            <th style="font-size: 0.65rem;"><?php echo $company->description ?></th>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($this->status as $row) {
                                        $state = new Status();
                                        $state = $row;
                                    ?>
                                        <tr>
                                            <td><small><?php echo $state->description ?></small></td>
                                            <?php foreach ($this->companies as $row) {
                                                $company = new Companies();
                                                $company = $row;
                                                $a = 0;
                                                foreach ($this->childrens as $row) {
                                                    $children = new Childrens();
                                                    $children = $row;
                                                    // print_r($children->age);
                                                    if (($children->status == $state->description) && ($children->company == $company->description)) {
                                                        $a = $a + 1;
                                                    }
                                                }
                                            ?>
                                                <td><small><?php echo $a ?></small></td>
                                            <?php
                                            }
                                            ?>
                                            <!-- <td><small>Sin seleccionar</small></td> -->
                                        </tr>
                                    <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </blockquote>
                </div>
            </div>

            <br>

            <div class="card glass">
                <div class="card-header">
                    <b>Seleccion de regalos agrupados por tipo</b>
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                        <canvas id="myChart5" width="400" height="300"></canvas>
                        <script>
                            $(document).ready(function() {
                                var total = parseInt('<?php echo $this->childrens_quantity; ?>');
                                // console.log(total);
                                $.ajax({
                                    url: "<?php echo constant('URL'); ?>dashboard/statusGift",
                                    dataType: 'json',
                                    contentType: "application/json; charset=utf-8",
                                    method: "POST",
                                    success: function(data) {

                                        var description = [];
                                        var users = [];
                                        var totalusers = [];
                                        // var totalusers = [];
                                        var color = [
                                            'rgba(255, 99, 132, 0.2)',
                                            'rgba(54, 162, 235, 0.2)',
                                            'rgba(255, 206, 86, 0.2)',
                                            'rgba(75, 192, 192, 0.2)',
                                            'rgba(153, 102, 255, 0.2)',
                                            'rgba(255, 159, 64, 0.2)',
                                            'rgba(0, 139, 139, 0.2)',
                                            'rgba(47, 79, 79, 0.2)',
                                            'rgba(0, 255, 255, 0.2)',
                                            'rgba(34, 139, 34, 0.2)',
                                            'rgba(178, 34, 34, 0.2)',
                                            'rgba(138, 43, 226, 0.2)',
                                            'rgba(222, 184, 135, 0.2)',
                                            'rgba(100, 149, 237, 0.2)',
                                            'rgba(169, 169, 169, 0.2)',
                                            'rgba(139, 0, 139, 0.2)',
                                        ];
                                        var bordercolor = [
                                            'rgba(255,99,132,1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)',
                                            'rgba(153, 102, 255, 1)',
                                            'rgba(255, 159, 64, 1)',
                                            'rgba(0, 139, 139, 0.1)',
                                            'rgba(47, 79, 79, 0.1)',
                                            'rgba(0, 255, 255, 0.1)',
                                            'rgba(34, 139, 34, 0.1)',
                                            'rgba(178, 34, 34, 0.1)',
                                            'rgba(138, 43, 226, 0.1)',
                                            'rgba(222, 184, 135, 0.1)',
                                            'rgba(100, 149, 237, 0.1)',
                                            'rgba(169, 169, 169, 0.1)',
                                            'rgba(139, 0, 139, 0.1)',
                                        ];
                                        // description.push('USUARIOS REGISTRADOS');
                                        // users.push(total);

                                        for (var i in data) {
                                            description.push(data[i].description);
                                            users.push(data[i].users);
                                            totalusers.push(total);
                                        }


                                        var chartdata = {
                                            labels: description,
                                            datasets: [{
                                                    label: "Estado de seleccion",
                                                    backgroundColor: color,
                                                    borderColor: color,
                                                    borderWidth: 2,
                                                    hoverBackgroundColor: color,
                                                    hoverBorderColor: bordercolor,
                                                    data: users
                                                },
                                                {
                                                    label: "Total hijos registrados",
                                                    backgroundColor: 'rgba(232, 248, 245 , 0.5)',
                                                    borderColor: 'rgba(163, 228, 215 , 1)',
                                                    borderWidth: 2,
                                                    hoverBackgroundColor: color,
                                                    hoverBorderColor: bordercolor,
                                                    data: totalusers,
                                                    type: 'line'
                                                }
                                            ],

                                        };
                                        var mostrar = document.getElementById('myChart5');

                                        var grafico = new Chart(mostrar, {
                                            type: 'bar',
                                            data: chartdata,
                                            options: {
                                                scales: {
                                                    yAxes: [

                                                        {
                                                            ticks: {
                                                                beginAtZero: true
                                                            },

                                                        }
                                                    ]
                                                },
                                                responsive: true,
                                            }
                                        });
                                    },
                                    error: function(data) {
                                        console.log(data);
                                    }
                                });
                            });
                        </script>
                    </blockquote>
                </div>
            </div>
            <br>

            <div class="card glass">
                <div class="card-header">
                    <b>Seleccion de regalos agrupados por tipo</b>
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                        <canvas id="myChart1" width="400" height="300"></canvas>
                        <script>
                            $(document).ready(function() {
                                // console.log(total);
                                $.ajax({
                                    url: "<?php echo constant('URL'); ?>dashboard/statusGiftTrans",
                                    dataType: 'json',
                                    contentType: "application/json; charset=utf-8",
                                    method: "POST",
                                    success: function(data1) {
                                        $.ajax({
                                            url: "<?php echo constant('URL'); ?>dashboard/statusGiftSuba",
                                            dataType: 'json',
                                            contentType: "application/json; charset=utf-8",
                                            method: "POST",
                                            success: function(data2) {
                                                console.log(data1);
                                                console.log(data2);
                                                var description = [];
                                                var usersT = [];
                                                var usersS = [];
                                                // var totalusers = [];
                                                var color = [
                                                    'rgba(255, 99, 132, 0.2)',
                                                    'rgba(54, 162, 235, 0.2)',
                                                    'rgba(255, 206, 86, 0.2)',
                                                    'rgba(75, 192, 192, 0.2)',
                                                    'rgba(153, 102, 255, 0.2)',
                                                    'rgba(255, 159, 64, 0.2)',
                                                    'rgba(0, 139, 139, 0.2)',
                                                    'rgba(47, 79, 79, 0.2)',
                                                    'rgba(0, 255, 255, 0.2)',
                                                    'rgba(34, 139, 34, 0.2)',
                                                    'rgba(178, 34, 34, 0.2)',
                                                    'rgba(138, 43, 226, 0.2)',
                                                    'rgba(222, 184, 135, 0.2)',
                                                    'rgba(100, 149, 237, 0.2)',
                                                    'rgba(169, 169, 169, 0.2)',
                                                    'rgba(139, 0, 139, 0.2)',
                                                ];
                                                var bordercolor = [
                                                    'rgba(255,99,132,1)',
                                                    'rgba(54, 162, 235, 1)',
                                                    'rgba(255, 206, 86, 1)',
                                                    'rgba(75, 192, 192, 1)',
                                                    'rgba(153, 102, 255, 1)',
                                                    'rgba(255, 159, 64, 1)',
                                                    'rgba(0, 139, 139, 0.1)',
                                                    'rgba(47, 79, 79, 0.1)',
                                                    'rgba(0, 255, 255, 0.1)',
                                                    'rgba(34, 139, 34, 0.1)',
                                                    'rgba(178, 34, 34, 0.1)',
                                                    'rgba(138, 43, 226, 0.1)',
                                                    'rgba(222, 184, 135, 0.1)',
                                                    'rgba(100, 149, 237, 0.1)',
                                                    'rgba(169, 169, 169, 0.1)',
                                                    'rgba(139, 0, 139, 0.1)',
                                                ];
                                                // description.push('USUARIOS REGISTRADOS');
                                                // users.push(total);

                                                for (var i in data1) {
                                                    usersT.push(data1[i].users);
                                                }
                                                for (var i in data2) {
                                                    description.push(data2[i].description);
                                                    usersS.push(data2[i].users);
                                                }


                                                var chartdata = {
                                                    labels: description,
                                                    datasets: [{
                                                            label: "Transversal",
                                                            backgroundColor: color[1],
                                                            borderColor: color[1],
                                                            borderWidth: 2,
                                                            hoverBackgroundColor: color[1],
                                                            hoverBorderColor: bordercolor[1],
                                                            data: usersT
                                                        },
                                                        {
                                                            label: "Suba",
                                                            backgroundColor: color[2],
                                                            borderColor: color[2],
                                                            borderWidth: 2,
                                                            hoverBackgroundColor: color[2],
                                                            hoverBorderColor: bordercolor[2],
                                                            data: usersS
                                                        }
                                                       
                                                    ],

                                                };
                                                var mostrar = document.getElementById('myChart1');

                                                var grafico = new Chart(mostrar, {
                                                    type: 'bar',
                                                    data: chartdata,
                                                    options: {
                                                        scales: {
                                                            yAxes: [
                                                                {
                                                                    ticks: {
                                                                        beginAtZero: true
                                                                    },

                                                                }
                                                            ]
                                                        },
                                                        responsive: true,
                                                    }
                                                });
                                            },
                                            error: function(data2) {
                                                console.log(data2);
                                            }
                                        });
                                    },
                                    error: function(data1) {
                                        console.log(data1);
                                    }
                                });
                            });
                        </script>
                    </blockquote>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card glass">
                <div class="card-header">
                    <b>Seleccion de regalos agrupados por tipo</b>
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                        <canvas id="myChart2" width="400" height="300"></canvas>
                        <script>
                            $(document).ready(function() {
                                // console.log(total);
                                $.ajax({
                                    url: "<?php echo constant('URL'); ?>dashboard/stateGraph",
                                    dataType: 'json',
                                    contentType: "application/json; charset=utf-8",
                                    method: "POST",
                                    success: function(data) {
                                        console.log(data);
                                        var name = [];
                                        var childrens = [];
                                        // var description = [];
                                        // var totalusers = [];
                                        var color = [
                                            'rgba(255, 99, 132, 0.2)',
                                            'rgba(54, 162, 235, 0.2)',
                                            'rgba(255, 206, 86, 0.2)',
                                            'rgba(75, 192, 192, 0.2)',
                                            'rgba(153, 102, 255, 0.2)',
                                            'rgba(255, 159, 64, 0.2)',
                                            'rgba(0, 139, 139, 0.2)',
                                            'rgba(47, 79, 79, 0.2)',
                                            'rgba(0, 255, 255, 0.2)',
                                            'rgba(34, 139, 34, 0.2)',
                                            'rgba(178, 34, 34, 0.2)',
                                            'rgba(138, 43, 226, 0.2)',
                                            'rgba(222, 184, 135, 0.2)',
                                            'rgba(100, 149, 237, 0.2)',
                                            'rgba(169, 169, 169, 0.2)',
                                            'rgba(139, 0, 139, 0.2)'
                                        ];
                                        var bordercolor = [
                                            'rgba(255, 99, 132, 0.1)',
                                            'rgba(54, 162, 235, 0.1)',
                                            'rgba(255, 206, 86, 0.1)',
                                            'rgba(75, 192, 192, 0.1)',
                                            'rgba(153, 102, 255, 0.1)',
                                            'rgba(255, 159, 64, 0.1)',
                                            'rgba(0, 139, 139, 0.1)',
                                            'rgba(47, 79, 79, 0.1)',
                                            'rgba(0, 255, 255, 0.1)',
                                            'rgba(34, 139, 34, 0.1)',
                                            'rgba(178, 34, 34, 0.1)',
                                            'rgba(138, 43, 226, 0.1)',
                                            'rgba(222, 184, 135, 0.1)',
                                            'rgba(100, 149, 237, 0.1)',
                                            'rgba(169, 169, 169, 0.1)',
                                            'rgba(139, 0, 139, 0.1)'
                                        ];
                                        // description.push('USUARIOS REGISTRADOS');
                                        // users.push(total);childrens

                                        for (var i in data) {
                                            name.push(data[i].name);
                                            childrens.push(data[i].childrens);

                                        }

                                        var chartdata = {
                                            labels: name,
                                            datasets: [{
                                                // label: "Hora de carga",
                                                backgroundColor: color,
                                                borderColor: color,
                                                borderWidth: 2,
                                                hoverBackgroundColor: color,
                                                hoverBorderColor: bordercolor,
                                                data: childrens
                                            }],

                                        };
                                        var mostrar = document.getElementById('myChart2');

                                        var grafico = new Chart(mostrar, {
                                            type: 'doughnut',
                                            data: chartdata,
                                            options: {
                                                scales: {
                                                    // yAxes: [

                                                    //     {
                                                    //         ticks: {
                                                    //             beginAtZero: true
                                                    //         },

                                                    //     }
                                                    // ]
                                                },
                                                responsive: true,
                                            }
                                        });
                                    },
                                    error: function(data) {
                                        console.log(data);
                                    }
                                });
                            });
                        </script>
                    </blockquote>
                </div>
            </div>

        </div>
    </div>
    <br>
</div>

<?php require 'views/templates/footer.php' ?>