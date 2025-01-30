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
        <h1><?= isset($pedido['PK_ID_PRODUCTO']) ? 'Editar Producto' : 'Crear Producto' ?></h1>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= isset($producto) ? base_url('producto/save/') . $producto['PK_ID_PRODUCTO'] : base_url('producto/save') ?>" method="post">
            <div class="form-group mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= esc($producto['NOMBRE'] ?? set_value('nombre')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="descripcion">Descripcion</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?= esc($producto['DESCRIPCION'] ?? set_value('descripcion')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="precio">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" value="<?= esc($producto['PRECIO'] ?? set_value('precio')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="stock">Stock</label>
                <input class="form-control" id="stock" name="stock" value="<?= esc($producto['STOCK'] ?? set_value('stock')) ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($producto) ? 'Actualizar' : 'Guardar'?></button>
            <a href="<?= base_url('producto') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>