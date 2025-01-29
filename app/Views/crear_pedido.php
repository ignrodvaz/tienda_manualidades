<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1><?= isset($pedido['PK_ID_PEDIDO']) ? 'Editar Pedido' : 'Crear Pedido' ?></h1>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= isset($pedido) ? base_url('pedido/save/') . $pedido['PK_ID_PEDIDO'] : base_url('pedido/save') ?>" method="post">
            <div class="form-group mb-3">
                <label for="fecha_pedido">Fecha Pedido</label>
                <input type="date" class="form-control" id="fecha_pedido" name="fecha_pedido" value="<?= esc($pedido['FECHA_PEDIDO'] ?? set_value('fecha_pedido')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="direccion_pedido">Direccion Pedido</label>
                <input type="text" class="form-control" id="direccion_pedido" name="direccion_pedido" value="<?= esc($pedido['DIRECCION_PEDIDO'] ?? set_value('direccion_pedido')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="total_pedido">Total Pedido</label>
                <input class="form-control" id="total_pedido" name="total_pedido" value="<?= esc($pedido['TOTAL_PEDIDO'] ?? set_value('total_pedido')) ?>">
            </div>
            <label for="estado_pedido">Estado</label>
            <select name="estado_pedido" id="estado_pedido" class="form-control mb-3">
                <option value="" disabled selected>Seleccione una opción</option>
                <option value="">Pendiente</option>
                <option value="">Confirmado</option>
                <option value="">En preparacion</option>
                <option value="">Enviado</option>
                <option value="">Entregado</option>
            </select>
            <div class="form-group mb-3">
                <label for="estado_pedido">Estado</label>
                <input class="form-control" id="estado_pedido" name="estado_pedido" value="<?= esc($pedido['ESTADO'] ?? set_value('estado_pedido')) ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($pedido) ? 'Actualizar' : 'Guardar'?></button>
            <a href="<?= base_url('detalle_pedido') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>