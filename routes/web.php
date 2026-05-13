
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
$router->get('/practica/raton', 'PracticaController@raton');
$router->post('/perfil/vincular', 'PerfilController@vincularTutor');
$router->get('/cursos', 'CursoController@index');
$router->get('/cursos/leccion/1', 'CursoController@leccion1');
$router->get('/practica', 'PracticaController@index');
$router->get('/practica/nuevo', 'PracticaController@create');
$router->post('/practica/store', 'PracticaController@store');
$router->get('/tutor/alumno/([0-9]+)', 'TutorController@verAlumno');
$router->get('/actividad/ver/([0-9]+)', 'ActividadController@ver');
$router->get('/actividad/avanzar/([0-9]+)', 'ActividadController@avanzar');
$router->get('/practica/crear-secuencia', 'PracticaController@crearSecuencia');
$router->post('/practica/guardar-secuencia', 'PracticaController@guardarSecuencia');