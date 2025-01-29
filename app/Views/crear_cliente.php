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
        <h1><?= isset($cliente['PK_ID_CLIENTE']) ? 'Editar Cliente' : 'Crear Cliente' ?></h1>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= isset($cliente) ? base_url('cliente/save/') . $cliente['PK_ID_CLIENTE'] : base_url('cliente/save') ?>" method="post">
            <div class="form-group mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= esc($cliente['NOMBRE'] ?? set_value('nombre')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input class="form-control" id="email" name="email" value="<?= esc($cliente['EMAIL'] ?? set_value('email')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="telefono">Telefono</label>
                <input class="form-control" id="telefono" name="telefono" value="<?= esc($cliente['TELEFONO'] ?? set_value('telefono')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="direccion">Direccion</label>
                <input class="form-control" id="direccion" name="direccion" value="<?= esc($cliente['DIRECCION'] ?? set_value('direccion')) ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($cliente) ? 'Actualizar' : 'Guardar'?></button>
            <a href="<?= base_url('cliente') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>