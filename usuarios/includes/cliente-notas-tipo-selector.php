<style>
.cliente-notas-tipo {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.cliente-notas-tipo-opt {
  flex: 1;
  min-width: 8rem;
  margin: 0;
  cursor: pointer;
}

.cliente-notas-tipo-opt input {
  position: absolute;
  opacity: 0;
  pointer-events: none;
}

.cliente-notas-tipo-opt span {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  padding: 0.55rem 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  background: #fff;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem;
  font-weight: 600;
  color: #475569;
  transition: border-color 0.15s, background 0.15s, color 0.15s;
}

.cliente-notas-tipo-opt input:checked + span {
  border-color: #7c3aed;
  background: #f5f3ff;
  color: #6d28d9;
  box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
}

.cliente-notas-tipo-opt:hover span {
  border-color: #a78bfa;
}

.cliente-notas-panel[hidden] {
  display: none !important;
}
</style>

<div class="cliente-notas-tipo js-notas-tipo-selector" role="radiogroup" aria-label="Tipo de nota a registrar">
  <label class="cliente-notas-tipo-opt">
    <input type="radio" name="tipoNota" value="texto" class="js-notas-tipo-radio" checked>
    <span><i class="icon-pencil" aria-hidden="true"></i> Nota escrita</span>
  </label>
  <label class="cliente-notas-tipo-opt">
    <input type="radio" name="tipoNota" value="voz" class="js-notas-tipo-radio">
    <span><i class="icon-volume-up" aria-hidden="true"></i> Nota de voz</span>
  </label>
</div>
