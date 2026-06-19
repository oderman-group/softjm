<?php
/** @var string $vozRootId Identificador único del bloque en la página */
$vozRootId = $vozRootId ?? 'clienteNotasVoz';
$vozEmbebido = !empty($vozEmbebido);
?>
<style>
.cliente-notas-voz {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px dashed #cbd5e1;
}

.cliente-notas-voz.cliente-notas-voz--embebido {
  margin-top: 0;
  padding-top: 0;
  border-top: none;
}

.cliente-notas-voz-label {
  display: block;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.75rem;
  font-weight: 600;
  color: #334155;
  margin-bottom: 0.35rem;
}

.cliente-notas-voz-label span {
  font-weight: 500;
  color: #64748b;
}

.cliente-notas-voz-hint {
  font-size: 0.75rem;
  color: #64748b;
  margin: 0 0 0.65rem 0;
  line-height: 1.45;
}

.cliente-notas-voz-controles {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 0.5rem 0.75rem;
  margin-bottom: 0.65rem;
}

.cliente-notas-voz-btn {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem;
  font-weight: 600;
  padding: 0.45rem 0.85rem;
  border-radius: 6px;
  border: 1px solid #cbd5e1;
  background: #fff;
  color: #334155;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}

.cliente-notas-voz-btn:hover:not(:disabled) {
  background: #f8fafc;
}

.cliente-notas-voz-btn:disabled {
  opacity: 0.55;
  cursor: not-allowed;
}

.cliente-notas-voz-btn--grabar {
  border-color: #7c3aed;
  color: #6d28d9;
}

.cliente-notas-voz-btn--pausar {
  border-color: #d97706;
  color: #b45309;
}

.cliente-notas-voz-btn--continuar {
  border-color: #059669;
  color: #047857;
}

.cliente-notas-voz-btn--finalizar {
  border-color: #dc2626;
  color: #b91c1c;
}

.cliente-notas-voz-btn--regrabar {
  border-color: #7c3aed;
  color: #6d28d9;
}

.cliente-notas-voz-btn--regrabar:hover:not(:disabled) {
  background: #f5f3ff;
  color: #5b21b6;
  border-color: #7c3aed;
}

.cliente-notas-voz-timer-wrap {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  min-width: 6.5rem;
}

.cliente-notas-voz-indicador {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #cbd5e1;
  flex-shrink: 0;
  display: none;
}

.cliente-notas-voz-indicador.is-active {
  display: inline-block;
  background: #dc2626;
  animation: cliente-notas-voz-pulse 1s ease-in-out infinite;
}

.cliente-notas-voz-indicador.is-paused {
  display: inline-block;
  background: #d97706;
  animation: none;
}

@keyframes cliente-notas-voz-pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.55; transform: scale(0.92); }
}

.cliente-notas-voz-timer {
  font-family: 'Plus Jakarta Sans', monospace;
  font-size: 0.8125rem;
  font-weight: 600;
  color: #475569;
}

.cliente-notas-voz-estado {
  font-size: 0.75rem;
  color: #64748b;
  margin: 0 0 0.5rem 0;
  min-height: 1rem;
  line-height: 1.45;
}

.cliente-notas-voz-preview-panel {
  display: none;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 0.875rem 1rem;
  margin-top: 0.35rem;
}

.cliente-notas-voz-preview-panel .cliente-notas-voz-preview-titulo {
  font-size: 0.8125rem;
  font-weight: 700;
  color: #334155;
  margin: 0 0 0.5rem 0;
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

.cliente-notas-voz-preview {
  width: 100%;
  margin: 0 0 0.75rem 0;
}

.cliente-notas-voz-no-soporte {
  display: none;
  font-size: 0.8125rem;
  color: #b45309;
  background: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 6px;
  padding: 0.65rem 0.75rem;
}

.cliente-nota-item--voz .cliente-nota-voz-badge {
  font-size: 0.8125rem;
  font-weight: 600;
  color: #6d28d9;
  margin-bottom: 0.5rem;
}

.cliente-nota-audio {
  width: 100%;
  max-width: 100%;
  height: 36px;
}
</style>

<div class="cliente-notas-voz<?= $vozEmbebido ? ' cliente-notas-voz--embebido' : '' ?>" id="<?= htmlspecialchars($vozRootId) ?>">
  <p class="cliente-notas-voz-hint">
    Grabe, pause cuando quiera, finalice para escuchar y use «Guardar nota» abajo para registrar (máx. 1 min).
  </p>
  <p class="cliente-notas-voz-no-soporte js-voz-no-soporte">
    Su navegador no permite grabar audio. Use Chrome, Edge o Firefox actualizado.
  </p>

    <p class="cliente-notas-voz-estado js-voz-estado"></p>

  <div class="cliente-notas-voz-grabacion-panel js-voz-grabacion-panel">
    <div class="cliente-notas-voz-controles">
      <button type="button" class="cliente-notas-voz-btn cliente-notas-voz-btn--grabar js-voz-grabar">
        <i class="icon-volume-up"></i> Grabar
      </button>
      <button type="button" class="cliente-notas-voz-btn cliente-notas-voz-btn--pausar js-voz-pausar">
        <i class="icon-pause"></i> Pausar
      </button>
      <button type="button" class="cliente-notas-voz-btn cliente-notas-voz-btn--continuar js-voz-continuar">
        <i class="icon-play"></i> Continuar
      </button>
      <button type="button" class="cliente-notas-voz-btn cliente-notas-voz-btn--finalizar js-voz-finalizar">
        <i class="icon-stop"></i> Finalizar
      </button>
      <span class="cliente-notas-voz-timer-wrap">
        <span class="cliente-notas-voz-indicador js-voz-indicador" aria-hidden="true"></span>
        <span class="cliente-notas-voz-timer js-voz-timer">0:00 / 1:00</span>
      </span>
    </div>
  </div>

  <div class="cliente-notas-voz-preview-panel js-voz-preview-panel">
    <p class="cliente-notas-voz-preview-titulo">
      <i class="icon-headphones"></i> Vista previa de la grabación
    </p>
    <audio class="cliente-notas-voz-preview js-voz-preview" controls preload="auto"></audio>
    <div class="cliente-notas-voz-controles">
      <button type="button" class="cliente-notas-voz-btn cliente-notas-voz-btn--regrabar js-voz-regrabar">
        <i class="icon-repeat"></i> Regrabar
      </button>
      <button type="button" class="cliente-notas-voz-btn js-voz-descartar">
        Descartar
      </button>
    </div>
  </div>
</div>
