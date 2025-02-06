<?php
/*
	 Esta función realiza una búsqueda recursiva
	 Devuelve un array de resultados
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
?>
