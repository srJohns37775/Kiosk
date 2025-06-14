<?php if ($rol === 'admin'): ?>
<div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="modalProductoTitle">Agregar Producto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formProducto" class="modal-body">
        <input type="hidden" name="id" id="productoId">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" class="form-control" required
                   title="Nombre descriptivo del producto, por ejemplo: 'Gaseosa 2lts'.">
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select" required
                    title="Seleccioná la categoría general del producto (ej: Bebidas, Snacks)."></select>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Marca</label>
            <select name="marca_id" class="form-select" required
                    title="Seleccioná la marca del producto, como CocaCola, Lays, etc."></select>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Stock Mínimo</label>
            <input type="number" name="stock_minimo" class="form-control" min="0" required
                   title="Cantidad mínima que se recomienda tener disponible antes de que se muestre una alerta de stock bajo.">
          </div>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-success">Guardar Producto</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
