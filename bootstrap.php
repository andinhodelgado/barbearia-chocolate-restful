<?php

use Util\ConstantesGenericasUtil;

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ERROR);

define('HOST', 'localhost');
define('BANCO', 'barbearia_chocolate');

define('USER', 'root');
define('SENHA', '');

define('DS', DIRECTORY_SEPARATOR);
define('DIR_APP', __DIR__);
define('DIR_PROJETO', 'barbearia-chocolate-restful');

define('DIR_IMG', DIR_APP . DS . 'img' . DS);
define('DIR_DOC', DIR_APP . DS . 'doc' . DS);

if(file_exists('autoload.php')){
    include('autoload.php');
} else {
    throw new Exception('Erro ao incluir boostrap', 404);
    exit;
}