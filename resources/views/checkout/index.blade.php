<x-app-layout>
    <div class="-mb-16 text-gray-700" x-data="{
        payment: 1
    }">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="col-span-1">
                <div class="lg:max-w-[40rem] px-4 lg:pr-8 lg:pl-8 sm:pl-6 ml-auto py-12">
                    <h1 class="mb-4 text-2xl font-bold">Pago</h1>
                    <div class="overflow-hidden border border-gray-200 rounded-lg shadow">
                        <ul class="divide-y divide-gray-200">
                            <li>
                                <label class="flex items-center p-4" for="">
                                    <input type="radio" name="payment" value="1" x-model="payment"
                                        class="mr-2">
                                    <span class="ml-2 text-lg font-semibold">Tarjeta de Crédito/Débito</span>
                                    <img class="h-6 ml-auto" src="{{ asset('img/credit-card.png') }}"
                                        alt="Tarjeta de Crédito/Débito">
                                </label>
                            </li>
                            <div class="p-4 text-sm text-gray-600">
                                <i class="fa-regular fa-credit-card"></i>
                                <p class="mt-2">Aceptamos tarjetas de crédito y débito. Puedes pagar de forma
                                    segura a través de nuestra plataforma.</p>

                            </div>
                            <li>
                                <label class="flex items-center p-4" for="">
                                    <input type="radio" name="payment" value="2" x-model="payment"
                                        class="mr-2">
                                    <span class="ml-2 text-lg font-semibold">Transferencia Bancaria</span>
                                    <img class="h-6 ml-auto" src="{{ asset('img/bank-transfer.png') }}"
                                        alt="Transferencia Bancaria">
                                </label>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="lg:max-w-[40rem] px-4 lg:pr-8 lg:pl-8 sm:pr-6 mr-auto py-12">
                    <h1 class="mb-4 text-2xl font-bold">Resumen de Compra</h1>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
