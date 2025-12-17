<?php
// Para saltar cuando hagamos modificaciones en la base de datos y mostrar los mensajes
session_name("actividad_2");
session_start();

require "src/func_ctes.php";

if (isset($_POST["btnContBorrar"])) {
	$url = API_TIENDA . "/producto/borrar/" . urlencode($_POST["btnContBorrar"]);
	$respuesta = consumir_servicios_REST($url, "DELETE");

	$json_detalles = json_decode($respuesta, true);

	if (!$json_detalles) {
		session_destroy();
		die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
	}

	if (isset($json_detalles["error_bd"])) {
		session_destroy();
		die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>" . $json_detalles["error_bd"] . "</p><p>Error consumiendo el servicio: " . $url . "</p>"));
	}
}

if (isset($_POST["h_cod"])) {
	$url = API_TIENDA . "/producto/" . urlencode($_POST["h_cod"]);
	$respuesta = consumir_servicios_REST($url, "GET");

	$json_detalles = json_decode($respuesta, true);

	if (!$json_detalles) {
		session_destroy();
		die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
	}

	if (isset($json_detalles["error_bd"])) {
		session_destroy();
		die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>" . $json_detalles["error_bd"] . "</p><p>Error consumiendo el servicio: " . $url . "</p>"));
	}
}

if (isset($_POST["btnContInsertar"])) {
	$error_cod = $_POST["cod"] == "";
	// Comprobamos que no esta repetido
	if (!$error_cod) {
		$url = API_TIENDA . "/repetido/producto/cod/" . urlencode($_POST["cod"]);
		$respuesta = consumir_servicios_REST($url, "GET");

		$json_repetido = json_decode($respuesta, true);

		if (!$json_repetido) {
			session_destroy();
			die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
		}

		if (isset($json_repetido["error_bd"])) {
			session_destroy();
			die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>" . $json_repetido["error_bd"] . "</p><p>Error consumiendo el servicio: " . $url . "</p>"));
		}

		$error_cod = $json_repetido["repetido"];
	}
	// $error_nombre no hace falta porque puede ser null
	$error_nombre_corto = $_POST["nombre_corto"] == "";
	if (!$error_nombre_corto) {
		// urlencode para que transforme lo que se ha pasado a algo valido para una url 
		// sino puede dar fallos
		$url = API_TIENDA . "/repetido/producto/nombre_corto/" . urlencode($_POST["nombre_corto"]);
		$respuesta = consumir_servicios_REST($url, "GET");

		$json_repetido = json_decode($respuesta, true);

		if (!$json_repetido) {
			session_destroy();
			die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
		}

		if (isset($json_repetido["error_bd"])) {
			session_destroy();
			die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>" . $json_repetido["error_bd"] . "</p><p>Error consumiendo el servicio: " . $url . "</p>"));
		}

		$error_nombre_corto = $json_repetido["repetido"];
	}

	$error_descripcion = $_POST["descripcion"] == "";
	$error_pvp = $_POST["pvp"] == "" || !is_numeric($_POST["pvp"]) || $_POST["pvp"] < 0;

	$error_form = $error_cod || $error_nombre_corto || $error_descripcion || $error_pvp;

	if (!$error_form) {
		$datos_insertar["cod"] = $_POST["cod"];
		$datos_insertar["nombre"] = $_POST["nombre"];
		$datos_insertar["nombre_corto"] = $_POST["nombre_corto"];
		$datos_insertar["descripcion"] = $_POST["descripcion"];
		$datos_insertar["PVP"] = $_POST["pvp"];
		$datos_insertar["familia"] = $_POST["familia"];

		$url = API_TIENDA . "/producto/insertar";
		$respuesta = consumir_servicios_REST($url, "POST", $datos_insertar);

		$json_insertar = json_decode($respuesta, true);

		if (!$json_insertar) {
			session_destroy();
			die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
		}

		if (isset($json_insertar["error_bd"])) {
			session_destroy();
			die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>" . $json_repetido["error_bd"] . "</p><p>Error consumiendo el servicio: " . $url . "</p>"));
		}

		// Guardamos el mensaje para informar al usuario
		$_SESSION["mensaje"] = "Producto insertado correctamente.";
		header("Location: index.php");
		exit;
	}
}

