<?php

// Rutas de Login
$router->get('/', 'AuthController@login');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate'); 

// Rutas de Registro
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@register'); 
$router->post('/verificar-email', 'AuthController@verificarEmail'); 

// Dashboard y Logout
$router->get('/dashboard', 'DashboardController@index');
$router->get('/logout', 'AuthController@logout');
$router->post('/guardar-perfil', 'AuthController@guardarPerfil');

// Administración y Cursos
$router->get('/admin', 'AdminController@index');
$router->get('/cursos', 'CursoController@index');
$router->get('/cursos/create', 'CursoController@create');
$router->post('/cursos/store', 'CursoController@store');
$router->post('/perfil/vincular', 'PerfilController@vincularTutor');
$router->get('/cursos/leccion/1', 'CursoController@leccion1');

$router->get('/practica', 'PracticaController@index');
$router->get('/practica/nuevo', 'PracticaController@create');
$router->post('/practica/store', 'PracticaController@store');
$router->get('/practica/crear-secuencia', 'PracticaController@crearSecuencia');
$router->post('/practica/guardar-secuencia', 'PracticaController@guardarSecuencia');
$router->get('/practica/ver-secuencia/([0-9]+)', 'PracticaController@verSecuencia');
$router->post('/practica/guardarPuntuacion', 'PracticaController@guardarPuntuacion');

$router->get('/practica/raton', 'PracticaController@raton');
$router->get('/practica/teclado', 'PracticaController@teclado');
$router->get('/practica/clicks', 'PracticaController@clicks');
$router->get('/practica/dobleclick', 'PracticaController@dobleclick');
$router->get('/practica/arrastrar', 'PracticaController@arrastrar');
$router->get('/practica/frases', 'PracticaController@frases'); 

$router->get('/tutor/alumno/([0-9]+)', 'TutorController@verAlumno');
$router->post('/tutor/guardar-mensaje', 'TutorController@guardarmensaje'); 
$router->get('/admin/alumnos', 'TutorController@adminAlumnos');
$router->get('/tutor/procesar_solicitud', 'TutorController@procesarSolicitud');
$router->post('/tutor/desasociar-alumno', 'TutorController@desasociarAlumno');

$router->get('/cursos/ver-leccion/([0-9]+)', 'CursoController@verLeccionCompleta');
$router->post('/cursos/guardar-progreso', 'CursoController@guardarProgresoClase');