
<?php

// Rutas de Login
$router->get('/', 'AuthController@login');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate'); 
// Rutas de Registro
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@register'); 

// Dashboard y Logout
$router->get('/dashboard', 'DashboardController@index');
$router->get('/logout', 'AuthController@logout');
$router->post('/guardar-perfil', 'AuthController@guardarPerfil');
// Administración y Cursos
$router->get('/admin', 'AdminController@index');
$router->get('/cursos', 'CursoController@index');
$router->get('/cursos/create', 'CursoController@create');
$router->post('/cursos/store', 'CursoController@store');
$router->get('/cursos/show', 'CursoController@show');
