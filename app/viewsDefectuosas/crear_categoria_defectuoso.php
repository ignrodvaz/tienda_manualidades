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
        <h1><?= isset($categoria['PK_ID_CATEGORIA']) ? 'Editar Categoría' : 'Crear Categoría' ?></h1>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= isset($categoria) ? base_url('categoria/save/') . $categoria['PK_ID_CATEGORIA'] : base_url('categoria/save') ?>" method="post">
            <div class="form-group mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= esc($categoria['NOMBRE'] ?? set_value('nombre')) ?>">
            </div>
            <div class="form-group mb-3">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion"><?= esc($categoria['DESCRIPCION'] ?? set_value('descripcion')) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($categoria) ? 'Actualizar' : 'Guardar'?></button>
            <a href="<?= base_url('categoria') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>