<?php

function cifradoCesarNumerico($numero, $desplazamiento) {
    $resultado = "";

    $longitud = strlen($numero);

    for ($i = 0; $i < $longitud; $i++) {
        // Obtener el dígito en la posición actual
        $digito = $numero[$i];

        // Verificar si el carácter es un dígito numérico
        if (ctype_digit($digito)) {
            // Aplicar el desplazamiento y asegurarse de que esté dentro del rango de dígitos (0-9)
            $nuevoDigito = ($digito + $desplazamiento) % 10;

            // Agregar el nuevo dígito al resultado
            $resultado .= $nuevoDigito;
        } else {
            // Si no es un dígito numérico, simplemente agregar el dígito al resultado sin cambios
            $resultado .= $digito;
        }
    }

    return $resultado;
}

// Ejemplo de uso
$numeroOriginal = "123000";
$desplazamiento = 3;

$numeroCifrado = cifradoCesarNumerico($numeroOriginal, $desplazamiento);

echo "Número Original: $numeroOriginal<br>";
echo "Número Cifrado: $numeroCifrado";

?>
