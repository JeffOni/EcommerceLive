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
                                    <p class="ml-6 text-sm text-gray-600">
                                        {{ $defaultAddress->parish->name ?? '' }},
                                        {{ $defaultAddress->canton->name ?? '' }},
                                        {{ $defaultAddress->province->name ?? '' }}
                                    </p>
                                    @if ($defaultAddress->reference)
                                    <p class="ml-6 text-sm text-gray-500">Ref: {{ $defaultAddress->reference }}
                                    </p>
                                    @endif
                                    @if ($defaultAddress->postal_code)
                                    <p class="ml-6 text-sm text-gray-500">CP: {{ $defaultAddress->postal_code }}
                                    </p>
                                    @endif
                                    @if ($defaultAddress->receiver_name && $defaultAddress->receiver_name !==
                                    auth()->user()->name)
                                    <p class="mt-1 ml-6 text-sm text-gray-600">
                                        <i class="mr-1 text-xs fas fa-user"></i>
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
                                    class="inline-flex items-center px-4 py-2 font-medium text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700">
                                    <i class="mr-2 fas fa-plus"></i>
                                    Configurar dirección de envío
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Tipo de Entrega -->
                    <div class="mb-8">
                        <h2 class="mb-4 text-xl font-bold">Tipo de Entrega</h2>
                        <div class="space-y-3">
                            <!-- Envío a domicilio -->
                            <label
                                class="flex items-start p-4 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="deliveryType === 'delivery' ? 'border-blue-500 bg-blue-50' : ''">
                                <input type="radio" name="deliveryType" value="delivery" x-model="deliveryType"
                                    class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="mr-2 text-blue-600 fas fa-truck"></i>
                                            <span class="font-semibold text-gray-900">Envío a domicilio</span>
                                        </div>
                                        <span class="text-sm font-medium text-green-600">$5.00</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">Tu pedido será entregado en la dirección
                                        especificada</p>
                                    <p class="text-xs text-gray-500">Tiempo estimado: 24-48 horas</p>
                                </div>
                            </label>

                            <!-- Retiro en tienda -->
                            <label
                                class="flex items-start p-4 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50"
                                :class="deliveryType === 'pickup' ? 'border-blue-500 bg-blue-50' : ''">
                                <input type="radio" name="deliveryType" value="pickup" x-model="deliveryType"
                                    class="mt-1 mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="mr-2 text-blue-600 fas fa-store"></i>
                                            <span class="font-semibold text-gray-900">Retiro en tienda</span>
                                        </div>
                                        <span class="text-sm font-medium text-green-600">Gratis</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">Retira tu pedido en nuestra tienda física</p>
                                    <div class="mt-2 space-y-1 text-xs text-gray-500"
                                        x-show="deliveryType === 'pickup'">
                                        <p><i class="mr-1 fas fa-map-marker-alt"></i> Av. Principal #123, Sector Centro,
                                            Quito</p>
                                        <p><i class="mr-1 fas fa-phone"></i> +593 99 999 9999</p>
                                        <p><i class="mr-1 fas fa-clock"></i> Lunes a Viernes: 9:00 AM - 6:00 PM,
                                            Sábados: 9:00 AM - 1:00 PM</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Mensaje informativo para retiro en tienda -->
                        <div class="p-3 mt-3 border border-blue-200 rounded-lg bg-blue-50"
                            x-show="deliveryType === 'pickup'">
                            <div class="flex items-start">
                                <i class="mr-2 mt-0.5 text-blue-600 fas fa-info-circle"></i>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium">Instrucciones para retiro:</p>
                                    <ul class="mt-1 space-y-1 text-xs">
                                        <li>• Presenta tu documento de identidad</li>
                                        <li>• Menciona el número de pedido: #<span
                                                x-text="orderNumber || 'XXXX'"></span></li>
                                        <li>• El pedido estará listo en 2-4 horas después de confirmar el pago</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
                            {{-- <li>
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
                            </li> --}}

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
                                <span>Impuestos (15%):</span>
                                <span x-text="'$' + (subtotal * 0.15).toFixed(2)"></span>
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
                                :disabled="transferProcessing"
                                :class="transferProcessing ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full px-4 py-3 font-semibold text-white transition bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50">
                                <span x-show="!transferProcessing">Ya Transferí - Subir Comprobante</span>
                                <span x-show="transferProcessing" class="flex items-center justify-center">
                                    <div
                                        class="w-5 h-5 border-2 border-white border-dashed rounded-full animate-spin mr-2">
                                    </div>
                                    Procesando...
                                </span>
                            </button>

                            <button x-show="payment == 3" x-cloak @click="confirmCashPayment()"
                                :disabled="cashProcessing"
                                :class="cashProcessing ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full px-4 py-3 font-semibold text-white transition bg-orange-600 rounded-lg hover:bg-orange-700 disabled:opacity-50">
                                <span x-show="!cashProcessing">Confirmar Pedido (Pago Contra Entrega)</span>
                                <span x-show="cashProcessing" class="flex items-center justify-center">
                                    <div
                                        class="w-5 h-5 border-2 border-white border-dashed rounded-full animate-spin mr-2">
                                    </div>
                                    Procesando...
                                </span>
                            </button> <button x-show="payment == 4" x-cloak @click="showQrModal = true"
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
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-2 xs:p-4" x-cloak>
            <div class="w-full max-w-xs xs:max-w-sm sm:max-w-md bg-white rounded-lg">
                <div class="flex items-center justify-between mb-3 xs:mb-4 p-3 xs:p-4 sm:p-6 pb-0">
                    <h3 class="text-sm xs:text-base sm:text-lg font-bold">
                        <span class="hidden xs:inline">Subir Comprobante de Transferencia</span>
                        <span class="xs:hidden">Comprobante</span>
                    </h3>
                    <button @click="showTransferModal = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-times text-sm xs:text-base"></i>
                    </button>
                </div>
                <form enctype="multipart/form-data" @submit.prevent="submitTransferReceipt"
                    class="p-3 xs:p-4 sm:p-6 pt-0">
                    @csrf
                    <div class="space-y-3 xs:space-y-4">
                        <div class="p-2 xs:p-3 sm:p-4 rounded-md bg-blue-50">
                            <h4 class="font-semibold text-blue-900 text-xs xs:text-sm">
                                <span class="hidden xs:inline">Datos para transferencia:</span>
                                <span class="xs:hidden">Transferir a:</span>
                            </h4>
                            <p class="text-xs xs:text-sm text-blue-800">Banco: Banco Ejemplo</p>
                            <p class="text-xs xs:text-sm text-blue-800">Cuenta: 123456789</p>
                            <p class="text-xs xs:text-sm text-blue-800">Monto: <span
                                    x-text="'$' + total.toFixed(2)"></span></p>
                            <p class="text-xs xs:text-sm text-blue-800">Referencia: <span x-text="orderNumber"></span>
                            </p>
                            <div class="pt-1 xs:pt-2 mt-1 xs:mt-2 border-t border-blue-200">
                                <p class="text-xs text-blue-700">Desglose:</p>
                                <p class="text-xs text-blue-700">• Subtotal: $<span x-text="subtotal.toFixed(2)"></span>
                                </p>
                                <p class="text-xs text-blue-700">• Impuestos (15%): $<span
                                        x-text="(subtotal * 0.15).toFixed(2)"></span></p>
                                <p class="text-xs text-blue-700">• Envío: $<span x-text="shipping.toFixed(2)"></span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 xs:mb-2 text-xs xs:text-sm font-medium text-gray-700">
                                <span class="hidden xs:inline">Subir comprobante de transferencia:</span>
                                <span class="xs:hidden">Comprobante:</span>
                            </label>
                            <div x-data="{ imagePreview: null }" class="space-y-2 xs:space-y-3">
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
                                    class="block w-full text-xs xs:text-sm text-gray-500 file:mr-2 xs:file:mr-4 file:py-1 xs:file:py-2 file:px-2 xs:file:px-4 file:rounded-full file:border-0 file:text-xs xs:file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                                <!-- Vista previa -->
                                <div x-show="imagePreview" class="mt-2 xs:mt-3" x-cloak>
                                    <label class="block mb-1 text-xs font-medium text-gray-600">Vista previa:</label>
                                    <div class="relative p-1 xs:p-2 border-2 border-gray-300 border-dashed rounded-lg">
                                        <template x-if="imagePreview === 'pdf'">
                                            <div
                                                class="flex items-center justify-center h-16 xs:h-20 sm:h-24 rounded bg-red-50">
                                                <div class="text-center">
                                                    <i
                                                        class="mb-1 text-lg xs:text-xl sm:text-2xl text-red-500 fa-solid fa-file-pdf"></i>
                                                    <p class="text-xs text-red-600">PDF seleccionado</p>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="imagePreview && imagePreview !== 'pdf'">
                                            <img :src="imagePreview" alt="Vista previa"
                                                class="object-cover w-full h-16 xs:h-20 sm:h-24 rounded">
                                        </template>
                                        <button type="button"
                                            @click="imagePreview = null; $el.parentElement.parentElement.querySelector('input[type=file]').value = ''"
                                            class="absolute flex items-center justify-center w-4 h-4 xs:w-5 xs:h-5 text-xs text-white bg-red-500 rounded-full top-1 right-1 hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs xs:text-sm font-medium text-gray-700">Comentarios
                                (opcional)</label>
                            <textarea name="comments" rows="2" xs:rows="3"
                                placeholder="Comentarios sobre la transferencia..."
                                class="block w-full px-2 xs:px-3 py-1 xs:py-2 mt-1 text-xs xs:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                        </div>
                    </div>
                    <div class="flex mt-4 xs:mt-6 space-x-2 xs:space-x-3">
                        <button type="button" @click="showTransferModal = false"
                            class="flex-1 px-2 xs:px-3 sm:px-4 py-2 text-xs xs:text-sm text-gray-700 transition bg-gray-300 rounded-md hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="transferSubmitting"
                            :class="transferSubmitting ? 'opacity-50 cursor-not-allowed' : ''"
                            class="flex-1 px-2 xs:px-3 sm:px-4 py-2 text-xs xs:text-sm text-white transition bg-green-600 rounded-md hover:bg-green-700 disabled:opacity-50">
                            <span x-show="!transferSubmitting">
                                <span class="hidden xs:inline">Enviar Comprobante</span>
                                <span class="xs:hidden">Enviar</span>
                            </span>
                            <span x-show="transferSubmitting" class="flex items-center justify-center">
                                <div
                                    class="w-3 h-3 xs:w-4 xs:h-4 border-2 border-white border-dashed rounded-full animate-spin mr-1 xs:mr-2">
                                </div>
                                Enviando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal QR De Una -->
        <div x-show="showQrModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-2 xs:p-4" x-cloak>
            <div class="w-full max-w-xs xs:max-w-sm sm:max-w-md bg-white rounded-lg">
                <div class="flex items-center justify-between mb-3 xs:mb-4 p-3 xs:p-4 sm:p-6 pb-0">
                    <h3 class="text-sm xs:text-base sm:text-lg font-bold">
                        <span class="hidden xs:inline">Confirmar Pago con QR</span>
                        <span class="xs:hidden">Pago QR</span>
                    </h3>
                    <button @click="showQrModal = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-times text-sm xs:text-base"></i>
                    </button>
                </div>
                <form enctype="multipart/form-data" @submit.prevent="submitQrReceipt" class="p-3 xs:p-4 sm:p-6 pt-0">
                    @csrf
                    <div class="space-y-4">
                        <div class="text-center">
                            <img src="{{ asset('img/qr-pichincha.png') }}" alt="QR De Una"
                                class="w-32 h-32 mx-auto mb-2">
                            <p class="text-sm text-gray-600">Monto: <span class="font-bold"
                                    x-text="'$' + total.toFixed(2)"></span></p>
                            <div class="p-2 mt-2 text-xs text-gray-600 rounded bg-gray-50">
                                <p>Subtotal: $<span x-text="subtotal.toFixed(2)"></span></p>
                                <p>Impuestos (15%): $<span x-text="(subtotal * 0.15).toFixed(2)"></span></p>
                                <p>Envío: $<span x-text="shipping.toFixed(2)"></span></p>
                            </div>
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
                        <button type="submit" :disabled="qrSubmitting"
                            :class="qrSubmitting ? 'opacity-50 cursor-not-allowed' : ''"
                            class="flex-1 px-4 py-2 text-white transition bg-purple-600 rounded-md hover:bg-purple-700 disabled:opacity-50">
                            <span x-show="!qrSubmitting">Confirmar Pago</span>
                            <span x-show="qrSubmitting" class="flex items-center justify-center">
                                <div class="w-4 h-4 border-2 border-white border-dashed rounded-full animate-spin mr-2">
                                </div>
                                Confirmando...
                            </span>
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
                deliveryType: 'delivery', // Por defecto envío a domicilio
                showPaymentModal: false,
                showTransferModal: false,
                showQrModal: false,
                cartItems: {!! json_encode($cartItems ?? []) !!},
                subtotal: {{ $subtotal ?? 0 }},
                shipping: {{ $shipping ?? 0 }},
                total: {{ $totalWithShipping ?? 0 }},
                taxRate: 0.15, // 15% de impuestos
                orderNumber: '',
                
                // Estados de procesamiento para evitar doble envío
                _isSubmittingTransfer: false,
                _isSubmittingQr: false,
                _isSubmittingCash: false,
                _lastRedirectUrl: null,
                
                // Estados para UI de loading
                transferProcessing: false,
                cashProcessing: false,
                transferSubmitting: false,
                qrSubmitting: false,
                
                // Datos de dirección de envío para validación
                shippingProvince: @if($defaultAddress) '{{ $defaultAddress->province->name ?? '' }}' @else '' @endif,
                
                init() {
                    this.orderNumber = this.generateOrderNumber();
                    this.validateShippingProvince();
                    // Calcular total inicial con impuestos
                    const subtotalWithTax = this.subtotal * (1 + this.taxRate);
                    this.total = subtotalWithTax + this.shipping;
                    this.updateTotal();
                },

                // Actualizar total según tipo de entrega
                updateTotal() {
                    this.$watch('deliveryType', (value) => {
                        if (value === 'pickup') {
                            this.shipping = 0;
                        } else {
                            this.shipping = {{ $shipping ?? 0 }};
                        }
                        // Calcular total con subtotal + impuestos + envío
                        const subtotalWithTax = this.subtotal * (1 + this.taxRate);
                        this.total = subtotalWithTax + this.shipping;
                    });
                },
                
                // Validar provincia de envío
                validateShippingProvince() {
                    // Si es retiro en tienda, no validar provincia
                    if (this.deliveryType === 'pickup') {
                        return true;
                    }

                    const allowedProvinces = ['Pichincha', 'Manabí'];
                    
                    if (this.shippingProvince && !allowedProvinces.includes(this.shippingProvince)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Dirección de envío no disponible',
                            html: `
                                <p>Lo sentimos, actualmente solo realizamos envíos a:</p>
                                <ul style="text-align: left; margin: 10px 0;">
                                    <li>• <strong>Pichincha</strong> (Quito y alrededores)</li>
                                    <li>• <strong>Manabí</strong> (Manta, Portoviejo y alrededores)</li>
                                </ul>
                                <p>Tu dirección actual está en: <strong>${this.shippingProvince}</strong></p>
                                <p style="margin-top: 15px;">Puedes:</p>
                                <ul style="text-align: left;">
                                    <li>• Cambiar tu dirección si tienes una en zona de cobertura</li>
                                    <li>• Seleccionar "Retiro en tienda" como tipo de entrega</li>
                                </ul>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Cambiar dirección',
                            cancelButtonText: 'Cancelar compra',
                            confirmButtonColor: '#3b82f6',
                            cancelButtonColor: '#ef4444'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('shipping.index') }}';
                            } else {
                                window.location.href = '{{ route('cart.index') }}';
                            }
                        });
                        return false;
                    }
                    return true;
                },
                
                // Verificar provincia antes de cualquier pago
                checkProvinceBeforePayment() {
                    console.log('Validando provincia antes del pago...');
                    const isValid = this.validateShippingProvince();
                    console.log('Provincia válida:', isValid);
                    if (!isValid) {
                        console.log('Validación de provincia falló, deteniendo proceso');
                        return false;
                    }
                    console.log('Provincia válida, continuando proceso');
                    return true;
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
                    console.log('=== INICIANDO TRANSFER RECEIPT ===');
                    // Validar provincia antes de procesar
                    if (!this.checkProvinceBeforePayment()) {
                        console.log('Validación de provincia falló, saliendo de submitTransferReceipt');
                        return;
                    }
                    console.log('Validación de provincia exitosa, continuando...');
                    // Evitar doble envío
                    if (this._isSubmittingTransfer) {
                        console.log('Ya se está procesando una transferencia, saliendo...');
                        return;
                    }
                    console.log('Iniciando procesamiento de transferencia...');
                    this._isSubmittingTransfer = true;
                    this.transferSubmitting = true;
                    const submitBtn = event.target.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;
                    try {
                        console.log('Iniciando envío de comprobante de transferencia...');
                        const formData = new FormData(event.target);
                        console.log('FormData creado, enviando fetch a checkout.transfer-payment...');

                        const response = await fetch('{{ route('checkout.transfer-payment') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        console.log('Response recibida, status:', response.status);
                        const result = await response.json();
                        console.log('Resultado de transfer payment:', result);

                        if (result.success) {
                            console.log('Transfer payment exitoso, mostrando animación...');
                            this._lastRedirectUrl = result.redirect_url || '{{ route('checkout.thank-you') }}';
                            this.showSuccessAnimation();
                        } else {
                            console.error('Error en respuesta:', result);
                            this.showErrorMessage('Error: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error completo en transfer:', error);
                        console.log('Tipo de error:', error.constructor.name);
                        console.log('Stack trace:', error.stack);
                        this.showErrorMessage('Error al subir el comprobante: ' + error.message);
                    } finally {
                        this._isSubmittingTransfer = false;
                        this.transferSubmitting = false;
                        if (submitBtn) submitBtn.disabled = false;
                    }
                },

                async submitQrReceipt(event) {
                    console.log('=== INICIANDO QR RECEIPT ===');
                    // Validar provincia antes de procesar
                    if (!this.checkProvinceBeforePayment()) {
                        console.log('Validación de provincia falló, saliendo de submitQrReceipt');
                        return;
                    }
                    console.log('Validación de provincia exitosa para QR, continuando...');
                    // Evitar doble envío
                    if (this._isSubmittingQr) {
                        console.log('Ya se está procesando un QR, saliendo...');
                        return;
                    }
                    console.log('Iniciando procesamiento de QR...');
                    this._isSubmittingQr = true;
                    this.qrSubmitting = true;
                    const submitBtn = event.target.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;
                    try {
                        console.log('Iniciando envío de comprobante QR...');
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
                        console.log('Resultado de QR payment:', result);

                        if (result.success) {
                            console.log('QR payment exitoso, mostrando animación...');
                            this._lastRedirectUrl = result.redirect_url || '{{ route('checkout.thank-you') }}';
                            this.showSuccessAnimation();
                        } else {
                            console.error('Error en respuesta QR:', result);
                            this.showErrorMessage('Error: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error completo QR:', error);
                        console.log('Tipo de error QR:', error.constructor.name);
                        console.log('Stack trace QR:', error.stack);
                        this.showErrorMessage('Error al subir el comprobante: ' + error.message);
                    } finally {
                        this._isSubmittingQr = false;
                        this.qrSubmitting = false;
                        if (submitBtn) submitBtn.disabled = false;
                    }
                },

                async confirmCashPayment() {
                    console.log('=== INICIANDO CASH PAYMENT ===');
                    // Evitar doble procesamiento
                    if (this._isSubmittingCash) {
                        console.log('Ya se está procesando un pago en efectivo, saliendo...');
                        return;
                    }
                    this._isSubmittingCash = true;
                    console.log('Marcado como procesando pago en efectivo...');
                    
                    // Validar provincia antes de procesar
                    if (!this.checkProvinceBeforePayment()) {
                        console.log('Validación de provincia falló, saliendo de confirmCashPayment');
                        this._isSubmittingCash = false;
                        return;
                    }
                    
                    try {
                        // Verificar que hay una dirección por defecto disponible
                        @if (!$defaultAddress)
                            this.showErrorMessage(
                                'No tienes una dirección de envío configurada. Por favor, configura una dirección antes de continuar.'
                            );
                            this._isSubmittingCash = false;
                            return;
                        @endif

                        console.log('Iniciando confirmación de pago en efectivo...');

                        this.cashProcessing = true;

                        const response = await fetch('{{ route('checkout.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                payment_method: 3, // Pago en efectivo contra entrega
                                delivery_type: this.deliveryType
                            })
                        });

                        const result = await response.json();
                        console.log('Respuesta del servidor:', result);

                        if (result.success) {
                            console.log('Pago en efectivo exitoso, mostrando animación...');
                            console.log('Pedido creado exitosamente');
                            this._lastRedirectUrl = result.redirect_url || '{{ route('checkout.thank-you') }}';
                            this.showSuccessAnimation();
                        } else {
                            console.error('Error en la respuesta:', result);
                            this.showErrorMessage('Error: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error completo en cash:', error);
                        console.log('Tipo de error cash:', error.constructor.name);
                        console.log('Stack trace cash:', error.stack);
                        this.showErrorMessage('Error al confirmar el pedido: ' + error.message);
                    } finally {
                        this._isSubmittingCash = false;
                        this.cashProcessing = false;
                    }
                },

                showSuccessAnimation() {
                    console.log('Iniciando showSuccessAnimation...');
                    console.log('URL de redirección:', this._lastRedirectUrl);
                    // Crear overlay de bloqueo y animación de éxito (ahora cumple función de loader también)
                    const overlay = document.createElement('div');
                    overlay.className = 'fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-60'; // Fondo negro sólido
                    overlay.style.opacity = '0';
                    overlay.style.transition = 'opacity 0.3s ease-in-out';
                    overlay.style.pointerEvents = 'all';
                    overlay.style.userSelect = 'none';
                    overlay.tabIndex = -1;
                    overlay.innerHTML = `
                        <div class="relative w-full max-w-xs xs:max-w-sm sm:max-w-md mx-2 xs:mx-4">
                            <div class="overflow-hidden transition-all duration-500 ease-out transform scale-75 bg-white shadow-2xl rounded-xl xs:rounded-2xl success-modal">
                                <div class="relative px-3 xs:px-4 sm:px-6 py-4 xs:py-6 sm:py-8 overflow-hidden text-center bg-gradient-to-r from-green-400 to-green-600">
                                    <div class="relative mb-2 xs:mb-3 sm:mb-4">
                                        <div class="success-icon-container">
                                            <div class="flex items-center justify-center w-12 h-12 xs:w-16 xs:h-16 sm:w-20 sm:h-20 p-2 xs:p-3 sm:p-4 mx-auto bg-white rounded-full success-icon">
                                                <svg class="w-6 h-6 xs:w-8 xs:h-8 sm:w-10 sm:h-10 text-green-500 checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="mb-1 xs:mb-2 text-lg xs:text-xl sm:text-2xl font-bold text-white">¡Genial!</h3>
                                    <p class="text-xs xs:text-sm text-green-100">Comprobante enviado exitosamente</p>
                                </div>
                                <div class="px-3 xs:px-4 sm:px-6 py-3 xs:py-4 sm:py-6 text-center">
                                    <p class="mb-2 xs:mb-3 sm:mb-4 leading-relaxed text-gray-700 text-xs xs:text-sm">
                                        <span class="hidden xs:inline">Tu comprobante ha sido enviado correctamente. Verificaremos tu pago en las próximas 24 horas.</span>
                                        <span class="xs:hidden">Comprobante enviado. Verificación en 24h.</span>
                                    </p>
                                    <div class="flex items-center justify-center mb-2 xs:mb-3 sm:mb-4">
                                        <div class="w-6 h-6 xs:w-8 xs:h-8 border-2 xs:border-4 border-green-500 border-dashed rounded-full animate-spin"></div>
                                        <span class="ml-2 xs:ml-3 text-xs xs:text-sm text-gray-600">Redirigiendo...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Prevenir scroll y teclas
                    document.body.style.overflow = 'hidden';
                    window.addEventListener('keydown', this._blockKeys, true);

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
                                z-index: 10000;
                                animation: confetti 3s linear infinite;
                            }
                        `;
                        document.head.appendChild(styles);
                    }

                    document.body.appendChild(overlay);
                    setTimeout(() => {
                        overlay.style.opacity = '1';
                        const modal = overlay.querySelector('.success-modal');
                        modal.style.transform = 'scale(1)';
                    }, 10);

                    // Crear confeti
                    this.createConfetti();

                    // Redirigir forzadamente después de 3.2s (ligeramente más que la animación)
                    setTimeout(() => {
                        // Redirigir primero, el overlay se elimina automáticamente al recargar la página
                        if (this._lastRedirectUrl) {
                            window.location.href = this._lastRedirectUrl;
                        } else {
                            window.location.href = '{{ route('checkout.thank-you') }}';
                        }
                        // No quitamos el overlay ni restauramos el scroll aquí, para que la animación cubra hasta el cambio de vista
                    }, 3200);
                },

                // Bloquear teclas y scroll
                _blockKeys(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
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