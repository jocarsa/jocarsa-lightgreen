<?php
	/*
		En el caso de que haya resultados en la búsqueda, mostramos los resultados
	*/

$searchTerm = isset($_GET['s']) ? trim($_GET['s']) : "";
$searchResults = [];
if ($searchTerm !== "") {
    // Perform the search in the docs folder
    $searchResults = searchFiles("docs", $searchTerm);
}

	echo "<h2>Search results for: <em>" . htmlspecialchars($searchTerm) . "</em></h2>";

                if (count($searchResults) === 0) {
                    echo "<p>No matching files found.</p>";
                } else {
                    echo "<ul>";
                    foreach ($searchResults as $result) {
                        // Extract a nicer display name (filename without extension)
                        $filename = basename($result);
                        $nameNoExt = pathinfo($filename, PATHINFO_FILENAME);
                        // Create a link that sets ?archivo= that file
                        echo "<li>
                                📄 
                                <a href='?archivo=" . codifica(urlencode($result)) . "'>
                                    " . htmlspecialchars($nameNoExt) . "
                                </a>
                              </li>";
                    }
                    echo "</ul>";
                }

?>
