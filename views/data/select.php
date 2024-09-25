<?php require 'views/templates/header.php' ?>

<br>
<br>

<div class="container">
    <?php

    foreach ($this->gifts as $row) {
        $gift = new Gifts();
        $gift = $row;
    ?>
        <div class="modal fade" id="Modal<?php echo $gift->idgift ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form name="formsavegift" action="<?php echo constant('URL'); ?>gift/save" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><?php echo $gift->name ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="iduser" value="<?php echo $this->id ?>">
                            <input type="hidden" name="idgift" value="<?php echo $gift->idgift ?>">
                            <img src="<?php echo constant('URL').$gift->img1; ?>" class="card-img-top" alt="...">    
                            <div style="text-align: justify;"><?php echo $gift->description ?>
                            <br>
                            <b>*Apreciado colaborador, recuerda que luego de seleccionar la opcion de tu gusto, esta no podra ser modificada</b>
                            </div>
                        </div>
                        <div class="modal-footer">
                                <div class="position-absolute bottom-0 start-0">
                                        <?php
                                            echo "cantidad: ".$gift->quantity;
                                        ?>
                                </div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button id="myBtn" type="submit" class="btn btn-primary" onClick="myFunction<?php echo $gift->idgift ?>()" target="_blank">Aceptar</button>
                            <script>
                                document.getElementById("myBtn").addEventListener("click", myFunction);

                            </script>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php
    }
    ?>

    <div class="card glass">
        <div class="card-header">
            <legend>Seleccion de regalos</legend>
        </div>

        <div class="card-body">
            <div class="alert alert-info mb-2 mt-1" role="alert">
                <strong>Selecciona solo 1 de las siguientes opciones de regalo por cada uno de tus hijos</strong>
            </div>
            <div class="mb-3">

                <div class="row row-cols-1 row-cols-md-3 g-4">

                    <?php

                    foreach ($this->gifts as $row) {
                        $gift = new Gifts();
                        $gift = $row;
                        // print_r($gift);
                    ?>
                        <div class="col">
                            <div class="card">
                                <div class="card-header" style="font-size: 14px;  text-align: center;">
                                    <strong>
                                        <?php
                                        echo $gift->name;
                                        ?>
                                    </strong>
                                </div>
                                <?php
                                            if($gift->quantity != "0"){
                                        ?>
                                <button type="button" style="border-color: transparent;" data-bs-toggle="modal" data-bs-target="#Modal<?php echo $gift->idgift ?>">
                                <img src="<?php echo constant('URL').$gift->img1; ?>" class="card-img-top" alt="..." >
                                </button>
                                <?php
                                         }else{
                                        ?>
                                <img src="<?php echo constant('URL').$gift->img1; ?>" class="card-img-top" alt="..." >        
                                <?php
                                         }
                                        ?>
                                

                                <div class="card-body">
                                    <div class="position-relative">
                                        <?php
                                            if($gift->quantity > "0"){
                                        ?>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal<?php echo $gift->idgift ?>">
                                            Seleccionar
                                        </button>
                                        <div class="ml-5 position-absolute bottom-0 end-0">
                                        <?php
                                            echo "cantidad: ".$gift->quantity;
                                        ?>
                                        </div>
                                        <?php
                                         }else{
                                        ?>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal<?php echo $gift->idgift ?>" disabled>
                                            Seleccionar
                                        </button>
                                        <div class="ml-5 position-absolute bottom-0 end-0 text-danger">
                                        <?php
                                            echo "cantidad: ".$gift->quantity;
                                        ?>
                                        </div>
                                        <?php
                                         }
                                        ?>
                                        

                                    </div>
                                    


                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    // }

                    ?>

                </div>
                <br>
                <hr>
            </div>
        </div>
    </div>

</div>

<?php require 'views/templates/footer.php' ?>