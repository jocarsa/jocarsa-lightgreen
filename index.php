<?php
// Include the menu builder and the Markdown converter
include "inc/menu.php";
include "inc/MarkdownConverter.php";
$converter = new MarkdownConverter();

/**
 * Recursively search for files whose filenames match the given term.
 * Returns an array of matching file paths.
 */
function searchFiles($dir, $term) {
    $found = [];
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $filePath = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            // Recurse into subfolders
            $found = array_merge($found, searchFiles($filePath, $term));
        } else {
            // Check if filename contains the term
            if (stripos($file, $term) !== false) {
                $found[] = $filePath;
            }
        }
    }
    return $found;
}

// Check if the user requested a specific file to load
$requestedFile = isset($_GET['archivo']) ? $_GET['archivo'] : null;

// Check if there's a search query
$searchTerm = isset($_GET['s']) ? trim($_GET['s']) : "";
$searchResults = [];
if ($searchTerm !== "") {
    // Perform the search in the docs folder
    $searchResults = searchFiles("docs", $searchTerm);
}
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
            // Show your folder tree navigation
            echo listFolderTree("docs");
        ?>
    </nav>
    <section>
        <?php
            // 1) If there's a requested file, display its contents
            if ($requestedFile) {
                echo $converter->convertUrlToHtml($requestedFile);
            }

            // 2) If there's a search term, display the results
            if ($searchTerm !== "") {
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
                                <a href='?archivo=" . urlencode($result) . "'>
                                    " . htmlspecialchars($nameNoExt) . "
                                </a>
                              </li>";
                    }
                    echo "</ul>";
                }
            }
        ?>
    </section>
</main>
</body>
</html>

