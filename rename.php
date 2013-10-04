<?

/**
 * \
 */

$objects = new RecursiveIteratorIterator
(new RecursiveDirectoryIterator(__DIR__),
    RecursiveIteratorIterator::SELF_FIRST);

foreach ($objects as $path => $fileObject) {

    $file_name = '';
    $base_name = '';

    $use = true;

    if (is_dir($fileObject)) {

        if ($fileObject->getFileName() == '.' || $fileObject->getFileName() == '..') {
        } else {

            $path = $fileObject->getPathName();

            foreach (scandir($path) as $filename) {
                if ($filename == '.' || $filename == '..') {
                } else {
                    $newname = preg_replace('"\.html.php"', '.phtml', $filename);
                    rename($path . '/' . $filename, $path . '/' . $newname);
                    echo $path . '/' . $newname . '<br />';
                }
            }
        }
    }
}
