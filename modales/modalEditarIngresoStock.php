<?php if ($rol === 'admin'): ?>
<div class="modal fade" id="modalEditarIngresoStock" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Editar Ingreso de Stock</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEditarIngresoStock" class="modal-body">
        <input type="hidden" name="id_ingreso" id="id_ingreso">
        <input type="hidden" name="producto_id" id="producto_id_edit">

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Número de Boleta/Remito</label>
            <input type="text" name="numero_boleta" id="numero_boleta_edit" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Proveedor (opcional)</label>
            <input type="text" name="proveedor" id="proveedor_edit" class="form-control">
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Cantidad Total</label>
            <input type="number" min="1" name="cantidad_total" id="cantidad_total_edit" class="form-control" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Precio Costo</label>
            <input type="number" step="0.01" name="precio_costo" id="precio_costo_edit" class="form-control" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Fecha de Vencimiento</label>
            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento_edit" class="form-control">
          </div>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
