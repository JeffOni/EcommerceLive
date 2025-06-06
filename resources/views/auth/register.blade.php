<x-guest-layout>
    <x-authentication-card :key="'register'" maxWidth="2xl">

        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                {{-- Nombre --}}
                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')"
                        required autofocus autocomplete="name" />
                </div>

                {{-- Apellido --}}
                <div>
                    <x-label for="last_name" value="{{ __('Last Name') }}" />
                    <x-input id="last_name" class="block w-full mt-1" type="text" name="last_name" :value="old('last_name')"
                        required autocomplete="last_name" />
                </div>

                {{-- Tipo de documento --}}
                <div>
                    <x-label for="document_type" value="{{ __('Document Type') }}" />
                    <x-select class="w-full mt-1" id="document_type" name="document_type" required>
                        <option value="" disabled selected>{{ __('Select Document Type') }}</option>
                        @foreach (App\Enums\TypeOfDocuments::cases() as $type)
                            <option value="{{ $type->value }}"
                                {{ old('document_type') == $type->value ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach

                    </x-select>
                </div>

                {{-- Número de documento --}}

                <div>
                    <x-label for="document_number" value="{{ __('Document Number') }}" />
                    <x-input id="document_number" class="block w-full mt-1" type="text" name="document_number"
                        :value="old('document_number')" required />
                </div>

                {{-- Email --}}

                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')"
                        required autocomplete="username" />
                </div>

                {{-- Teléfono --}}
                <div>
                    <x-label for="phone" value="{{ __('Phone') }}" />
                    <x-input id="phone" class="block w-full mt-1" type="text" name="phone" :value="old('phone')"
                        required />
                </div>
                {{-- password --}}
                <div>
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block w-full mt-1" type="password" name="password" required
                        autocomplete="new-password" />
                </div>

                {{-- Confirm Password --}}
                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block w-full mt-1" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                </div>

            </div>


            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div>
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' =>
                                        '<a target="_blank" href="' .
                                        route('terms.show') .
                                        '" class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">' .
                                        __('Terms of Service') .
                                        '</a>',
                                    'privacy_policy' =>
                                        '<a target="_blank" href="' .
                                        route('policy.show') .
                                        '" class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">' .
                                        __('Privacy Policy') .
                                        '</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4" name="Register" />
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
