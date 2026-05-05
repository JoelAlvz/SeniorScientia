<h2>Cursos</h2>

<?php if ($_SESSION['user']['tipo'] !== 'alumno'): ?>
    <a href="/cursos/create">Crear curso</a>
<?php endif; ?>
<ul>
<?php foreach ($cursos as $curso): ?>
    <li>
        <a href="/cursos/show?id=<?= $curso['id_curso'] ?>">
            <?= $curso['titulo'] ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>