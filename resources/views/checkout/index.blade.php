<x-app-layout>
    <div class="-mb-16 text-gray-700" x-data="checkoutData()">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="col-span-1">
                <div class="lg:max-w-[40rem] px-4 lg:pr-8 lg:pl-8 sm:pl-6 ml-auto py-12">
                    <!-- Dirección de Envío -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold">Dirección de Envío</h2>
                            @if ($defaultAddress)
                            <a href="{{ route('shipping.index') }}"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="mr-1.5 fas fa-edit text-xs"></i>
                                Gestionar direcciones
                            </a>
                            @endif
                        </div>

                        @if ($defaultAddress)
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <i class="mr-2 text-green-600 fas fa-map-marker-alt"></i>
                                        <p class="font-semibold text-gray-900">{{ $defaultAddress->address }}</p>
                                    </div>
                                    <p class="text-sm text-gray-600 ml-6">
                                        {{ $defaultAddress->parish->name ?? '' }},
                                        {{ $defaultAddress->canton->name ?? '' }},
                                        {{ $defaultAddress->province->name ?? '' }}
                                    </p>
                                    @if ($defaultAddress->reference)
                                    <p class="text-sm text-gray-500 ml-6">Ref: {{ $defaultAddress->reference }}
                                    </p>
                                    @endif
                                    @if ($defaultAddress->postal_code)
                                    <p class="text-sm text-gray-500 ml-6">CP: {{ $defaultAddress->postal_code }}
                                    </p>
                                    @endif
                                    @if ($defaultAddress->receiver_name && $defaultAddress->receiver_name !==
                                    auth()->user()->name)
                                    <p class="text-sm text-gray-600 ml-6 mt-1">
                                        <i class="mr-1 fas fa-user text-xs"></i>
                                        Receptor: {{ $defaultAddress->receiver_name }}
                                    </p>
                                    @endif
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">
                                    Por defecto
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="text-xs text-gray-500">
                                <i class="mr-1 fas fa-info-circle"></i>
                                Tu pedido será enviado a esta dirección. Si necesitas enviarlo a otra dirección,
                                puedes cambiar tu dirección predeterminada desde la gestión de direcciones.
                            </p>
                        </div>
                        @else
                        <div class="p-6 border border-red-200 rounded-lg bg-red-50">
                            <div class="text-center">
                                <i class="mb-3 text-3xl text-red-500 fas fa-map-marker-alt"></i>
                                <p class="mb-2 font-semibold text-red-800">No tienes una dirección de envío
                                    configurada</p>
                                <p class="mb-4 text-sm text-red-600">
                                    Para continuar con tu compra, necesitas configurar al menos una dirección de
                                    envío.
                                </p>
                                <a href="{{ route('shipping.index') }}"
                                    class="inline-flex items-center px-4 py-2 font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="mr-2 fas fa-plus"></i>
                                    Configurar dirección de envío
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

                    <h1 class="mb-4 text-2xl font-bold">Método de Pago</h1>
                    <div class="overflow-hidden border border-gray-200 rounded-lg shadow">
                        <ul class="divide-y divide-gray-200">
                            <!--
                            <li>
                                <label class="flex items-center p-4" for="">
                                    <input type="radio" name="payment" value="1" x-model="payment"
                                        class="mr-2">
                                    <span class="ml-2 text-lg font-semibold">Tarjeta de Crédito/Débito (PayPhone)</span>
                                    <img class="h-10 ml-auto" src="{{ asset('img/credit-card.png') }}"
                                        alt="Tarjeta de Crédito/Débito">
                                </label>
                                <div class="p-4 text-sm text-gray-600 bg-gray-200" x-show="payment == 1">
                                    <i class="fa-regular fa-credit-card"></i>
                                    <p class="mt-2">Aceptamos tarjetas de crédito y débito a través de PayPhone.
                                        Puedes pagar de forma segura en nuestra plataforma.</p>
                                </div>
                            </li>
                            -->
                            <li>
                                <label class="flex items-center p-4" for="">
                                    <input type="radio" name="payment" value="2" x-model="payment" class="mr-2">
                                    <span class="ml-2 text-lg font-semibold">Transferencia Bancaria</span>
                                    <img class="h-16 ml-auto " src="{{ asset('img/bank-transfer2.png') }}"
                                        alt="Transferencia Bancaria">
                                </label>
                                <div class="flex items-center justify-center p-4 bg-gray-200" x-cloak
                                    x-show="payment == 2">
                                    <div>
                                        <p class="text-sm text-gray-600">Realiza una transferencia a nuestra cuenta
                                            bancaria. Asegúrate de incluir tu número de pedido como referencia.</p>
                                        <p class="text-sm text-gray-600">Nombre del Banco: Banco Ejemplo</p>
                                        <p class="text-sm text-gray-600">Número de Cuenta: 123456789</p>
                                        <p class="text-sm text-gray-600">Código SWIFT: BANCOEX</p>
                                        <p class="text-sm text-gray-600">Referencia: #12345</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <label class="flex items-center p-4" for="">
                                    <input type="radio" name="payment" value="3" x-model="payment" class="mr-2">
                                    <span class="ml-2 text-lg font-semibold">Pago en Efectivo (Contra Entrega)</span>
                                    <img class="h-10 ml-auto" src="{{ asset('img/cash.png') }}" alt="Pago en Efectivo">
                                </label>
                                <div class="p-4 text-sm text-gray-600 bg-gray-200" x-show="payment == 3">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    <p class="mt-2">Paga en efectivo cuando recibas tu pedido en la dirección
                                        indicada.</p>
                                </div>
                            </li>
                            <li>
                                <label class="flex items-center p-4" for="">
                                    <input type="radio" name="payment" value="4" x-model="payment" class="mr-2">
                                    <span class="ml-2 text-lg font-semibold">Pago con QR De Una (Banco Pichincha)</span>
                                    <img class="h-10 ml-auto" src="{{ asset('img/qr.png') }}" alt="Pago QR De Una">
                                </label>
                                <div class="p-4 text-sm text-gray-600 bg-gray-200" x-show="payment == 4">
                                    <i class="fa-solid fa-qrcode"></i>
                                    <p class="mt-2">Escanea el siguiente código QR con la app De Una de Banco
                                        Pichincha para realizar el pago.</p>
                                    <img src="{{ asset('img/qr-pichincha.png') }}" alt="QR De Una Banco Pichincha"
                                        class="w-40 h-40 mx-auto mt-4">
                                    <p class="mt-2 text-xs text-gray-500">Asegúrate de completar el pago y confirmar en
                                        el resumen.</p>
                                </div>
                            </li>

                            <!-- PayPhone y PayPal: lógica preparada y comentada para futura integración -->
                            <!--
                            <li>
                                <label class="flex items-center p-4" for="">
                                    <input type="radio" name="payment" value="5" x-model="payment" class="mr-2">
                                    <span class="ml-2 text-lg font-semibold">Tarjeta de Crédito/Débito (PayPhone)</span>
                                    <img class="h-10 ml-auto" src="{{ asset('img/payphone-logo.png') }}" alt="PayPhone">
                                </label>
                                <div class="p-4 text-sm text-gray-600 bg-gray-200" x-show="payment == 5">
                                    <i class="fa-brands fa-payphone"></i>
                                    <p class="mt-2">Paga con PayPhone. Serás redirigido a la pasarela oficial para completar el pago.</p>
                                </div>
                            </li>
                            <li>
                                <label class="flex items-center p-4" for="">
                                    <input type="radio" name="payment" value="6" x-model="payment" class="mr-2">
                                    <span class="ml-2 text-lg font-semibold">PayPal</span>
                                    <img class="h-10 ml-auto" src="{{ asset('img/paypal.png') }}" alt="PayPal">
                                </label>
                                <div class="p-4 text-sm text-gray-600 bg-gray-200" x-show="payment == 6">
                                    <i class="fa-brands fa-paypal"></i>
                                    <p class="mt-2">Paga de forma segura con PayPal. Serás redirigido a la pasarela oficial.</p>
                                </div>
                            </li>
                            -->
                        </ul>

                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="lg:max-w-[40rem] px-4 lg:pr-8 lg:pl-8 sm:pr-6 mr-auto py-12">
                    <h1 class="mb-4 text-2xl font-bold">Resumen de Compra</h1>

                    <!-- Resumen del carrito -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                        <template x-for="item in cartItems" :key="item.id">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div>
                                    <span x-text="item.name" class="text-sm font-medium"></span>
                                    <span x-text="'x' + item.quantity" class="ml-2 text-xs text-gray-500"></span>
                                </div>
                                <span x-text="'$' + item.subtotal.toFixed(2)" class="text-sm font-semibold"></span>
                            </div>
                        </template>

                        <div class="pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal:</span>
                                <span x-text="'$' + subtotal.toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Envío:</span>
                                <span x-text="'$' + shipping.toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between pt-2 text-lg font-bold border-t">
                                <span>Total:</span>
                                <span x-text="'$' + total.toFixed(2)"></span>
                            </div>
                        </div>

                        <!-- Botones dinámicos según método de pago -->
                        <div class="mt-6">
                            <!--
                            <button x-show="payment == 1" x-cloak @click="showPaymentModal = true"
                                class="w-full px-4 py-3 font-semibold text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                                Pagar con PayPhone - <span x-text="'$' + total.toFixed(2)"></span>
                            </button>
                            -->
                            <button x-show="payment == 2" x-cloak @click="showTransferModal = true"
                                class="w-full px-4 py-3 font-semibold text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                                Ya Transferí - Subir Comprobante
                            </button>

                            <button x-show="payment == 3" x-cloak @click="confirmCashPayment()"
                                class="w-full px-4 py-3 font-semibold text-white transition bg-orange-600 rounded-lg hover:bg-orange-700">
                                Confirmar Pedido (Pago Contra Entrega)
                            </button>

                            <button x-show="payment == 4" x-cloak @click="showQrModal = true"
                                class="w-full px-4 py-3 font-semibold text-white transition bg-purple-600 rounded-lg hover:bg-purple-700">
                                Ya Pagué con QR - Subir Comprobante
                            </button>

                            <!-- Botones para métodos de pago futuros (comentados) -->
                            <!--
                            <button x-show="payment == 5" x-cloak @click="submitPayPhoneGateway()"
                                class="w-full px-4 py-3 font-semibold text-white transition bg-blue-800 rounded-lg hover:bg-blue-900">
                                Pagar con PayPhone - <span x-text="'$' + total.toFixed(2)"></span>
                            </button>

                            <button x-show="payment == 6" x-cloak @click="submitPayPalGateway()"
                                class="w-full px-4 py-3 font-semibold text-white transition bg-yellow-600 rounded-lg hover:bg-yellow-700">
                                Pagar con PayPal - <span x-text="'$' + total.toFixed(2)"></span>
                            </button>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal PayPhone (comentado) -->
        <!--
        <div x-show="showPaymentModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <div class="w-full max-w-md p-6 mx-4 bg-white rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Pagar con PayPhone</h3>
                    <button @click="showPaymentModal = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form @submit.prevent="submitPayPhonePayment">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número de Tarjeta</label>
                            <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha Venc.</label>
                                <input type="text" name="expiry_date" placeholder="MM/AA" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" name="cvv" placeholder="123" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Titular</label>
                            <input type="text" name="cardholder_name" placeholder="Juan Pérez" required
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="p-3 bg-gray-100 rounded-md">
                            <div class="flex justify-between text-sm">
                                <span>Total a pagar:</span>
                                <span class="font-bold" x-text="'$' + total.toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-6 space-x-3">
                        <button type="button" @click="showPaymentModal = false"
                            class="flex-1 px-4 py-2 text-gray-700 transition bg-gray-300 rounded-md hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 text-white transition bg-blue-600 rounded-md hover:bg-blue-700">
                            Pagar Ahora
                        </button>
                    </div>
                </form>
            </div>
        </div>
        -->
        <!-- Modal Transferencia -->
        <div x-show="showTransferModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
            <div class="w-full max-w-md p-6 mx-4 bg-white rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Subir Comprobante de Transferencia</h3>
                    <button @click="showTransferModal = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form enctype="multipart/form-data" @submit.prevent="submitTransferReceipt">
                    @csrf
                    <div class="space-y-4">
                        <div class="p-4 rounded-md bg-blue-50">
                            <h4 class="font-semibold text-blue-900">Datos para transferencia:</h4>
                            <p class="text-sm text-blue-800">Banco: Banco Ejemplo</p>
                            <p class="text-sm text-blue-800">Cuenta: 123456789</p>
                            <p class="text-sm text-blue-800">Monto: <span x-text="'$' + total.toFixed(2)"></span></p>
                            <p class="text-sm text-blue-800">Referencia: <span x-text="orderNumber"></span></p>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Subir comprobante de transferencia:
                            </label>
                            <div x-data="{ imagePreview: null }" class="space-y-3">
                                <input type="file" name="receipt_file" accept="image/*,application/pdf" required
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            if (file.type.startsWith('image/')) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => imagePreview = e.target.result;
                                                reader.readAsDataURL(file);
                                            } else {
                                                imagePreview = 'pdf';
                                            }
                                        } else {
                                            imagePreview = null;
                                        }
                                    "
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                                <!-- Vista previa -->
                                <div x-show="imagePreview" class="mt-3" x-cloak>
                                    <label class="block mb-1 text-xs font-medium text-gray-600">Vista previa:</label>
                                    <div class="relative p-2 border-2 border-gray-300 border-dashed rounded-lg">
                                        <template x-if="imagePreview === 'pdf'">
                                            <div class="flex items-center justify-center h-24 rounded bg-red-50">
                                                <div class="text-center">
                                                    <i class="mb-1 text-2xl text-red-500 fa-solid fa-file-pdf"></i>
                                                    <p class="text-xs text-red-600">Archivo PDF seleccionado</p>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="imagePreview && imagePreview !== 'pdf'">
                                            <img :src="imagePreview" alt="Vista previa"
                                                class="object-cover w-full h-24 rounded">
                                        </template>
                                        <button type="button"
                                            @click="imagePreview = null; $el.parentElement.parentElement.querySelector('input[type=file]').value = ''"
                                            class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full top-1 right-1 hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Comentarios (opcional)</label>
                            <textarea name="comments" rows="3"
                                placeholder="Agrega cualquier comentario sobre la transferencia..."
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                        </div>
                    </div>
                    <div class="flex mt-6 space-x-3">
                        <button type="button" @click="showTransferModal = false"
                            class="flex-1 px-4 py-2 text-gray-700 transition bg-gray-300 rounded-md hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 text-white transition bg-green-600 rounded-md hover:bg-green-700">
                            Enviar Comprobante
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal QR De Una -->
        <div x-show="showQrModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            x-cloak>
            <div class="w-full max-w-md p-6 mx-4 bg-white rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Confirmar Pago con QR</h3>
                    <button @click="showQrModal = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form enctype="multipart/form-data" @submit.prevent="submitQrReceipt">
                    @csrf
                    <div class="space-y-4">
                        <div class="text-center">
                            <img src="{{ asset('img/qr-pichincha.png') }}" alt="QR De Una"
                                class="w-32 h-32 mx-auto mb-2">
                            <p class="text-sm text-gray-600">Monto: <span class="font-bold"
                                    x-text="'$' + total.toFixed(2)"></span></p>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Subir captura de pantalla del pago:
                            </label>
                            <div x-data="{ qrImagePreview: null }" class="space-y-3">
                                <input type="file" name="receipt_file" accept="image/*,application/pdf" required
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            if (file.type.startsWith('image/')) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => qrImagePreview = e.target.result;
                                                reader.readAsDataURL(file);
                                            } else {
                                                qrImagePreview = 'pdf';
                                            }
                                        } else {
                                            qrImagePreview = null;
                                        }
                                    "
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">

                                <!-- Vista previa QR -->
                                <div x-show="qrImagePreview" class="mt-3" x-cloak>
                                    <label class="block mb-1 text-xs font-medium text-gray-600">Vista previa:</label>
                                    <div class="relative p-2 border-2 border-purple-300 border-dashed rounded-lg">
                                        <template x-if="qrImagePreview === 'pdf'">
                                            <div class="flex items-center justify-center h-24 rounded bg-purple-50">
                                                <div class="text-center">
                                                    <i class="mb-1 text-2xl text-purple-500 fa-solid fa-file-pdf"></i>
                                                    <p class="text-xs text-purple-600">Archivo PDF seleccionado</p>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="qrImagePreview && qrImagePreview !== 'pdf'">
                                            <img :src="qrImagePreview" alt="Vista previa QR"
                                                class="object-cover w-full h-24 rounded">
                                        </template>
                                        <button type="button"
                                            @click="qrImagePreview = null; $el.parentElement.parentElement.querySelector('input[type=file]').value = ''"
                                            class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full top-1 right-1 hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número de transacción
                                (opcional)</label>
                            <input type="text" name="transaction_number" placeholder="Número de transacción De Una"
                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    <div class="flex mt-6 space-x-3">
                        <button type="button" @click="showQrModal = false"
                            class="flex-1 px-4 py-2 text-gray-700 transition bg-gray-300 rounded-md hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 text-white transition bg-purple-600 rounded-md hover:bg-purple-700">
                            Confirmar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.checkoutData = function() {
            return {
                payment: 2, // Por defecto transferencia
                showPaymentModal: false,
                showTransferModal: false,
                showQrModal: false,
                cartItems: {!! json_encode($cartItems ?? []) !!},
                subtotal: {{ $subtotal ?? 0 }},
                shipping: {{ $shipping ?? 0 }},
                total: {{ $totalWithShipping ?? 0 }},
                orderNumber: '',
                init() {
                    this.orderNumber = this.generateOrderNumber();
                },
                generateOrderNumber() {
                    const now = new Date();
                    const dateStr = now.getFullYear() +
                        String(now.getMonth() + 1).padStart(2, '0') +
                        String(now.getDate()).padStart(2, '0');
                    const randomStr = Math.random().toString(36).substr(2, 6).toUpperCase();
                    return `ORD-${dateStr}-${randomStr}`;
                },

                // Métodos de pago unificados - usando CheckoutController
                async submitTransferReceipt(event) {
                    try {
                        const formData = new FormData(event.target);

                        const response = await fetch('{{ route('checkout.transfer-payment') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const result = await response.json();
                        console.log('Resultado de transfer payment:', result);

                        if (result.success) {
                            this.showTransferModal = false;

                            // Mostrar animación celebratoria con confeti
                            this.showSuccessAnimation();

                            // Redirigir después de la animación
                            setTimeout(() => {
                                window.location.href = result.redirect_url ||
                                    '{{ route('checkout.thank-you') }}';
                            }, 3000);
                        } else {
                            this.showErrorMessage('Error: ' + result.message);
                        }
                    } catch (error) {
                        this.showErrorMessage('Error al subir el comprobante: ' + error.message);
                    }
                },

                async submitQrReceipt(event) {
                    try {
                        const formData = new FormData(event.target);

                        const response = await fetch('{{ route('checkout.qr-payment') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.showQrModal = false;

                            // Mostrar animación celebratoria con confeti
                            this.showSuccessAnimation();

                            // Redirigir después de la animación
                            setTimeout(() => {
                                window.location.href = result.redirect_url ||
                                    '{{ route('checkout.thank-you') }}';
                            }, 3000);
                        } else {
                            this.showErrorMessage('Error: ' + result.message);
                        }
                    } catch (error) {
                        this.showErrorMessage('Error al subir el comprobante: ' + error.message);
                    }
                },

                async confirmCashPayment() {
                    try {
                        // Verificar que hay una dirección por defecto disponible
                        @if (!$defaultAddress)
                            this.showErrorMessage(
                                'No tienes una dirección de envío configurada. Por favor, configura una dirección antes de continuar.'
                            );
                            return;
                        @endif

                        console.log('Iniciando confirmación de pago en efectivo...');

                        const response = await fetch('{{ route('checkout.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                payment_method: 3 // Pago en efectivo contra entrega
                            })
                        });

                        const result = await response.json();
                        console.log('Respuesta del servidor:', result);

                        if (result.success) {
                            console.log('Pedido creado exitosamente');
                            // Mostrar animación celebratoria con confeti
                            this.showSuccessAnimation();
                            // Redirigir después de la animación
                            setTimeout(() => {
                                window.location.href = result.redirect_url || '{{ route('checkout.thank-you') }}';
                            }, 3000);
                        } else {
                            console.error('Error en la respuesta:', result);
                            this.showErrorMessage('Error: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error completo:', error);
                        this.showErrorMessage('Error al confirmar el pedido: ' + error.message);
                    }
                },

                showSuccessMessage(message) {
                    // Crear overlay con animación
                    const successOverlay = document.createElement('div');
                    successOverlay.className =
                        'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
                    successOverlay.style.opacity = '0';
                    successOverlay.style.transition = 'opacity 0.3s ease-in-out';

                    successOverlay.innerHTML = `
                    <div class="relative w-full max-w-md mx-4">
                        <!-- Modal principal -->
                        <div class="overflow-hidden transition-all duration-500 ease-out transform scale-75 bg-white shadow-2xl rounded-2xl success-modal">
                            <!-- Header con gradiente -->
                            <div class="relative px-6 py-8 overflow-hidden text-center bg-gradient-to-r from-green-400 to-green-600">
                                <!-- Partículas animadas -->
                                <div class="absolute inset-0 opacity-20">
                                    <div class="particle" style="left: 20%; animation-delay: 0s;"></div>
                                    <div class="particle" style="left: 40%; animation-delay: 0.5s;"></div>
                                    <div class="particle" style="left: 60%; animation-delay: 1s;"></div>
                                    <div class="particle" style="left: 80%; animation-delay: 1.5s;"></div>
                                </div>
                                
                                <!-- Icono principal animado -->
                                <div class="relative mb-4">
                                    <div class="success-icon-container">
                                        <div class="flex items-center justify-center w-20 h-20 p-4 mx-auto bg-white rounded-full success-icon">
                                            <svg class="w-10 h-10 text-green-500 checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <h3 class="mb-2 text-2xl font-bold text-white">¡Excelente!</h3>
                                <p class="text-sm text-green-100">Pago procesado exitosamente</p>
                            </div>
                            
                            <!-- Contenido -->
                            <div class="px-6 py-6 text-center">
                                <p class="mb-6 leading-relaxed text-gray-700">${message}</p>
                                
                                <!-- Botones -->
                                <div class="space-y-3">
                                    <button onclick="window.location.href='{{ route('checkout.thank-you') }}'" 
                                            class="w-full px-6 py-3 font-semibold text-white transition-all duration-200 transform rounded-lg shadow-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:scale-105">
                                        <i class="mr-2 fa-solid fa-heart"></i>
                                        Ver detalles
                                    </button>
                                    
                                    <button onclick="this.closest('.fixed').remove()" 
                                            class="w-full px-6 py-2 text-gray-700 transition-colors duration-200 bg-gray-100 rounded-lg hover:bg-gray-200">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confetti animado -->
                    <div class="absolute inset-0 overflow-hidden pointer-events-none confetti-container">
                        ${this.generateConfetti()}
                    </div>
                `;

                    // Agregar estilos CSS
                    if (!document.querySelector('#success-animation-styles')) {
                        const styles = document.createElement('style');
                        styles.id = 'success-animation-styles';
                        styles.textContent = `
                        @keyframes particle-float {
                            0% { transform: translateY(100px) rotate(0deg); opacity: 0; }
                            50% { opacity: 1; }
                            100% { transform: translateY(-20px) rotate(360deg); opacity: 0; }
                        }
                        
                        @keyframes checkmark-draw {
                            0% { stroke-dasharray: 0 100; }
                            100% { stroke-dasharray: 100 0; }
                        }
                        
                        @keyframes confetti-fall {
                            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
                            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
                        }
                        
                        @keyframes bounce-in {
                            0% { transform: scale(0.3) rotate(-10deg); opacity: 0; }
                            50% { transform: scale(1.1) rotate(5deg); }
                            100% { transform: scale(1) rotate(0deg); opacity: 1; }
                        }
                        
                        .particle {
                            position: absolute;
                            width: 10px;
                            height: 10px;
                            background: white;
                            border-radius: 50%;
                            animation: particle-float 2s infinite ease-in-out;
                        }
                        
                        .success-icon-container {
                            animation: bounce-in 0.6s ease-out 0.2s both;
                        }
                        
                        .checkmark {
                            stroke-dasharray: 100;
                            animation: checkmark-draw 0.8s ease-out 0.5s both;
                        }
                        
                        .confetti-piece {
                            position: absolute;
                            width: 10px;
                            height: 10px;
                            animation: confetti-fall 3s linear infinite;
                        }
                        
                        .success-modal {
                            animation: bounce-in 0.5s ease-out both;
                        }
                    `;
                        document.head.appendChild(styles);
                    }

                    document.body.appendChild(successOverlay);

                    // Animar entrada
                    setTimeout(() => {
                        successOverlay.style.opacity = '1';
                        const modal = successOverlay.querySelector('.success-modal');
                        modal.style.transform = 'scale(1)';
                    }, 10);

                    // Auto-remover después de 8 segundos y redirigir
                    setTimeout(() => {
                        if (successOverlay.parentNode) {
                            successOverlay.style.opacity = '0';
                            setTimeout(() => {
                                successOverlay.remove();
                                // Redireccionar a página de agradecimiento
                                window.location.href = '{{ route('checkout.thank-you') }}';
                            }, 300);
                        }
                    }, 4000); // Reducido a 4 segundos para redirección más rápida
                },

                generateConfetti() {
                    const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8'];
                    let confetti = '';

                    for (let i = 0; i < 50; i++) {
                        const color = colors[Math.floor(Math.random() * colors.length)];
                        const left = Math.random() * 100;
                        const delay = Math.random() * 3;
                        const duration = 2 + Math.random() * 2;

                        confetti += `
                        <div class="confetti-piece" 
                             style="left: ${left}%; 
                                    background-color: ${color}; 
                                    animation-delay: ${delay}s;
                                    animation-duration: ${duration}s;
                                    transform: rotate(${Math.random() * 360}deg);">
                        </div>
                    `;
                    }

                    return confetti;
                },

                showErrorMessage(message) {
                    // Crear overlay con animación de error
                    const errorOverlay = document.createElement('div');
                    errorOverlay.className =
                        'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
                    errorOverlay.style.opacity = '0';
                    errorOverlay.style.transition = 'opacity 0.3s ease-in-out';

                    errorOverlay.innerHTML = `
                    <div class="relative w-full max-w-md mx-4">
                        <div class="overflow-hidden transition-all duration-500 ease-out transform scale-75 bg-white shadow-2xl rounded-2xl error-modal">
                            <!-- Header con gradiente rojo -->
                            <div class="relative px-6 py-8 overflow-hidden text-center bg-gradient-to-r from-red-400 to-red-600">
                                <!-- Icono de error animado -->
                                <div class="relative mb-4">
                                    <div class="error-icon-container">
                                        <div class="flex items-center justify-center w-20 h-20 p-4 mx-auto bg-white rounded-full error-icon">
                                            <svg class="w-10 h-10 text-red-500 x-mark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <h3 class="mb-2 text-2xl font-bold text-white">¡Ups!</h3>
                                <p class="text-sm text-red-100">Ocurrió un problema</p>
                            </div>
                            
                            <!-- Contenido -->
                            <div class="px-6 py-6 text-center">
                                <p class="mb-6 leading-relaxed text-gray-700">${message}</p>
                                
                                <!-- Botón -->
                                <button onclick="this.closest('.fixed').remove()" 
                                        class="w-full px-6 py-3 font-semibold text-white transition-all duration-200 transform rounded-lg shadow-lg bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:scale-105">
                                    <i class="mr-2 fa-solid fa-times"></i>
                                    Entendido
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                    // Agregar estilos CSS para errores si no existen
                    if (!document.querySelector('#error-animation-styles')) {
                        const styles = document.createElement('style');
                        styles.id = 'error-animation-styles';
                        styles.textContent = `
                        .error-icon-container {
                            animation: bounce-in 0.6s ease-out 0.2s both;
                        }
                        
                        .x-mark {
                            stroke-dasharray: 100;
                            animation: checkmark-draw 0.8s ease-out 0.5s both;
                        }
                        
                        .error-modal {
                            animation: bounce-in 0.5s ease-out both;
                        }
                    `;
                        document.head.appendChild(styles);
                    }

                    document.body.appendChild(errorOverlay);

                    // Animar entrada
                    setTimeout(() => {
                        errorOverlay.style.opacity = '1';
                        const modal = errorOverlay.querySelector('.error-modal');
                        modal.style.transform = 'scale(1)';
                    }, 10);

                    // Auto-remover después de 5 segundos
                    setTimeout(() => {
                        if (errorOverlay.parentNode) {
                            errorOverlay.style.opacity = '0';
                            setTimeout(() => errorOverlay.remove(), 300);
                        }
                    }, 5000);
                },

                showSuccessAnimation() {
                    console.log('Ejecutando showSuccessAnimation');
                    // Crear overlay con animación de éxito
                    const successOverlay = document.createElement('div');
                    successOverlay.className =
                        'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
                    successOverlay.style.opacity = '0';
                    successOverlay.style.transition = 'opacity 0.3s ease-in-out';

                    successOverlay.innerHTML = `
                        <div class="relative w-full max-w-md mx-4">
                            <div class="overflow-hidden transition-all duration-500 ease-out transform scale-75 bg-white shadow-2xl rounded-2xl success-modal">
                                <!-- Header con gradiente verde -->
                                <div class="relative px-6 py-8 overflow-hidden text-center bg-gradient-to-r from-green-400 to-green-600">
                                    <!-- Icono de éxito animado -->
                                    <div class="relative mb-4">
                                        <div class="success-icon-container">
                                            <div class="flex items-center justify-center w-20 h-20 p-4 mx-auto bg-white rounded-full success-icon">
                                                <svg class="w-10 h-10 text-green-500 checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <h3 class="mb-2 text-2xl font-bold text-white">¡Genial!</h3>
                                    <p class="text-sm text-green-100">Comprobante enviado exitosamente</p>
                                </div>
                                
                                <!-- Contenido -->
                                <div class="px-6 py-6 text-center">
                                    <p class="mb-4 leading-relaxed text-gray-700">Tu comprobante ha sido enviado correctamente. Verificaremos tu pago en las próximas 24 horas.</p>
                                    <div class="flex items-center justify-center mb-4">
                                        <div class="w-8 h-8 border-4 border-green-500 border-dashed rounded-full animate-spin"></div>
                                        <span class="ml-3 text-sm text-gray-600">Redirigiendo...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Agregar estilos CSS para animaciones si no existen
                    if (!document.querySelector('#success-animation-styles')) {
                        const styles = document.createElement('style');
                        styles.id = 'success-animation-styles';
                        styles.textContent = `
                            @keyframes bounce-in {
                                0% { transform: scale(0.3); opacity: 0; }
                                50% { transform: scale(1.05); }
                                70% { transform: scale(0.9); }
                                100% { transform: scale(1); opacity: 1; }
                            }
                            
                            @keyframes checkmark-draw {
                                0% { stroke-dashoffset: 100; }
                                100% { stroke-dashoffset: 0; }
                            }
                            
                            @keyframes confetti {
                                0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
                                100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
                            }
                            
                            .success-icon-container {
                                animation: bounce-in 0.6s ease-out 0.2s both;
                            }
                            
                            .checkmark {
                                stroke-dasharray: 100;
                                animation: checkmark-draw 0.8s ease-out 0.5s both;
                            }
                            
                            .success-modal {
                                animation: bounce-in 0.5s ease-out both;
                            }
                            
                            .confetti-piece {
                                position: fixed;
                                width: 10px;
                                height: 10px;
                                z-index: 60;
                                animation: confetti 3s linear infinite;
                            }
                        `;
                        document.head.appendChild(styles);
                    }

                    document.body.appendChild(successOverlay);

                    // Animar entrada
                    setTimeout(() => {
                        successOverlay.style.opacity = '1';
                        const modal = successOverlay.querySelector('.success-modal');
                        modal.style.transform = 'scale(1)';
                    }, 10);

                    // Crear confeti
                    this.createConfetti();
                },

                createConfetti() {
                    const colors = ['#f43f5e', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444'];
                    const confettiCount = 50;

                    for (let i = 0; i < confettiCount; i++) {
                        setTimeout(() => {
                            const confetti = document.createElement('div');
                            confetti.className = 'confetti-piece';
                            confetti.style.left = Math.random() * 100 + 'vw';
                            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                            confetti.style.animationDelay = Math.random() * 3 + 's';
                            confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';

                            document.body.appendChild(confetti);

                            // Remover confeti después de la animación
                            setTimeout(() => {
                                if (confetti.parentNode) {
                                    confetti.remove();
                                }
                            }, 5000);
                        }, i * 100);
                    }
                }
            }
        }
    </script>
</x-app-layout>