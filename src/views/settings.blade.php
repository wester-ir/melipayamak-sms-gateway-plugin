@section('title', __('Plugin Settings: :name', ['name' => $pluginObj->getDisplayName()]))

<x-admin-layout>
    <form action="{{ route('admin.plugins.plugin.settings.update', $plugin) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-content form">
                <div class="form-row">
                    <div class="form-control" data-danger="{{ as_string($errors->has('username')) }}">
                        <label for="username" after="{{ __('Required') }}">{{ __('Username') }}</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $options->get('username')) }}" class="default ltr-direction">
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <div class="form-control" data-danger="{{ as_string($errors->has('password')) }}">
                        <label for="password" after="{{ __('Required') }}">{{ __('Password') }}</label>
                        <input type="text" id="password" name="password" value="{{ old('password', $options->get('password')) }}" class="default ltr-direction">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="form-control" data-danger="{{ as_string($errors->has('number')) }}">
                        <label for="number" after="{{ __('Required') }}">{{ __('Dedicated Number') }}</label>
                        <input type="text" id="number" name="number" value="{{ old('number', $options->get('number')) }}" class="default ltr-direction">
                        <x-input-error :messages="$errors->get('number')" class="mt-2" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-content form">
                <div class="form-row">
                    <div class="form-control" data-danger="{{ as_string($errors->has('verification_pattern_id')) }}">
                        <label for="verification_pattern_id" after="{{ __('Required') }}">{{ __('MelipayamakSMSGateway::attributes.verification_pattern_id') }}</label>
                        <input type="text" id="verification_pattern_id" name="verification_pattern_id" value="{{ old('verification_pattern_id', $options->get('verification_pattern_id')) }}" class="default ltr-direction">
                        <x-input-error :messages="$errors->get('verification_pattern_id')" class="mt-2" />
                    </div>

                    <div class="form-control" data-danger="{{ as_string($errors->has('order_paid_pattern_id')) }}">
                        <label for="order_paid_pattern_id" after="{{ __('Required') }}">{{ __('MelipayamakSMSGateway::attributes.order_paid_pattern_id') }}</label>
                        <input type="text" id="order_paid_pattern_id" name="order_paid_pattern_id" value="{{ old('order_paid_pattern_id', $options->get('order_paid_pattern_id')) }}" class="default ltr-direction">
                        <x-input-error :messages="$errors->get('order_paid_pattern_id')" class="mt-2" />
                    </div>
    
                    <div class="form-control" data-danger="{{ as_string($errors->has('order_fulfilled_pattern_id')) }}">
                        <label for="order_fulfilled_pattern_id" after="{{ __('Required') }}">{{ __('MelipayamakSMSGateway::attributes.order_fulfilled_pattern_id') }}</label>
                        <input type="text" id="order_fulfilled_pattern_id" name="order_fulfilled_pattern_id" value="{{ old('order_fulfilled_pattern_id', $options->get('order_fulfilled_pattern_id')) }}" class="default ltr-direction">
                        <x-input-error :messages="$errors->get('order_fulfilled_pattern_id')" class="mt-2" />
                    </div>
                </div>

                <div class="form-control" data-danger="{{ as_string($errors->has('order_paid_pattern_args')) }}">
                    <label for="order_paid_pattern_args" after="{{ __('Required') }}">{{ __('MelipayamakSMSGateway::attributes.order_paid_pattern_args') }}</label>
                    <input type="text" id="order_paid_pattern_args" name="order_paid_pattern_args" value="{{ old('order_paid_pattern_args', $options->get('order_paid_pattern_args')) }}" class="default ltr-direction">
                    <x-input-error :messages="$errors->get('order_paid_pattern_args')" class="mt-2" />
                </div>

                <div class="form-control" data-danger="{{ as_string($errors->has('order_fulfilled_pattern_args')) }}">
                    <label for="order_fulfilled_pattern_args" after="{{ __('Required') }}">{{ __('MelipayamakSMSGateway::attributes.order_fulfilled_pattern_args') }}</label>
                    <input type="text" id="order_fulfilled_pattern_args" name="order_fulfilled_pattern_args" value="{{ old('order_fulfilled_pattern_args', $options->get('order_fulfilled_pattern_args')) }}" class="default ltr-direction">
                    <x-input-error :messages="$errors->get('order_fulfilled_pattern_args')" class="mt-2" />
                </div>
            </div>
        </div>

        <button class="btn btn-primary mt-5">{{ __('Save') }}</button>
    </form>
</x-admin-layout>
