<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Categorias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<body>
<div class="container mt-5">
    <h1 class="text-center">Listado de Categorias</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            toastr.success('<?= session()->getFlashdata('success'); ?>');
        </script>
    <?php endif; ?>
    <form action="<?= base_url('categoria')?>" class="mb-3">
        <div class="container d-flex">
            <a href="<?=base_url('categoria/save')?>" class="btn btn-primary w-auto me-2">Crear Categoria</a>
            <div class="input-group w-auto ms-5">
                <input type="text" name="NOMBRE" class="form-control" placeholder="Nombre" value="<?= esc($name) ?>">
                <input type="text" name="DESCRIPCION" class="form-control" placeholder="Descripcion" value="<?= esc($descripcion) ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
            <div class="d-flex input-group w-auto ms-5">
                <select name="" id="" class=" form-control w-auto selectpicker">
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="">Bajas</option>
                    <option value="">Altas</option>
                    <option value="">Todas</option>
                </select>
                <a href="" class="btn btn-primary w-auto">Ver listado</a>
            </div>
        </div>
    </form>

    <?php if (!empty($categorias) && is_array($categorias)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NOMBRE</th>
                    <th>DESCRIPCION</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= esc($categoria['NOMBRE']) ?></td>
                        <td><?= esc($categoria['DESCRIPCION']) ?></td>
                        <td>
                            <a href="<?= base_url('categoria/save/' . $categoria['PK_ID_CATEGORIA']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= base_url('categoria/delete/' . esc($categoria['PK_ID_CATEGORIA'])) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar esta categoría?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-4">
            <?= $pager ->only(['NOMBRE'])->links('default', 'custom_pagination')?> <!--Usa la plantilla predeterminada -->
        </div>
    <?php else: ?>
        <p class="text-center">No hay categorías registradas.</p>
    <?php endif; ?>
</div>
</body>
</html>