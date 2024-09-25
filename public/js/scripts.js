function preloader() {
    // console.log("entro aqui");
    document.getElementById("form").submit();
    // document.getElementById("testdiv").innerHTML = "En 10 segundos se apagará el computador.";
    document.getElementById("loading_oculto").style.visibility = "visible";
    document.getElementById("loading_fondo_oculto").style.visibility = "visible";
    $(window).load(function () {
      // $(".loader").fadeOut("slow");
      document.getElementById("loading_oculto").style.visibility = "hidden";
      document.getElementById("loading_fondo_oculto").style.visibility = "hidden";
      // console.log("entro alla");
    });
  
    // document.getElementById("testdiv").innerHTML = "Computador apagado.";
  }
  