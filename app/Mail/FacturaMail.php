<?php

namespace App\Mail;

use App\Models\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacturaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Venta $venta;

    public function __construct(Venta $venta)
    {
        // Carga las relaciones que necesita el PDF (detalles, factura, empleado)
        $this->venta = $venta->loadMissing(['detalles.producto', 'factura.cliente', 'empleado']);
    }

    public function build()
    {
        $numero = $this->venta->factura->numero_factura ?? $this->venta->id_venta;

        $correo = $this->subject("Factura N° {$numero} - Supermercado")
                       ->view('emails.factura', ['numero' => $numero, 'venta' => $this->venta]);

        // Adjunta la FACTURA en PDF (tamaño carta/A4) usando DomPDF.
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $venta = $this->venta;
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pos.factura_pdf', compact('venta'))
                    ->setPaper('a4', 'portrait');
            $correo->attachData($pdf->output(), "factura-{$numero}.pdf", ['mime' => 'application/pdf']);
        }

        return $correo;
    }
}
