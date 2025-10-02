<h2>Detalle de Historial - Pedido <?= $historial['id_pedido'] ?></h2>
<p><strong>Detalle de ruta:</strong></p>
<pre><?= htmlspecialchars(json_encode(json_decode($historial['detalles_ruta']), JSON_PRETTY_PRINT)) ?></pre>

<p><strong>Evaluación Cliente:</strong> <?= htmlspecialchars($historial['evaluacion_cliente'] ?? 'Sin evaluación') ?></p>
<p><strong>Evaluación Conductor:</strong> <?= htmlspecialchars($historial['evaluacion_conductor'] ?? 'Sin evaluación') ?></p>

<a href="?controller=Historial&action=index">Volver al historial</a>
