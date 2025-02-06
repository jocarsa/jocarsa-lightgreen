<?php
/*
	Archivo principal de la aplicación, cargamos la interfaz y los includes
*/
include "inc/menu.php";
include "inc/MarkdownConverter.php";
include "inc/codificador.php";
include "inc/buscador.php";
?>
<!doctype html>
<html>
<head>
    <title>jocarsa | lightgreen</title>
    <link rel="stylesheet" href="estilo.css">
    <script src="carpetas.js"></script>
    <link rel="icon" type="image/svg+xml" href="lightgreen.png" />
</head>
<body>
<header>
    <h1>
        <img src="lightgreen.png" alt="Logo"> jocarsa | lightgreen
    </h1>
    <!-- Use a GET form to trigger the search when pressing Enter -->
    <form method="GET" action="" style="margin: 0;">
        <input 
            type="search" 
            id="buscador" 
            name="s"
            value="<?php echo htmlspecialchars($searchTerm); ?>"
            placeholder="Search..."
        >
    </form>
</header>
<main>
    <nav>
        <?php
            echo listFolderTree("docs");
        ?>
    </nav>
    <section>
        <?php
        		$requestedFile = isset($_GET['archivo']) ? decodifica($_GET['archivo']) : null;
            if ($requestedFile) {
            	$converter = new MarkdownConverter();
                echo $converter->convertUrlToHtml(urldecode($requestedFile));
            }
            if ($searchTerm !== "") {
                include "../resultadosbusqueda.php";
            }
        ?>
    </section>
</main>
</body>
</html>

