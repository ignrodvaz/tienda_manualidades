<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pedidos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<body>
<div class="container w-100 mt-5">
    <h1 class="text-center">Listado de Pedidos</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            toastr.success('<?= session()->getFlashdata('success'); ?>');
        </script>
    <?php endif; ?>
    <form action="<?= base_url('pedido')?>" class="mb-3">
        <div class="container d-flex">
            <a href="<?=base_url('pedido/save')?>" class="btn btn-primary w-auto me-3">Crear Pedido</a>
            <div class="input-group">
                <input type="text" name="FECHA_PEDIDO" class="form-control" placeholder="Fecha Pedido" value="<?= esc($fecha_pedido) ?>">
                <input type="text" name="DIRECCION_PEDIDO" class="form-control" placeholder="Direccion" value="<?= esc($direccion_pedido) ?>">
                <input type="text" name="TOTAL_PEDIDO" class="form-control" placeholder="Total" value="<?= esc($total_pedido) ?>">
                <input type="text" name="ESTADO" class="form-control" placeholder="Estado" value="<?= esc($estado_pedido) ?>">
                <input type="text" name="FK_ID_CLIENTE" class="form-control" placeholder="ID Cliente" value="<?= esc($fk_id_cliente) ?>">
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

    <?php if (!empty($pedidos) && is_array($pedidos)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>FECHA PEDIDO</th>
                    <th>DIRECCION PEDIDO</th>
                    <th>TOTAL PEDIDO</th>
                    <th>ESTADO</th>
                    <th>ID CLIENTE</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= esc($pedido['FECHA_PEDIDO']) ?></td>
                        <td><?= esc($pedido['DIRECCION_PEDIDO']) ?></td>
                        <td><?= esc($pedido['TOTAL_PEDIDO']) ?></td>
                        <td><?= esc($pedido['ESTADO']) ?></td>
                        <td><?= esc($pedido['FK_ID_CLIENTE']) ?></td>
                        <td>
                            <a href="<?= base_url('pedido/save/' . $pedido['PK_ID_PEDIDO']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= base_url('pedido/delete/' . esc($pedido['PK_ID_PEDIDO'])) ?>" 
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