if (isset($_POST["btnInsertar"]) || isset($_POST["btnContInsertar"])) {
	$url = API_TIENDA . "/familias";
	$respuesta = consumir_servicios_REST($url, "GET");

	$json_familias = json_decode($respuesta, true);

	if (!$json_familias) {
		session_destroy();
		die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
	}

	if (isset($json_familias["error_bd"])) {
		session_destroy();
		die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>" . $json_familias["error_bd"] . "</p><p>Error consumiendo el servicio: " . $url . "</p>"));
	}
}

$url = API_TIENDA . "/productos";
$respuesta = consumir_servicios_REST($url, "GET");

// Convertir el json en array asociativo (el true hace que sea asociativo)
$json_productos = json_decode($respuesta, true);

// Comprobamos si hemos recibido respuesta del servicio
// En caso de que no, es un 404 probablemente y terminamos
if (!$json_productos) {
	session_destroy();
	die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}

// Comprobamos si el json viene con un error
// En otro caso viene con los datos de productos (los que queremos)
if (isset($json_productos["error_bd"])) {
	session_destroy();
	die(error_page("Actividad 2", "<h1>Listado de los productos</h1><p>" . $json_productos["error_bd"] . "</p><p>Error consumiendo el servicio: " . $url . "</p>"));
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Actividad 2</title>
	<style>
		.text-center {
			text-align: center;
		}

		.table-center {
			width: 90%;
			margin: 0 auto;
		}

		table,
		td,
		th {
			border: 1px solid black;
		}

		th {
			background-color: lightgrey;
		}

		table {
			border-collapse: collapse;
		}

		.enlace {
			border: none;
			background: none;
			text-decoration: underline;
			color: blue;
			cursor: pointer;
		}

		.error {
			color: red;
		}

		.info {
			color: blue;
		}
	</style>
</head>

<body>
	<h1 class="text-center">Listado de los productos</h1>
	<?php
	if (isset($_SESSION["mensaje"])) {
		echo "<p class='info text-center'>" . $_SESSION["mensaje"] . "</p>";
		session_unset();
	}

	if (isset($_POST["btnBorrar"])) {
	?>
		<div class="text-center">
			<form action="index.php" method="post">
				<p>Se dispone usted a borrar el producto: <strong><?= $_POST["h_cod"] ?></strong></p>
				<p>¿ Estás seguro ?</p>
				<p>
					<button type="submit">Volver</button>
					<button type="submit" name="btnContBorrar" value="<?= $_POST["h_cod"] ?>">Continuar</button>
				</p>
			</form>
		</div>
		<?php
	}

	if (isset($_POST["btnDetalles"])) {
		if (isset($json_detalles["producto"])) {
		?>
			<div class="table-center">
				<h2>Información del producto: <?= $json_detalles["producto"]["cod"] ?></h2>
				<p><strong>Nombre: </strong><?= $json_detalles["producto"]["nombre"] ?></p>
				<p><strong>Nombre Corto: </strong><?= $json_detalles["producto"]["nombre_corto"] ?></p>
				<p><strong>Descripción: </strong><?= $json_detalles["producto"]["descripcion"] ?></p>
				<p><strong>PVP: </strong><?= $json_detalles["producto"]["PVP"] ?> €</p>
				<p><strong>Familia: </strong><?= $json_detalles["producto"]["nombre_familia"] ?></p>
				<form action="index.php" method="post">
					<p><button type="submit">Volver</button></p>
				</form>
			</div>
		<?php
		} else {
			// Por si acaso de borra desde otro lado
		?>
			<div class="table-center">
				<h2>Información del producto: <?= $json_detalles["producto"]["cod"] ?></h2>
				<p>El producto <?= $_POST["btnDetalles"] ?> ya no se encuentra en la BD.</p>
			</div>
		<?php
		}
	}

	if (isset($_POST["btnEditar"]) || isset($_POST["btnContEditar"]) && $error_form) {
	}

	if (isset($_POST["btnInsertar"]) || (isset($_POST["btnContInsertar"]) && $error_form)) {
		?>
		<form class="table-center" action="index.php" method="post">
			<h2>Creando un nuevo producto</h2>
			<p>
				<label for="cod">Código: </label>
				<input type="text" name="cod" id="cod" value="<?php if (isset($_POST["cod"])) echo $_POST["cod"] ?>">
				<?php
				if (isset($_POST["btnContInsertar"]) && $error_cod) {
					if ($_POST["cod"] == "") {
						echo "<span class='error'>* Campo obligatorio</span>";
					} else {
						echo "<span class='error'>* Código repetido</span>";
					}
				}
				?>
			</p>
			<p>
				<label for="nombre">Nombre: </label>
				<input type="text" name="nombre" id="nombre" value="<?php if (isset($_POST["nombre"])) echo $_POST["nombre"] ?>">
			</p>
			<p>
				<label for="nombre_corto">Nombre corto: </label>
				<input type="text" name="nombre_corto" id="nombre_corto" value="<?php if (isset($_POST["nombre_corto"])) echo $_POST["nombre_corto"] ?>">
				<?php
				if (isset($_POST["btnContInsertar"]) && $error_nombre_corto) {
					if ($_POST["nombre_corto"] == "") {
						echo "<span class='error'>* Campo obligatorio</span>";
					} else {
						echo "<span class='error'>* Nombre Corto repetido</span>";
					}
				}
				?>
			</p>
			<p>
				<label for="descripcion">Descripción: </label>
				<textarea name="descripcion" id="descripcion"><?php if (isset($_POST["descripcion"])) echo $_POST["descripcion"] ?></textarea>
				<?php
				if (isset($_POST["btnContInsertar"]) && $error_descripcion) {
					if ($_POST["descripcion"] == "") {
						echo "<span class='error'>* Campo obligatorio</span>";
					}
				}
				?>
			</p>
			<p>
				<label for="pvp">PVP: </label>
				<input type="text" name="pvp" id="pvp" value="<?php if (isset($_POST["pvp"])) echo $_POST["pvp"] ?>">
				<?php
				if (isset($_POST["btnContInsertar"]) && $error_pvp) {
					if ($_POST["pvp"] == "") {
						echo "<span class='error'>* Campo obligatorio</span>";
					} else {
						echo "<span class='error'>* Valor de PVP inválido</span>";
					}
				}
				?>
			</p>
			<p>
				<label for="familia">Seleccione una familia: </label>
				<select name="familia" id="familia">
					<?php
					foreach ($json_familias["familias"] as $familia) {
						if (isset($_POST["familia"]) && $_POST["familia"] == $familia["cod"]) {
							echo "<option selected value='" . $familia["cod"] . "'>" . $familia["nombre"] . "</option>";
						} else {
							echo "<option value='" . $familia["cod"] . "'>" . $familia["nombre"] . "</option>";
						}
					}
					?>
				</select>
			</p>
			<p>
				<button type="submit">Volver</button>
				<button type="submit" name="btnContInsertar">Continuar</button>
			</p>
		</form>
	<?php
	}
	?>
	<table class="text-center table-center">
		<tr>
			<th>COD</th>
			<th>Nombre</th>
			<th>PVP (€)</th>
			<th>
				<form action="index.php" method="post">
					<button class="enlace" type="submit" name="btnInsertar">Producto+</button>
				</form>
			</th>
		</tr>
		<?php
		foreach ($json_productos["productos"] as $producto) {
			echo "<tr>";
			echo "<td>";

			echo "<form action='index.php' method='post'>";
			echo "<button type='submit' class='enlace' name='btnDetalles' value='" . $producto["cod"] . "'>" . $producto["cod"] . "</button>";
			echo "<input type='hidden' name='h_cod' value='" . $producto["cod"] . "'>";
			echo "</form>";

			echo "</td>";
			echo "<td>" . $producto["nombre_corto"] . "</td>";
			echo "<td>" . $producto["PVP"] . "</td>";
			echo "<td>";

			echo "<form action='index.php' method='post'>";
			echo "<button type='submit' class='enlace' name='btnBorrar' value=''>Borrar</button>";
			echo " - ";
			echo "<button type='submit' class='enlace' name='btnEditar' value=''>Editar</button>";
			echo "<input type='hidden' name='h_cod' value='" . $producto["cod"] . "'>";
			echo "</form>";

			echo "</td>";
			echo "</tr>";
		}
		?>
	</table>
</body>

</html>