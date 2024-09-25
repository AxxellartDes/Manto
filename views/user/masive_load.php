<?php require 'views/templates/header.php' ?>

<div class="container">
    <br>
    <?php
    $mensaje = "";
    echo $this->mensaje;
    ?>
    <div class="card">
        <h5 class="card-header">Cargar documento</h5>
        <div class="card-body">
            <form action="<?php echo constant('URL'); ?>user/loadUsers" method="POST" enctype="multipart/form-data">
                <div class="container">
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="fileProg" name="fileProg" required>
                        </div>
                    </div>
                </div>

                <div style="text-align: center;">
                    <input class="btn btn-primary" id="inputGroupFileAddon03" type="submit" value="Cargar documento">
                </div>
            </form>
        </div>
    </div>
</div>

<br>

<script>
    //Add the following code if you want the name of the file appear on select
    $('.custom-file-input').on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>

<?php require 'views/templates/footer.php' ?>