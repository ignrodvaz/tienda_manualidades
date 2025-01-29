<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');

//CATEGORIA
// Rutas para Categoría
$routes->get('categoria', 'CategoriaController::index'); //Esta ruta muestra el listado de categorías.
//Estas rutas manejan tanto la creación como la edición de CATEGORIAS.
$routes->get('categoria/save', 'CategoriaController::saveCategoria'); // Mostrar formulario para crear categoría (GET)
$routes->post('categoria/save', 'CategoriaController::saveCategoria'); // Crear categoría (POST)
$routes->get('categoria/save/(:num)', 'CategoriaController::saveCategoria/$1'); // Mostrar formulario para editar categoría (GET)
$routes->post('categoria/save/(:num)', 'CategoriaController::saveCategoria/$1'); // Editar categoría (POST)
$routes->get('categoria/delete/(:num)', 'CategoriaController::delete/$1'); //Esta ruta elimina una categoría específica usando su ID.

//CLIENTE
//Rutas para Cliente
$routes->get('cliente', 'ClienteController::index'); //Esta ruta muestra el listado de CLIENTE.
//Estas rutas manejan tanto la creación como la edición de CLIENTE.
$routes->get('cliente/save', 'ClienteController::saveCliente'); // Mostrar formulario para crear CLIENTE (GET)
$routes->post('cliente/save', 'ClienteController::saveCliente'); // Crear CLIENTE (POST)
$routes->get('cliente/save/(:num)', 'ClienteController::saveCliente/$1'); // Mostrar formulario para editar CLIENTE (GET)
$routes->post('cliente/save/(:num)', 'ClienteController::saveCliente/$1'); // Editar CLIENTE (POST)
$routes->get('cliente/delete/(:num)', 'ClienteController::delete/$1'); //Esta ruta elimina una CLIENTE específica usando su ID.

//DETALLE_PEDIDO
//Rutas para Detalle_pedido
$routes->get('detalle_pedido', 'DetallePedidoController::index'); //Esta ruta muestra el listado de DETALLE_PEDIDO.
//Estas rutas manejan tanto la creación como la edición de DETALLE_PEDIDO.
$routes->get('detalle_pedido/save', 'DetallePedidoController::saveDetallePedido'); // Mostrar formulario para crear DETALLE_PEDIDO (GET)
$routes->post('detalle_pedido/save', 'DetallePedidoController::saveDetallePedido'); // Crear DETALLE_PEDIDO (POST)
$routes->get('detalle_pedido/save/(:num)', 'DetallePedidoController::saveDetallePedido/$1'); // Mostrar formulario para editar DETALLE_PEDIDO (GET)
$routes->post('detalle_pedido/save/(:num)', 'DetallePedidoController::saveDetallePedido/$1'); // Editar DETALLE_PEDIDO (POST)
$routes->get('detalle_pedido/delete/(:num)', 'DetallePedidoController::delete/$1'); //Esta ruta elimina una DETALLE_PEDIDO específica usando su ID.

//PEDIDO
//Rutas para Pedido
$routes->get('pedido', 'PedidoController::index'); //Esta ruta muestra el listado de PEDIDO.
//Estas rutas manejan tanto la creación como la edición de PEDIDO.
$routes->get('pedido/save', 'PedidoController::savePedido'); // Mostrar formulario para crear PEDIDO (GET)
$routes->post('pedido/save', 'PedidoController::savePedido'); // Crear PEDIDO (POST)
$routes->get('pedido/save/(:num)', 'PedidoController::savePedido/$1'); // Mostrar formulario para editar PEDIDO (GET)
$routes->post('pedido/save/(:num)', 'PedidoController::savePedido/$1'); // Editar PEDIDO (POST)
$routes->get('pedido/delete/(:num)', 'PedidoController::delete/$1'); //Esta ruta elimina una PEDIDO específica usando su ID.