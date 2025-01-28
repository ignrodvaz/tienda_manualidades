<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');

// Rutas para Categoría
$routes->get('categoria', 'CategoriaController::index'); //Esta ruta muestra el listado de categorías.

//Estas rutas manejan tanto la creación como la edición de categorías.
$routes->get('categoria/save', 'CategoriaController::saveCategoria'); // Mostrar formulario para crear categoría (GET)
$routes->post('categoria/save', 'CategoriaController::saveCategoria'); // Crear categoría (POST)
$routes->get('categoria/save/(:num)', 'CategoriaController::saveCategoria/$1'); // Mostrar formulario para editar categoría (GET)
$routes->post('categoria/save/(:num)', 'CategoriaController::saveCategoria/$1'); // Editar categoría (POST)

$routes->get('categoria/delete/(:num)', 'CategoriaController::delete/$1'); //Esta ruta elimina una categoría específica usando su ID.