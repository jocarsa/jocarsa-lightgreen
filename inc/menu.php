<?php
/*
	Función que genera un menú recursivo a partir del contenido de las carpetas
*/
function listFolderTree($dir, &$counter = 0)
{
    $files = scandir($dir);
    $output = "<ul>";
    foreach ($files as $file) {
        if ($file === "." || $file === "..") {
            continue;
        }
        $filePath = $dir . DIRECTORY_SEPARATOR . $file;
        
        if (is_dir($filePath)) {
            $folderId = "folder-" . $counter++;
            $icon = '<span class="folder-icon">📂</span>';
            $displayName = htmlspecialchars($file);
            $output .= "<li class=\"folder-header\" data-folder-id=\"$folderId\">{$icon} {$displayName}";
            $output .= listFolderTree($filePath, $counter);
            $output .= "</li>";
        } else {
            $icon = "📄";
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $displayName = "<a href='?archivo=" . codifica(urlencode($filePath)) . "'>" . htmlspecialchars($fileName) . "</a>";
            $output .= "<li>{$icon} {$displayName}</li>";
        }
    }
    $output .= "</ul>";
    return $output;
}
?>

