<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Detalles de Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<body>
<div class="container w-100 mt-5">
    <h1 class="text-center">Listado de Detalles de Pedido</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            toastr.success('<?= session()->getFlashdata('success'); ?>');
        </script>
    <?php endif; ?>
    <form action="<?= base_url('detalle_pedido')?>" class="mb-3">
        <div class="container d-flex">
            <a href="<?=base_url('detalle_pedido/save')?>" class="btn btn-primary w-auto me-3">Crear Detalle Pedido</a>
            <div class="input-group">
                <input type="text" name="CANTIDAD" class="form-control" placeholder="Cantidad" value="<?= esc($cantidad) ?>">
                <input type="text" name="PRECIO_UNITARIO" class="form-control" placeholder="Precio" value="<?= esc($precio_unitario) ?>">
                <input type="text" name="FK_ID_PEDIDO" class="form-control" placeholder="Id Pedido" value="<?= esc($fk_id_pedido) ?>">
                <input type="text" name="FK_ID_PRODUCTO" class="form-control" placeholder="Id Producto" value="<?= esc($fk_id_producto) ?>">
                <select name="estado" id="estado" class="form-control w-auto h-100 selectpicker">
                    <option value="" disabled <?= $estado === null ? 'selected' : '' ?>>Seleccione una opción</option>
                    <option value="altas" <?= $estado === 'altas' ? 'selected' : '' ?>>Altas</option>
                    <option value="bajas" <?= $estado === 'bajas' ? 'selected' : '' ?>>Bajas</option>
                    <option value="todas" <?= $estado === 'todas' ? 'selected' : '' ?>>Todas</option>
                </select>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <?php if (!empty($detalles) && is_array($detalles)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CANTIDAD</th>
                    <th>PRECIO</th>
                    <th>ID PEDIDO</th>
                    <th>ID PRODUCTO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $detalle): ?>
                    <tr>
                        <td><?= esc($detalle['PK_ID_DETALLE']) ?></td>
                        <td><?= esc($detalle['CANTIDAD']) ?></td>
                        <td><?= esc($detalle['PRECIO_UNITARIO']) ?></td>
                        <td><?= esc($detalle['FK_ID_PEDIDO']) ?></td>
                        <td><?= esc($detalle['FK_ID_PRODUCTO']) ?></td>
                        <td>
                            <a href="<?= base_url('detalle_pedido/save/' . $detalle['PK_ID_DETALLE']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= base_url('detalle_pedido/delete/' . esc($detalle['PK_ID_DETALLE'])) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar este detalle de pedido?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-4">
            <?= $pager ->only(['CANTIDAD'])->links('default', 'custom_pagination')?> <!--Usa la plantilla predeterminada -->
        </div>
    <?php else: ?>
        <p class="text-center">No hay detalles de pedido registrados.</p>
    <?php endif; ?>
</div>
</body>
</html>
