@extends('layouts.app')
@section('titulo', 'Facturación')

@section('content')
<div class="mb-5">
    <h2 class="text-2xl font-bold text-slate-800">Facturación</h2>
    <p class="text-sm text-slate-500">Historial de tickets y facturas generados por el sistema</p>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Total Documentos</p><p class="text-3xl font-bold text-slate-800">{{ $totalDocumentos }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center"><i data-lucide="file-text" class="w-6 h-6 text-blue-500"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Total Facturado</p><p class="text-3xl font-bold text-vortex-green2">${{ number_format($totalFacturado, 2) }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center"><i data-lucide="dollar-sign" class="w-6 h-6 text-vortex-green2"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Pendientes</p><p class="text-3xl font-bold text-slate-800">{{ $pendientes }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center"><i data-lucide="clock" class="w-6 h-6 text-amber-500"></i></div>
    </div>
</div>

{{-- Buscador --}}
<form method="GET" class="mb-5">
    <div class="relative">
        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por número de documento..."
               class="w-full bg-white border border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
    </div>
</form>

{{-- Tabla --}}
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-700">Historial de Facturación ({{ $ventas->count() }})</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Número</th>
                    <th class="px-4 py-3 font-medium">Fecha</th>
                    <th class="px-4 py-3 font-medium">Cliente</th>
                    <th class="px-4 py-3 font-medium text-center">Items</th>
                    <th class="px-4 py-3 font-medium text-right">Subtotal</th>
                    <th class="px-4 py-3 font-medium text-right">IVA</th>
                    <th class="px-4 py-3 font-medium text-right">Total</th>
                    <th class="px-4 py-3 font-medium">Pago</th>
                    <th class="px-4 py-3 font-medium text-center">Estado</th>
                    <th class="px-4 py-3 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($ventas as $v)
                @php $subtotal = $v->total - $v->impuesto; @endphp
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs font-semibold text-slate-700">N° {{ $v->factura->numero_factura ?? $v->id_venta }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-slate-500">Consumidor Final</td>
                    <td class="px-4 py-3 text-center text-slate-600">{{ $v->detalles->sum('cantidad') }}</td>
                    <td class="px-4 py-3 text-right text-slate-500">${{ number_format($subtotal, 2) }}</td>
                    <td class="px-4 py-3 text-right text-slate-500">${{ number_format($v->impuesto, 2) }}</td>
                    <td class="px-4 py-3 text-right font-semibold text-slate-800">${{ number_format($v->total, 2) }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $v->factura->metodo_pago ?? '—' }}</td>
                    <td class="px-4 py-3 text-center"><span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Pagado</span></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1.5">
                            <a title="Ver ticket" target="_blank" href="{{ route('pos.comprobante', $v->id_venta) }}?tipo=Ticket" class="p-1.5 rounded-md text-slate-500 hover:bg-slate-100"><i data-lucide="receipt" class="w-4 h-4"></i></a>
                            <a title="Ver factura" target="_blank" href="{{ route('pos.comprobante', $v->id_venta) }}?tipo=Factura" class="p-1.5 rounded-md text-blue-500 hover:bg-blue-50"><i data-lucide="file-text" class="w-4 h-4"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="px-5 py-10 text-center text-slate-400">No hay documentos todavía. Realiza ventas en el POS para verlas aquí.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
