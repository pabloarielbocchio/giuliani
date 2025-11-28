<?php
/**
 * Script para eliminar BOM (Byte Order Mark) de archivos PHP
 * Ejecutar desde la línea de comandos: C:\xampp\php\php.exe remove_bom.php
 */

function removeBOM($file) {
    $content = file_get_contents($file, 'rb');
    
    // Detectar BOM UTF-8 (EF BB BF)
    $bom1 = "\xEF\xBB\xBF";
    $bom2 = pack('H*', 'EFBBBF');
    $bom3 = chr(239) . chr(187) . chr(191);
    
    $hasBOM = false;
    if (substr($content, 0, 3) === $bom1 || substr($content, 0, 3) === $bom2 || substr($content, 0, 3) === $bom3) {
        $hasBOM = true;
    }
    
    // También verificar con bin2hex para estar seguros
    $hex = bin2hex(substr($content, 0, 3));
    if ($hex === 'efbbbf') {
        $hasBOM = true;
    }
    
    if ($hasBOM) {
        $content = substr($content, 3);
        file_put_contents($file, $content, LOCK_EX);
        return true;
    }
    return false;
}

function getAllPhpFiles($dir) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }
    
    return $files;
}

echo "Buscando y eliminando BOM en archivos PHP...\n\n";

// Buscar en todos los archivos PHP del proyecto
$allFiles = getAllPhpFiles(__DIR__);

// Excluir algunos directorios si es necesario
$excludeDirs = ['vendor', 'node_modules', '.git'];
$files = [];
foreach ($allFiles as $file) {
    $exclude = false;
    foreach ($excludeDirs as $dir) {
        if (strpos($file, $dir) !== false) {
            $exclude = true;
            break;
        }
    }
    if (!$exclude) {
        $files[] = $file;
    }
}

$removed = 0;
$checked = 0;

foreach ($files as $file) {
    $checked++;
    $relativePath = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file);
    if (removeBOM($file)) {
        echo "✓ BOM eliminado de: $relativePath\n";
        $removed++;
    }
}

echo "\n";
echo "Archivos verificados: $checked\n";
echo "BOM eliminados: $removed\n";
echo "\n¡Proceso completado!\n";

