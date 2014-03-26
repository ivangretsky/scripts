<?php
print $_SERVER['PHP_SELF'];
file_download($_GET['file']);
function file_download($filename) {
// Проверяем существование файла

//  Перенаправляем клиента на файл.
    header('Location: ' . $filename);
    exit;

// Прерываем дальнейшее выполнение скрипта, чтобы не отправлять мусор в ответе клиенту
  
}
?>