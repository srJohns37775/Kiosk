<?php if ($rol === 'admin'): ?>
<div class="modal fade" id="modalIngresoStock" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title">Ingreso de Stock</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formIngresoStock" class="modal-body">
        <div class="row">
          <div class="col-12 mb-3">
            <label class="form-label">Producto</label>
            <select name="producto_id" id="producto_id_ingreso" class="form-select" required></select>
            <small class="text-info d-block mt-1">Producto seleccionado: <span id="descripcionProductoSeleccionado">Ninguno</span></small>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Número de Boleta/Remito</label>
            <input type="text" name="numero_boleta" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Proveedor (opcional)</label>
            <input type="text" name="proveedor" class="form-control">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Precio Costo Unidad</label>
            <input type="number" step="0.01" name="precio_costo" class="form-control" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">% Ganancia (markup)</label>
            <input type="number" step="0.01" name="markup" class="form-control">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Precio Venta</label>
            <input type="number" step="0.01" name="precio_venta" class="form-control" readonly>
          </div>
          <div class="row" id="packIngresoFields" style="display:none;">
            <div class="col-md-6 mb-3">
                <label class="form-label">Cantidad de Packs</label>
                <input type="number" min="1" name="cantidad_packs" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Unidades por Pack</label>
                <input type="number" min="1" name="unidades_pack" class="form-control">
            </div>
            </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Cantidad a Ingresar</label>
            <input type="number" min="1" name="cantidad" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Fecha de Vencimiento (opcional)</label>
            <input type="date" name="fecha_vencimiento" class="form-control">
          </div>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-primary">Guardar Ingreso</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
