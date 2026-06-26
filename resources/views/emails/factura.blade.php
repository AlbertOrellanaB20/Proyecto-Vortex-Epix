<div style="font-family: Arial, Helvetica, sans-serif; color:#334155; max-width:520px;">
    <div style="background:#fef3c7; border:1px solid #fde68a; color:#92400e; border-radius:8px; padding:12px 14px; font-size:13px; margin-bottom:16px;">
        ⚠️ <strong>Aviso:</strong> Este correo es parte de un <strong>proyecto estudiantil</strong> del Instituto Nacional de Acajutla (Módulo 3.1). <strong>No es una factura real ni una transacción comercial verdadera</strong>; se envía únicamente con fines académicos de demostración.
    </div>
    <h2 style="color:#16a34a; margin-bottom:4px;">¡Gracias por su compra!</h2>
    <p style="margin-top:0;">Le adjuntamos su factura <strong>N.&deg; {{ $numero }}</strong> en formato PDF.</p>
    <p>Total: <strong>${{ number_format($venta->total ?? 0, 2) }}</strong></p>
    <hr style="border:none; border-top:1px solid #e2e8f0; margin:16px 0;">
    <p style="font-size:12px; color:#94a3b8;">Supermercado &middot; Sistema de Punto de Venta</p>
</div>
