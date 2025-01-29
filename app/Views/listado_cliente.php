<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<body>
<div class="container w-100 mt-5">
    <h1 class="text-center">Listado de Clientes</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            toastr.success('<?= session()->getFlashdata('success'); ?>');
        </script>
    <?php endif; ?>
    <form action="<?= base_url('cliente')?>" class="mb-3">
        <div class="container d-flex">
            <a href="<?=base_url('cliente/save')?>" class="btn btn-primary w-auto me-3">Crear Cliente</a>
            <div class="input-group">
                <input type="text" name="NOMBRE" class="form-control" placeholder="Nombre" value="<?= esc($name) ?>">
                <input type="text" name="EMAIL" class="form-control" placeholder="Email" value="<?= esc($email) ?>">
                <input type="text" name="TELEFONO" class="form-control" placeholder="Telefono" value="<?= esc($telefono) ?>">
                <input type="text" name="DIRECCION" class="form-control" placeholder="Direccion" value="<?= esc($direccion) ?>">
                <input type="text" name="FECHA_REGISTRO" class="form-control" placeholder="Fecha Registro" value="<?= esc($fecha_registro) ?>">
                <input type="text" name="ROL" class="form-control" placeholder="Rol" value="<?= esc($rol) ?>">
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

    <?php if (!empty($clientes) && is_array($clientes)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NOMBRE</th>
                    <th>EMAIL</th>
                    <th>TELEFONO</th>
                    <th>DIRECCION</th>
                    <th>FECHA REGISTRO</th>
                    <th>ROL</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= esc($cliente['NOMBRE']) ?></td>
                        <td><?= esc($cliente['EMAIL']) ?></td>
                        <td><?= esc($cliente['TELEFONO']) ?></td>
                        <td><?= esc($cliente['DIRECCION']) ?></td>
                        <td><?= esc($cliente['FECHA_REGISTRO']) ?></td>
                        <td><?= esc($cliente['ROL_NOMBRE']) ?></td>
                        <td>
                            <a href="<?= base_url('cliente/save/' . $cliente['PK_ID_CLIENTE']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= base_url('cliente/delete/' . esc($cliente['PK_ID_CLIENTE'])) ?>" 
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