<?php if ($rol === 'admin'): ?>
<div class="modal fade" id="modalVerIngresos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title">Ingresos de Stock</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Producto:</strong> <span id="nombreProductoIngresos"></span></p>
        <div class="table-responsive">
          <table class="table table-dark table-bordered table-hover text-white">
            <thead>
              <tr>
                <th>Boleta</th>
                <th>Fecha Ingreso</th>
                <th>Cantidad</th>
                <th>Precio Costo</th>
                <th>Proveedor</th>
                <th>Vencimiento</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody id="tablaIngresosBody">
              <tr><td colspan="7" class="text-center text-warning">Cargando...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
