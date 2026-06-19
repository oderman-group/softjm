(function (global) {
  'use strict';

  var MAX_SEGUNDOS = 60;
  var ESTADO_IDLE = 'idle';
  var ESTADO_RECORDING = 'recording';
  var ESTADO_PAUSED = 'paused';
  var ESTADO_PREVIEW = 'preview';

  function formatearTiempo(segundos) {
    var s = Math.max(0, Math.min(MAX_SEGUNDOS, parseInt(segundos, 10) || 0));
    var m = Math.floor(s / 60);
    var r = s % 60;
    return m + ':' + String(r).padStart(2, '0');
  }

  function escaparHtml(texto) {
    var div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
  }

  function nl2br(texto) {
    return escaparHtml(texto).replace(/\n/g, '<br>');
  }

  function renderHistorialItem(nota) {
    var meta =
      '<div class="cliente-nota-meta">' +
        '<span><strong>' + escaparHtml(nota.usuario) + '</strong></span>' +
        '<span>' + escaparHtml(nota.fecha) + '</span>' +
      '</div>';

    if (nota.audio_url) {
      var dur = nota.duracion_segundos ? formatearTiempo(nota.duracion_segundos) : '';
      return (
        '<article class="cliente-nota-item cliente-nota-item--voz">' +
          meta +
          '<div class="cliente-nota-voz-badge"><i class="icon-volume-up"></i> Nota de voz' +
            (dur ? ' · ' + dur : '') +
          '</div>' +
          '<audio class="cliente-nota-audio" controls preload="none" src="' + escaparHtml(nota.audio_url) + '"></audio>' +
        '</article>'
      );
    }

    return (
      '<article class="cliente-nota-item">' +
        meta +
        '<div class="cliente-nota-texto">' + nl2br(nota.nota || '') + '</div>' +
      '</article>'
    );
  }

  function ClienteNotasVoz(options) {
    this.root = document.getElementById(options.rootId);
    if (!this.root) return;

    this.getClienteId = options.getClienteId;
    this.onSaved = options.onSaved || function () {};
    this.onError = options.onError || function () {};
    this.onSaving = options.onSaving || function () {};
    this.saveUrl = options.saveUrl || 'ajax/ajax-clientes-nota-interna-voz-guardar.php';

    this.btnGrabar = this.root.querySelector('.js-voz-grabar');
    this.btnPausar = this.root.querySelector('.js-voz-pausar');
    this.btnContinuar = this.root.querySelector('.js-voz-continuar');
    this.btnFinalizar = this.root.querySelector('.js-voz-finalizar');
    this.btnGuardar = this.root.querySelector('.js-voz-guardar');
    this.btnRegrabar = this.root.querySelector('.js-voz-regrabar');
    this.btnDescartar = this.root.querySelector('.js-voz-descartar');
    this.timerEl = this.root.querySelector('.js-voz-timer');
    this.preview = this.root.querySelector('.js-voz-preview');
    this.previewPanel = this.root.querySelector('.js-voz-preview-panel');
    this.grabacionPanel = this.root.querySelector('.js-voz-grabacion-panel');
    this.indicador = this.root.querySelector('.js-voz-indicador');
    this.estadoEl = this.root.querySelector('.js-voz-estado');
    this.noSoportado = this.root.querySelector('.js-voz-no-soporte');

    this.mediaRecorder = null;
    this.stream = null;
    this.chunks = [];
    this.blob = null;
    this.blobUrl = null;
    this.mimeType = 'audio/webm';
    this.duracion = 0;
    this.elapsedMs = 0;
    this.tickStart = 0;
    this.timerInterval = null;
    this.estadoGrabacion = ESTADO_IDLE;
    this.guardando = false;
    this.soportaPausa = typeof MediaRecorder !== 'undefined' &&
      typeof MediaRecorder.prototype.pause === 'function';

    this.init();
  }

  ClienteNotasVoz.prototype.tieneVozPendiente = function () {
    return this.estadoGrabacion === ESTADO_PREVIEW && !!this.blob;
  };

  ClienteNotasVoz.prototype.init = function () {
    if (!navigator.mediaDevices || typeof MediaRecorder === 'undefined') {
      if (this.noSoportado) this.noSoportado.style.display = 'block';
      if (this.btnGrabar) this.btnGrabar.disabled = true;
      return;
    }

    if (this.btnGrabar) this.btnGrabar.addEventListener('click', this.iniciarGrabacion.bind(this));
    if (this.btnPausar) this.btnPausar.addEventListener('click', this.pausarGrabacion.bind(this));
    if (this.btnContinuar) this.btnContinuar.addEventListener('click', this.continuarGrabacion.bind(this));
    if (this.btnFinalizar) this.btnFinalizar.addEventListener('click', this.finalizarGrabacion.bind(this));
    if (this.btnGuardar) this.btnGuardar.addEventListener('click', this.guardar.bind(this));
    if (this.btnRegrabar) this.btnRegrabar.addEventListener('click', this.regrabar.bind(this));
    if (this.btnDescartar) this.btnDescartar.addEventListener('click', this.descartar.bind(this));

    this.actualizarUi();
  };

  ClienteNotasVoz.prototype.setEstado = function (texto) {
    if (this.estadoEl) this.estadoEl.textContent = texto || '';
  };

  ClienteNotasVoz.prototype.getDuracionSegundos = function () {
    var ms = this.elapsedMs;
    if (this.estadoGrabacion === ESTADO_RECORDING && this.tickStart) {
      ms += Date.now() - this.tickStart;
    }
    return Math.min(MAX_SEGUNDOS, Math.max(0, Math.floor(ms / 1000)));
  };

  ClienteNotasVoz.prototype.actualizarTimer = function () {
    this.duracion = this.getDuracionSegundos();
    if (this.timerEl) {
      this.timerEl.textContent = formatearTiempo(this.duracion) + ' / ' + formatearTiempo(MAX_SEGUNDOS);
    }
  };

  ClienteNotasVoz.prototype.liberarBlobUrl = function () {
    if (this.blobUrl) {
      URL.revokeObjectURL(this.blobUrl);
      this.blobUrl = null;
    }
  };

  ClienteNotasVoz.prototype.liberarStream = function () {
    if (this.stream) {
      this.stream.getTracks().forEach(function (track) { track.stop(); });
      this.stream = null;
    }
  };

  ClienteNotasVoz.prototype.detenerTimer = function () {
    clearInterval(this.timerInterval);
    this.timerInterval = null;
  };

  ClienteNotasVoz.prototype.iniciarTimer = function () {
    var self = this;
    this.detenerTimer();
    this.timerInterval = setInterval(function () {
      self.actualizarTimer();
      if (self.getDuracionSegundos() >= MAX_SEGUNDOS) {
        self.finalizarGrabacion(true);
        self.setEstado('Se alcanzó el máximo de 1 minuto. Escuche y guarde o regrabe.');
      }
    }, 200);
  };

  ClienteNotasVoz.prototype.mostrarBoton = function (btn, visible) {
    if (!btn) return;
    btn.style.display = visible ? 'inline-flex' : 'none';
  };

  ClienteNotasVoz.prototype.actualizarUi = function () {
    var estado = this.estadoGrabacion;
    var enGrabacion = estado === ESTADO_RECORDING || estado === ESTADO_PAUSED;
    var enPreview = estado === ESTADO_PREVIEW && !!this.blob;

    if (this.grabacionPanel) {
      this.grabacionPanel.style.display = enPreview ? 'none' : 'block';
    }
    if (this.previewPanel) {
      this.previewPanel.style.display = enPreview ? 'block' : 'none';
    }
    this.mostrarBoton(this.btnGrabar, estado === ESTADO_IDLE);
    this.mostrarBoton(this.btnPausar, estado === ESTADO_RECORDING && this.soportaPausa);
    this.mostrarBoton(this.btnContinuar, estado === ESTADO_PAUSED && this.soportaPausa);
    this.mostrarBoton(this.btnFinalizar, enGrabacion);
    if (this.indicador) {
      this.indicador.classList.toggle('is-active', estado === ESTADO_RECORDING);
      this.indicador.classList.toggle('is-paused', estado === ESTADO_PAUSED);
    }
    if (this.btnGuardar) {
      this.btnGuardar.disabled = this.guardando;
    }

    this.actualizarTimer();
  };

  ClienteNotasVoz.prototype.limpiarPreview = function () {
    this.blob = null;
    this.liberarBlobUrl();
    if (this.preview) {
      this.preview.removeAttribute('src');
      this.preview.load();
    }
  };

  ClienteNotasVoz.prototype.descartar = function () {
    if (this.estadoGrabacion === ESTADO_RECORDING || this.estadoGrabacion === ESTADO_PAUSED) {
      this.abortarGrabacion();
    }
    this.limpiarPreview();
    this.chunks = [];
    this.elapsedMs = 0;
    this.tickStart = 0;
    this.duracion = 0;
    this.estadoGrabacion = ESTADO_IDLE;
    this.detenerTimer();
    this.setEstado('');
    this.actualizarUi();
  };

  ClienteNotasVoz.prototype.regrabar = function () {
    this.descartar();
    this.iniciarGrabacion();
  };

  ClienteNotasVoz.prototype.reset = function () {
    this.guardando = false;
    if (this.btnGuardar) this.btnGuardar.disabled = false;
    this.descartar();
  };

  ClienteNotasVoz.prototype.abortarGrabacion = function () {
    if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
      this.mediaRecorder.onstop = null;
      this.mediaRecorder.stop();
    }
    this.detenerTimer();
    this.liberarStream();
    this.chunks = [];
    this.elapsedMs = 0;
    this.tickStart = 0;
    this.estadoGrabacion = ESTADO_IDLE;
  };

  ClienteNotasVoz.prototype.resolverMimeType = function () {
    if (MediaRecorder.isTypeSupported('audio/webm;codecs=opus')) {
      return 'audio/webm;codecs=opus';
    }
    if (MediaRecorder.isTypeSupported('audio/webm')) {
      return 'audio/webm';
    }
    if (MediaRecorder.isTypeSupported('audio/ogg;codecs=opus')) {
      return 'audio/ogg;codecs=opus';
    }
    return '';
  };

  ClienteNotasVoz.prototype.iniciarGrabacion = function () {
    var self = this;
    if (this.guardando) return;
    if (this.estadoGrabacion === ESTADO_RECORDING || this.estadoGrabacion === ESTADO_PAUSED) return;

    this.limpiarPreview();
    this.chunks = [];
    this.elapsedMs = 0;
    this.tickStart = 0;
    this.duracion = 0;

    navigator.mediaDevices.getUserMedia({ audio: true })
      .then(function (stream) {
        self.stream = stream;
        self.mimeType = self.resolverMimeType();
        var options = self.mimeType ? { mimeType: self.mimeType } : undefined;
        self.mediaRecorder = new MediaRecorder(stream, options);

        self.mediaRecorder.ondataavailable = function (e) {
          if (e.data && e.data.size > 0) self.chunks.push(e.data);
        };

        self.mediaRecorder.onstop = function () {
          self.detenerTimer();
          self.liberarStream();

          if (self.chunks.length) {
            self.blob = new Blob(self.chunks, { type: self.mimeType || 'audio/webm' });
            self.duracion = Math.max(1, self.getDuracionSegundos());
            self.liberarBlobUrl();
            self.blobUrl = URL.createObjectURL(self.blob);
            if (self.preview) {
              self.preview.src = self.blobUrl;
              self.preview.load();
              var playPromise = self.preview.play();
              if (playPromise && typeof playPromise.catch === 'function') {
                playPromise.catch(function () {});
              }
            }
            self.estadoGrabacion = ESTADO_PREVIEW;
            self.setEstado('Escuche la grabación y use «Guardar nota» abajo para registrarla.');
          } else {
            self.estadoGrabacion = ESTADO_IDLE;
            self.setEstado('');
          }

          self.actualizarUi();
        };

        self.mediaRecorder.start(250);
        self.estadoGrabacion = ESTADO_RECORDING;
        self.tickStart = Date.now();
        self.setEstado(self.soportaPausa
          ? 'Grabando... Puede pausar, continuar o finalizar para escuchar.'
          : 'Grabando... Pulse «Finalizar» para escuchar antes de guardar.');
        self.iniciarTimer();
        self.actualizarUi();
      })
      .catch(function () {
        self.onError('No se pudo acceder al micrófono. Verifique los permisos del navegador.');
      });
  };

  ClienteNotasVoz.prototype.pausarGrabacion = function () {
    if (this.estadoGrabacion !== ESTADO_RECORDING || !this.soportaPausa) return;
    if (!this.mediaRecorder || this.mediaRecorder.state !== 'recording') return;

    this.elapsedMs += Date.now() - this.tickStart;
    this.tickStart = 0;
    this.mediaRecorder.pause();
    this.estadoGrabacion = ESTADO_PAUSED;
    this.setEstado('Grabación pausada. Continúe, finalice para escuchar o descarte.');
    this.actualizarUi();
  };

  ClienteNotasVoz.prototype.continuarGrabacion = function () {
    if (this.estadoGrabacion !== ESTADO_PAUSED || !this.soportaPausa) return;
    if (!this.mediaRecorder || this.mediaRecorder.state !== 'paused') return;
    if (this.getDuracionSegundos() >= MAX_SEGUNDOS) {
      this.finalizarGrabacion(true);
      return;
    }

    this.mediaRecorder.resume();
    this.tickStart = Date.now();
    this.estadoGrabacion = ESTADO_RECORDING;
    this.setEstado('Grabando... Puede pausar, continuar o finalizar para escuchar.');
    this.actualizarUi();
  };

  ClienteNotasVoz.prototype.finalizarGrabacion = function (automatico) {
    if (this.estadoGrabacion !== ESTADO_RECORDING && this.estadoGrabacion !== ESTADO_PAUSED) return;

    if (this.estadoGrabacion === ESTADO_RECORDING && this.tickStart) {
      this.elapsedMs += Date.now() - this.tickStart;
      this.tickStart = 0;
    }

    if (this.getDuracionSegundos() < 1 && !automatico) {
      this.onError('La grabación es demasiado corta. Mantenga al menos 1 segundo.');
      return;
    }

    if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
      this.mediaRecorder.stop();
    } else {
      this.estadoGrabacion = ESTADO_IDLE;
      this.detenerTimer();
      this.liberarStream();
      this.actualizarUi();
    }
  };

  ClienteNotasVoz.prototype.guardar = function (clienteIdOverride) {
    var self = this;
    if (this.guardando || !this.blob || this.estadoGrabacion !== ESTADO_PREVIEW) {
      return Promise.resolve(false);
    }

    var clienteId = clienteIdOverride
      ? parseInt(clienteIdOverride, 10)
      : parseInt(this.getClienteId(), 10);
    if (!clienteId) {
      this.onError('Cliente no válido.');
      return Promise.resolve(false);
    }

    var duracion = Math.max(1, this.duracion || this.getDuracionSegundos());
    this.guardando = true;
    if (this.btnGuardar) this.btnGuardar.disabled = true;
    this.onSaving(true);
    this.setEstado('Guardando nota de voz...');

    var extension = (this.mimeType.indexOf('ogg') >= 0) ? 'ogg' : 'webm';
    var formData = new FormData();
    formData.append('cliente', String(clienteId));
    formData.append('duracion', String(duracion));
    formData.append('audio', this.blob, 'nota-voz.' + extension);

    return fetch(this.saveUrl, { method: 'POST', body: formData })
      .then(function (res) {
        return res.text().then(function (text) {
          try {
            return JSON.parse(text);
          } catch (e) {
            throw new Error('Respuesta inválida del servidor.');
          }
        });
      })
      .then(function (data) {
        self.guardando = false;
        self.onSaving(false);
        if (self.btnGuardar) self.btnGuardar.disabled = false;

        if (data.success) {
          self.reset();
          self.onSaved(data);
          return true;
        }

        self.setEstado('Escuche la grabación y use «Guardar» abajo para registrarla.');
        self.onError(data.message || 'No se pudo guardar la nota de voz.');
        return false;
      })
      .catch(function (err) {
        self.guardando = false;
        self.onSaving(false);
        if (self.btnGuardar) self.btnGuardar.disabled = false;
        self.setEstado('Escuche la grabación y use «Guardar» abajo para registrarla.');
        self.onError(err.message || 'Error de conexión al guardar la nota de voz.');
        return false;
      });
  };

  function bindNotasTipoSelector(options) {
    var root = options.root;
    if (!root) return null;

    var panelTexto = root.querySelector('.js-notas-panel-texto');
    var panelVoz = root.querySelector('.js-notas-panel-voz');
    var radios = root.querySelectorAll('.js-notas-tipo-radio');
    var widgetVoz = options.widgetVoz || null;
    var ultimoModo = 'texto';

    function getModo() {
      var checked = root.querySelector('.js-notas-tipo-radio:checked');
      return checked ? checked.value : 'texto';
    }

    function aplicarModo(modo) {
      ultimoModo = modo;
      if (panelTexto) panelTexto.hidden = modo !== 'texto';
      if (panelVoz) panelVoz.hidden = modo !== 'voz';
      if (typeof options.onModoChange === 'function') {
        options.onModoChange(modo);
      }
    }

    function hayGrabacionActiva() {
      return widgetVoz &&
        (widgetVoz.estadoGrabacion === 'recording' || widgetVoz.estadoGrabacion === 'paused');
    }

    function cambiarModo(modo) {
      if (modo === ultimoModo) return;

      if (hayGrabacionActiva()) {
        if (!window.confirm('Hay una grabación en curso. ¿Cambiar de tipo y descartarla?')) {
          var previo = root.querySelector('.js-notas-tipo-radio[value="' + ultimoModo + '"]');
          if (previo) previo.checked = true;
          return;
        }
        widgetVoz.reset();
      } else if (widgetVoz && typeof widgetVoz.tieneVozPendiente === 'function' && widgetVoz.tieneVozPendiente()) {
        widgetVoz.descartar();
      }

      aplicarModo(modo);
    }

    radios.forEach(function (radio) {
      radio.addEventListener('change', function () {
        if (radio.checked) cambiarModo(radio.value);
      });
    });

    aplicarModo(getModo());

    return {
      getModo: getModo,
      resetModo: function () {
        var radioTexto = root.querySelector('.js-notas-tipo-radio[value="texto"]');
        if (radioTexto) radioTexto.checked = true;
        aplicarModo('texto');
      }
    };
  }

  global.ClienteNotasVoz = ClienteNotasVoz;
  global.ClienteNotasVozUtil = {
    renderHistorialItem: renderHistorialItem,
    formatearTiempo: formatearTiempo,
    bindNotasTipoSelector: bindNotasTipoSelector
  };
})(window);
