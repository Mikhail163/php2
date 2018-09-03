<?php 

require __DIR__.'/vendor/autoload.php';

$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');

// create twig object
$twig = new Twig_Environment($loader/*, array(
		'cache' => '/path/to/compilation_cache',
)*/);

echo $twig->render( 'index.html', [
		'title' => 'Первая страница Twig',
		'content' => 'Это самая крутая тестовая страница супер пупер контента '
]);