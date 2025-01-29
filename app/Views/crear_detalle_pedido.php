<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Categoria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1><?= isset($detalle['PK_ID_DETALLE']) ? 'Editar Detalle Pedido' : 'Crear Detalle Pedido' ?></h1>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= isset($detalle) ? base_url('detalle_pedido/save/') . $detalle['PK_ID_DETALLE'] : base_url('detalle_pedido/save') ?>" method="post">
            <div class="form-group mb-3">
                <label for="cantidad">cantidad</label>
                <input type="text" class="form-control" id="cantidad" name="cantidad" value="<?= esc($detalle['CANTIDAD'] ?? set_value('cantidad')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="precio_unitario">Precio Unitario</label>
                <input class="form-control" id="precio_unitario" name="precio_unitario" value="<?= esc($detalle['PRECIO_UNITARIO'] ?? set_value('precio_unitario')) ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($detalle) ? 'Actualizar' : 'Guardar'?></button>
            <a href="<?= base_url('detalle_pedido') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>