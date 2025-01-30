<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<body>
<div class="container w-100 mt-5">
    <h1 class="text-center">Listado de Productos</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            toastr.success('<?= session()->getFlashdata('success'); ?>');
        </script>
    <?php endif; ?>
    <form action="<?= base_url('producto')?>" class="mb-3">
        <div class="container d-flex">
            <a href="<?=base_url('producto/save')?>" class="btn btn-primary w-auto me-3">Crear Producto</a>
            <div class="input-group">
                <input type="text" name="NOMBRE" class="form-control" placeholder="Nombre" value="<?= esc($name) ?>">
                <input type="text" name="DESCRIPCION" class="form-control" placeholder="Descripcion" value="<?= esc($descripcion) ?>">
                <input type="text" name="PRECIO" class="form-control" placeholder="Precio" value="<?= esc($precio) ?>">
                <input type="text" name="STOCK" class="form-control" placeholder="Stock" value="<?= esc($stock) ?>">
                <input type="text" name="FK_ID_CATEGORIA" class="form-control" placeholder="ID Categoria" value="<?= esc($fk_id_categoria) ?>">
                <select name="estado" id="estado" class="form-control h-100 selectpicker">
                    <option value="" disabled <?= $estado === null ? 'selected' : '' ?>>Seleccione una opción</option>
                    <option value="altas" <?= $estado === 'altas' ? 'selected' : '' ?>>Altas</option>
                    <option value="bajas" <?= $estado === 'bajas' ? 'selected' : '' ?>>Bajas</option>
                    <option value="todas" <?= $estado === 'todas' ? 'selected' : '' ?>>Todas</option>
                </select>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <?php if (!empty($productos) && is_array($productos)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NOMBRE</th>
                    <th>DESCRIPCION</th>
                    <th>PRECIO</th>
                    <th>STOCK</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= esc($producto['NOMBRE']) ?></td>
                        <td><?= esc($producto['DESCRIPCION']) ?></td>
                        <td><?= esc($producto['PRECIO']) ?></td>
                        <td><?= esc($producto['STOCK']) ?></td>
                        <td>
                            <a href="<?= base_url('producto/save/' . $producto['PK_ID_PRODUCTO']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= base_url('producto/delete/' . esc($producto['PK_ID_PRODUCTO'])) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar este cliente?');">Eliminar</a>
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