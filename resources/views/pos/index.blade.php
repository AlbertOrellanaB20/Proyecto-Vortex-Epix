@extends('layouts.app')
@section('titulo', 'Punto de Venta (POS)')

@section('head')
<style>
    /* Animación del cajón de dinero */
    .cajon { transition: transform .5s cubic-bezier(.34,1.56,.64,1); }
    .cajon-abierto { transform: translateY(38px); }
    @keyframes entrar { from { opacity:0; transform: translateX(-12px);} to {opacity:1; transform:none;} }
    .item-nuevo { animation: entrar .25s ease; }
</style>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Zona de escaneo (caja) --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <label class="block text-sm font-medium text-slate-600 mb-2 flex items-center gap-2">
                <i data-lucide="scan-barcode" class="w-5 h-5 text-vortex-green"></i> Escanea o escribe el código del producto
            </label>
            <input id="inputCodigo" type="text" autocomplete="off" autofocus
                   placeholder="Acerca el lector o escribe el código y presiona Enter..."
                   class="w-full text-lg border-2 border-vortex-green/40 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-vortex-green/50 font-mono">
            <p id="msgScan" class="text-sm mt-2 h-5"></p>
        </div>

        {{-- Lista de productos escaneados --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-700 flex items-center gap-2"><i data-lucide="shopping-cart" class="w-5 h-5"></i> Productos en la venta</h3>
                <span id="contadorItems" class="text-xs text-slate-400">0 productos</span>
            </div>
            <div id="listaItems" class="divide-y divide-slate-100 max-h-[420px] overflow-y-auto">
                <div id="vacio" class="px-5 py-16 text-center text-slate-400">
                    <i data-lucide="package-search" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                    <p class="text-sm">Aún no has escaneado productos.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Panel de cobro --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 h-fit lg:sticky lg:top-20">
        <h3 class="font-semibold text-slate-700 mb-4 flex items-center gap-2"><i data-lucide="receipt" class="w-5 h-5"></i> Resumen de Compra</h3>

        <div class="space-y-2 text-sm border-b border-slate-100 pb-3 mb-3">
            <div class="flex justify-between text-slate-500"><span>Subtotal</span><span id="t_subtotal">$0.00</span></div>
            <div class="flex justify-between text-slate-500"><span>IVA (13%)</span><span id="t_iva">$0.00</span></div>
            <div class="flex justify-between text-lg font-bold text-slate-800"><span>Total</span><span id="t_total" class="text-vortex-green2">$0.00</span></div>
        </div>

        {{-- Método de pago --}}
        <p class="text-xs font-medium text-slate-600 mb-2">Método de Pago</p>
        <div class="grid grid-cols-2 gap-2 mb-3">
            <label class="flex items-center gap-2 border rounded-lg px-3 py-2 text-sm cursor-pointer has-[:checked]:border-vortex-green has-[:checked]:bg-green-50">
                <input type="radio" name="metodo" value="Efectivo" checked class="accent-vortex-green" onchange="cambioMetodo()"> <i data-lucide="banknote" class="w-4 h-4"></i> Efectivo
            </label>
            <label class="flex items-center gap-2 border rounded-lg px-3 py-2 text-sm cursor-pointer has-[:checked]:border-vortex-green has-[:checked]:bg-green-50">
                <input type="radio" name="metodo" value="Tarjeta" class="accent-vortex-green" onchange="cambioMetodo()"> <i data-lucide="credit-card" class="w-4 h-4"></i> Tarjeta
            </label>
        </div>

        {{-- Efectivo / vuelto --}}
        <div id="zonaEfectivo">
            <label class="block text-xs font-medium text-slate-600 mb-1">Efectivo recibido</label>
            <input id="inputEfectivo" type="number" step="0.01" min="0" value="0" oninput="calcularVuelto()"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
            <div class="flex justify-between text-sm bg-slate-50 rounded-lg px-3 py-2 mb-3">
                <span class="text-slate-500">Vuelto</span><span id="t_vuelto" class="font-semibold text-slate-700">$0.00</span>
            </div>
        </div>

        {{-- Tipo de documento --}}
        <p class="text-xs font-medium text-slate-600 mb-2">Tipo de Documento</p>
        <div class="grid grid-cols-2 gap-2 mb-4">
            <label class="flex items-center gap-2 border rounded-lg px-3 py-2 text-sm cursor-pointer has-[:checked]:border-vortex-green has-[:checked]:bg-green-50">
                <input type="radio" name="tipo" value="Ticket" checked class="accent-vortex-green"> Ticket
            </label>
            <label class="flex items-center gap-2 border rounded-lg px-3 py-2 text-sm cursor-pointer has-[:checked]:border-vortex-green has-[:checked]:bg-green-50">
                <input type="radio" name="tipo" value="Factura" class="accent-vortex-green"> Factura
            </label>
        </div>

        <div class="flex gap-2">
            <button onclick="cancelarVenta()" class="flex-1 flex items-center justify-center gap-1 border border-slate-200 text-slate-600 rounded-lg py-2.5 text-sm hover:bg-slate-50">
                <i data-lucide="x" class="w-4 h-4"></i> Cancelar
            </button>
            <button id="btnCobrar" onclick="cobrar()" class="flex-1 flex items-center justify-center gap-1 bg-vortex-green hover:bg-vortex-green2 text-white rounded-lg py-2.5 text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                <i data-lucide="check" class="w-4 h-4"></i> Cobrar
            </button>
        </div>
    </div>
</div>

{{-- Modal de éxito con cajón de dinero --}}
<div id="modalExito" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl text-center p-6">
        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
            <i data-lucide="check-circle" class="w-9 h-9 text-vortex-green2"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-800">¡Venta realizada!</h3>
        <p class="text-sm text-slate-500 mb-4">Documento N° <span id="x_numero" class="font-semibold"></span></p>

        {{-- Cajón animado --}}
        <div class="relative h-24 mx-auto mb-4" style="width:140px">
            <div class="absolute inset-x-0 top-0 h-16 bg-slate-700 rounded-t-lg"></div>
            <div id="cajon" class="cajon absolute inset-x-2 top-9 h-12 bg-slate-500 rounded-b-lg border-t-4 border-slate-400 flex items-center justify-center">
                <i data-lucide="banknote" class="w-7 h-7 text-green-300"></i>
            </div>
        </div>

        <div class="bg-slate-50 rounded-lg p-3 mb-4 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">Total cobrado</span><span id="x_total" class="font-semibold"></span></div>
            <div id="x_vuelto_row" class="flex justify-between mt-1"><span class="text-slate-500">Vuelto</span><span id="x_vuelto" class="font-bold text-vortex-green2 text-lg"></span></div>
        </div>

        <div class="flex gap-2">
            <a id="x_imprimir" href="#" target="_blank" class="flex-1 border border-slate-200 text-slate-600 rounded-lg py-2.5 text-sm hover:bg-slate-50 flex items-center justify-center gap-1">
                <i data-lucide="printer" class="w-4 h-4"></i> Imprimir
            </a>
            <button onclick="nuevaVenta()" class="flex-1 bg-vortex-green hover:bg-vortex-green2 text-white rounded-lg py-2.5 text-sm font-medium">Nueva venta</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const URL_BUSCAR = "{{ route('pos.buscar') }}";
    const URL_COBRAR = "{{ route('pos.cobrar') }}";
    const URL_COMPROB = "{{ url('/pos/comprobante') }}";
    let carrito = []; // {id, nombre, precio, stock, imagen, cantidad}

    const input = document.getElementById('inputCodigo');
    input.addEventListener('keydown', async (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const codigo = input.value.trim();
            input.value = '';
            if (codigo) await buscarProducto(codigo);
        }
    });

    async function buscarProducto(codigo) {
        const msg = document.getElementById('msgScan');
        try {
            const r = await fetch(URL_BUSCAR + '?codigo=' + encodeURIComponent(codigo));
            const data = await r.json();
            if (!data.ok) { msg.textContent = '⚠️ ' + data.mensaje; msg.className = 'text-sm mt-2 h-5 text-red-500'; return; }
            agregarAlCarrito(data.producto);
            msg.textContent = '✓ ' + data.producto.nombre + ' agregado'; msg.className = 'text-sm mt-2 h-5 text-vortex-green2';
        } catch { msg.textContent = 'Error de conexión'; msg.className = 'text-sm mt-2 h-5 text-red-500'; }
        input.focus();
    }

    function agregarAlCarrito(p) {
        const existe = carrito.find(i => i.id === p.id);
        if (existe) {
            if (existe.cantidad + 1 > p.stock) { alerta('No hay más stock de ' + p.nombre); return; }
            existe.cantidad++;
        } else {
            carrito.push({ ...p, cantidad: 1 });
        }
        render();
    }

    function cambiarCantidad(id, delta) {
        const item = carrito.find(i => i.id === id);
        if (!item) return;
        const nueva = item.cantidad + delta;
        if (nueva <= 0) { carrito = carrito.filter(i => i.id !== id); }
        else if (nueva > item.stock) { alerta('Stock máximo: ' + item.stock); return; }
        else { item.cantidad = nueva; }
        render();
    }
    function quitar(id) { carrito = carrito.filter(i => i.id !== id); render(); }

    function render() {
        const lista = document.getElementById('listaItems');
        const vacio = document.getElementById('vacio');
        document.getElementById('contadorItems').textContent = carrito.reduce((a,i)=>a+i.cantidad,0) + ' productos';

        if (carrito.length === 0) { vacio.style.display = 'block'; lista.querySelectorAll('.fila').forEach(e=>e.remove()); recalcular(); return; }
        vacio.style.display = 'none';
        lista.querySelectorAll('.fila').forEach(e=>e.remove());

        carrito.forEach(i => {
            const sub = (i.precio * i.cantidad).toFixed(2);
            const img = i.imagen
                ? `<img src="/img/productos/${i.imagen}" class="w-12 h-12 rounded object-cover bg-slate-100" onerror="this.outerHTML='<div class=\\'w-12 h-12 rounded bg-slate-100 flex items-center justify-center\\'><i data-lucide=\\'package\\' class=\\'w-5 h-5 text-slate-400\\'></i></div>'">`
                : `<div class="w-12 h-12 rounded bg-slate-100 flex items-center justify-center"><i data-lucide="package" class="w-5 h-5 text-slate-400"></i></div>`;
            const div = document.createElement('div');
            div.className = 'fila item-nuevo px-5 py-3 flex items-center gap-3';
            div.innerHTML = `
                ${img}
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-slate-700 truncate">${i.nombre}</p>
                    <p class="text-xs text-slate-400">$${i.precio.toFixed(2)} c/u</p>
                </div>
                <div class="flex items-center gap-1">
                    <button onclick="cambiarCantidad(${i.id},-1)" class="w-7 h-7 rounded-md border border-slate-200 text-slate-500 hover:bg-slate-50">−</button>
                    <span class="w-7 text-center text-sm font-medium">${i.cantidad}</span>
                    <button onclick="cambiarCantidad(${i.id},1)" class="w-7 h-7 rounded-md border border-slate-200 text-slate-500 hover:bg-slate-50">+</button>
                </div>
                <span class="w-16 text-right font-semibold text-slate-700">$${sub}</span>
                <button onclick="quitar(${i.id})" class="text-red-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>`;
            lista.appendChild(div);
        });
        recalcular();
        lucide.createIcons();
    }

    // Redondeo a 2 decimales seguro (evita errores de centavos)
    function r2(n) { return Math.round((n + Number.EPSILON) * 100) / 100; }
    // Cálculo ÚNICO de totales, usado en todos lados (display, vuelto y cobro)
    function totales() {
        const subtotal = r2(carrito.reduce((a, i) => a + i.precio * i.cantidad, 0));
        const iva = r2(subtotal * 0.13);
        const total = r2(subtotal + iva);
        return { subtotal, iva, total };
    }
    function recalcular() {
        const { subtotal, iva, total } = totales();
        document.getElementById('t_subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('t_iva').textContent = '$' + iva.toFixed(2);
        document.getElementById('t_total').textContent = '$' + total.toFixed(2);
        document.getElementById('btnCobrar').disabled = carrito.length === 0;
        calcularVuelto();
    }

    function metodoActual() { return document.querySelector('input[name="metodo"]:checked').value; }
    function cambioMetodo() {
        document.getElementById('zonaEfectivo').style.display = metodoActual() === 'Efectivo' ? 'block' : 'none';
    }
    function calcularVuelto() {
        const { total } = totales();
        const efectivo = parseFloat(document.getElementById('inputEfectivo').value) || 0;
        const vuelto = r2(efectivo - total);
        const el = document.getElementById('t_vuelto');
        el.textContent = '$' + (vuelto >= 0 ? vuelto.toFixed(2) : '0.00');
        el.className = 'font-semibold ' + (vuelto < 0 && efectivo > 0 ? 'text-red-500' : 'text-slate-700');
    }

    function cancelarVenta() {
        if (carrito.length === 0) return;
        if (confirm('¿Cancelar la venta en progreso?')) { carrito = []; render(); input.focus(); }
    }
    function alerta(t) { const m = document.getElementById('msgScan'); m.textContent = '⚠️ ' + t; m.className = 'text-sm mt-2 h-5 text-red-500'; }

    async function cobrar() {
        if (carrito.length === 0) return;
        const metodo = metodoActual();
        const { total } = totales();
        const efectivo = parseFloat(document.getElementById('inputEfectivo').value) || 0;
        if (metodo === 'Efectivo' && efectivo < total) { alerta('El efectivo no cubre el total ($' + total.toFixed(2) + ')'); return; }
        if (!confirm('¿Confirmar el cobro por $' + total.toFixed(2) + '?')) return;

        const btn = document.getElementById('btnCobrar');
        btn.disabled = true; btn.innerHTML = 'Procesando...';
        try {
            const r = await fetch(URL_COBRAR, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({
                    items: carrito.map(i => ({ id: i.id, cantidad: i.cantidad })),
                    metodo_pago: metodo,
                    tipo_documento: document.querySelector('input[name="tipo"]:checked').value,
                    efectivo: efectivo
                })
            });
            const data = await r.json();
            if (!data.ok) { alerta(data.mensaje || 'No se pudo cobrar'); btn.disabled=false; btn.innerHTML='<i data-lucide="check" class="w-4 h-4"></i> Cobrar'; lucide.createIcons(); return; }
            mostrarExito(data);
        } catch { alerta('Error de conexión al cobrar'); btn.disabled=false; btn.innerHTML='<i data-lucide="check" class="w-4 h-4"></i> Cobrar'; lucide.createIcons(); }
    }

    function mostrarExito(d) {
        document.getElementById('x_numero').textContent = d.numero_factura;
        document.getElementById('x_total').textContent = '$' + Number(d.total).toFixed(2);
        document.getElementById('x_vuelto').textContent = '$' + Number(d.vuelto).toFixed(2);
        document.getElementById('x_vuelto_row').style.display = (metodoActual() === 'Efectivo') ? 'flex' : 'none';
        document.getElementById('x_imprimir').href = URL_COMPROB + '/' + d.id_venta + '?tipo=' + encodeURIComponent(d.tipo);
        const modal = document.getElementById('modalExito');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        lucide.createIcons();
        setTimeout(() => document.getElementById('cajon').classList.add('cajon-abierto'), 200);
    }
    function nuevaVenta() {
        document.getElementById('cajon').classList.remove('cajon-abierto');
        document.getElementById('modalExito').classList.add('hidden');
        document.getElementById('modalExito').classList.remove('flex');
        carrito = []; document.getElementById('inputEfectivo').value = 0; render(); input.focus();
    }

    render();
</script>
@endsection
