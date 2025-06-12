_<?php if ($rol === 'admin'): ?>
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
            <input type="text" name="descripcion" class="form-control" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select" required></select>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Marca</label>
            <select name="marca_id" class="form-select" required></select>
          </div>
          <div class="col-md-12 mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="esPack" name="es_pack">
            <label class="form-check-label" for="esPack">¿Viene en pack/caja?</label>
          </div>
          <div id="packFields" class="row d-none">
            <div class="col-md-6 mb-3">
              <label class="form-label">Cantidad de Packs</label>
              <input type="number" min="1" name="cantidad_packs" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Unidades por Pack</label>
              <input type="number" min="1" name="unidades_pack" class="form-control">
            </div>
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
