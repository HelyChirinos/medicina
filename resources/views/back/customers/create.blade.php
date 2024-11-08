@extends('layouts.back')

@section('title')
    &vert; Customer
@endsection

@section('content')
    <form method="POST" action="{{ route('back.customers.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-lg-7">
                <div class="card mb-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">Customer - Add</div>

                            <div class="col fs-5 text-end">
                                <img src="{{ asset('img/icons/person.png') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-2">
                            <label for="customer_last_name" class="col-md-2 col-form-label">Last name :</label>

                            <div class="col-md-10">
                                <input id="customer_last_name" name="customer_last_name" type="text" class="form-control @error('customer_last_name') is-invalid @enderror js-identification"
                                    value="{{ old('customer_last_name') }}" autofocus>

                                @error('customer_last_name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-1">
                            <label for="customer_first_name" class="col-md-2 col-form-label">First name :</label>

                            <div class="col-md-10">
                                <input id="customer_first_name" name="customer_first_name" type="text" class="form-control @error('customer_first_name') is-invalid @enderror js-identification"
                                    value="{{ old('customer_first_name') }}">

                                @error('customer_first_name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <hr class="narrow" />

                        <div class="row mb-2">
                            <label for="company_name" class="col-md-2 col-form-label">Company :</label>

                            <div class="col-md-10">
                                <input id="company_name" name="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror js-identification"
                                    value="{{ old('company_name') }}">

                                @error('company_name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-1">
                            <label for="company_vat" class="col-md-2 col-form-label">VAT N° :</label>

                            <div class="col-md-10">
                                <input id="company_vat" name="company_vat" type="text" class="form-control @error('company_vat') is-invalid @enderror" value="{{ old('company_vat') }}">

                                @error('company_vat')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <hr class="narrow" />

                        <div class="row mb-2">
                            <label for="address_street" class="col-md-2 col-form-label">Street :</label>

                            <div class="col-md-6">
                                <input id="address_street" name="address_street" type="text" class="form-control @error('address_street') is-invalid @enderror" value="{{ old('address_street') }}">

                                @error('address_street')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <label for="address_street" class="col-md-1 col-form-label">N° :</label>

                            <div class="col-md-2">
                                <input id="address_number" name="address_number" type="text" class="form-control @error('address_number') is-invalid @enderror" value="{{ old('address_number') }}">

                                @error('address_number')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-secondary" id="btnMapFacturation" name="btnMapFacturation" title="Show address on map" tabindex="-1">
                                    <img src="{{ asset('img/icons/google-maps-location.png') }}" class="img-fluid mx-auto d-block" />
                                </button>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <label for="address_country" class="col-md-2 col-form-label">Country :</label>

                            <div class="col-md-3">
                                <select name="address_country" id="address_country" class="form-select">
                                    <option value="">seleccione ...</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->iso2 }}">{{ $country->name_en }}</option>
                                    @endforeach
                                </select>
                                @error('address_country')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <label for="address_country" class="col-md-1 col-form-label">PC :</label>

                            <div class="col-md-2">
                                <input id="address_postal_code" name="address_postal_code" type="text" class="form-control @error('address_postal_code') is-invalid @enderror"
                                    value="{{ old('address_postal_code') }}">

                                @error('address_postal_code')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <label for="address_country" class="col-md-1 col-form-label">Place :</label>

                            <div class="col-md-3">
                                <input id="address_place" name="address_place" type="text" class="form-control @error('address_place') is-invalid @enderror" value="{{ old('address_place') }}">

                                @error('address_place')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <hr class="narrow" />

                        <div class="row mb-2">
                            <label for="phone" class="col-md-2 col-form-label">Phone :</label>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>

                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label for="email" class="col-md-2 col-form-label">E-mail :</label>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                </div>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                <a class="btn btn-secondary text-white btn-sm" href="{{ route('back.customers.index') }}"" role=" button" tabindex="-1">
                                    <i class="bi bi-arrow-left-short"></i>
                                </a>
                            </div>

                            <div class="col text-end">
                                <button type="submit" class="btn btn-primary text-white btn-sm" tabindex="-1">Send</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">Delivery address</div>

                            <div class="col text-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm me-3" id="btnClear" name="btnClear" title="Clear delivery address" tabindex="-1">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnCopy" name="btnCopy" title="Copy customer address" tabindex="-1">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>

                            <div class="col fs-5 text-end">
                                <img src="{{ asset('img/icons/delivery.png') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-2">
                            <label for="delivery_address_street" class="col-md-2 col-form-label">Street :</label>

                            <div class="col-md-6">
                                <input id="delivery_address_street" name="delivery_address_street" type="text" class="form-control @error('delivery_address_street') is-invalid @enderror"
                                    value="{{ old('delivery_address_street') }}">

                                @error('delivery_address_street')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <label for="delivery_address_number" class="col-md-1 col-form-label">N° :</label>

                            <div class="col-md-2">
                                <input id="delivery_address_number" name="delivery_address_number" type="text" class="form-control @error('delivery_address_number') is-invalid @enderror"
                                    value="{{ old('delivery_address_number') }}">

                                @error('delivery_address_number')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-secondary" id="btnMapDelivery" name="btnMapDelivery" title="Show address on map" tabindex="-1">
                                    <img src="{{ asset('img/icons/google-maps-location.png') }}" class="img-fluid mx-auto d-block" />
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <label for="delivery_address_country" class="col-md-2 col-form-label">Country :</label>

                            <div class="col-md-3">
                                <select name="delivery_address_country" id="delivery_address_country" class="form-select">
                                    <option value="">Choose ...</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->iso2 }}">{{ $country->name_en }}</option>
                                    @endforeach
                                </select>
                                @error('delivery_address_country')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <label for="delivery_address_postal_code" class="col-md-1 col-form-label">PC :</label>

                            <div class="col-md-2">
                                <input id="delivery_address_postal_code" name="delivery_address_postal_code" type="text"
                                    class="form-control @error('delivery_address_postal_code') is-invalid @enderror" value="{{ old('delivery_address_postal_code') }}">

                                @error('delivery_address_postal_code')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <label for="delivery_address_place" class="col-md-1 col-form-label">Place :</label>

                            <div class="col-md-3">
                                <input id="delivery_address_place" name="delivery_address_place" type="text" class="form-control @error('delivery_address_place') is-invalid @enderror"
                                    value="{{ old('delivery_address_place') }}">

                                @error('delivery_address_place')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <small>Delivery address should only be used if different from facturation address above.</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card mb-2">
                    <div class="card-header bg-info text-white">
                        <div class="row">
                            <div class="col">Help</div>

                            <div class="col fs-5 text-end"><i class="bi bi-question"></i></div>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul>
                            <li>Specify the requested values.</li>
                            <li> Use the buttons
                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="bi bi-copy"></i>
                                </button> or
                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="bi bi-trash"></i>
                                </button> to <b>copy</b> the Customers address to the Delivery address or <b>clear</b> the Delivery address.
                            </li>
                            <li> Use the buttons
                                <button type="button" class="btn btn-outline-secondary" disabled>
                                    <img src="{{ asset('img/icons/google-maps-location.png') }}" class="img-fluid mx-auto d-block" />
                                </button> to <b>view</b> the addresses on Google Maps.
                            </li>
                            <li>Click the <strong>Send</strong> button to <b>save</b>.</li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">Existing simular customers</div>

                            <div class="col fs-5 text-end">
                                <img src="{{ asset('img/icons/persons.png') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="MyCustomers">
                            <!-- dynamically added -->
                        </div>
                    </div>

                    <div class="card-footer">
                        <small>
                            To avoid adding a customer more than once, after entering either <b>Last name</b>, <b>First name</b> or <b>Company</b>, existing simular customers will be shown above.<br />
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script type="module">
        /* -------------------------------------------------------------------------------------------- */
        $('#address_country').select2({
            theme: "bootstrap-5",
            minimumResultsForSearch: 31,
            placeholder: "Choose ...",
            language: "en",
            allowClear: true,
        }).on('change', function() {
            document.getElementById('address_postal_code').focus();
        }).on('select2:close', function() {
            document.getElementById('address_postal_code').focus();
        });

        $('#delivery_address_country').select2({
            theme: "bootstrap-5",
            minimumResultsForSearch: 31,
            placeholder: "Choose ...",
            language: "en",
            allowClear: true,
        }).on('change', function() {
            document.getElementById('delivery_address_postal_code').focus();
        }).on('select2:close', function() {
            document.getElementById('delivery_address_postal_code').focus();
        });
        /* -------------------------------------------------------------------------------------------- */
        $('#btnMapFacturation').click(function() {
            const href = "https://www.google.nl/maps/place/";

            const place = [
                ($('#address_street').val() ?? ''),
                ($('#address_number').val() ?? '') + ',',
                ($('#address_postal_code').val() ?? ''),
                ($('#address_place').val() ?? '')
            ].filter(Boolean).join("+");

            if (place > ',') {
                window.open(href + place).focus();
            } else {
                showToast({
                    type: 'info',
                    title: 'Show address ...',
                    message: 'No address available.',
                });
            }
        });
        /* ------------------------------------------- */
        $('#btnMapDelivery').click(function() {
            const href = "https://www.google.nl/maps/place/";

            const place = [
                ($('#delivery_address_street').val() ?? ''),
                ($('#delivery_address_number').val() ?? '') + ',',
                ($('#delivery_address_postal_code').val() ?? ''),
                ($('#delivery_address_place').val() ?? '')
            ].filter(Boolean).join("+");

            if (place > ',') {
                window.open(href + place).focus();
            } else {
                showToast({
                    type: 'info',
                    title: 'Show address ...',
                    message: 'No address availabler.',
                });
            }
        });
        /* -------------------------------------------------------------------------------------------- */
        $('#btnClear').click(function() {
            $('#delivery_address_street').val('');
            $('#delivery_address_number').val('');
            $('#delivery_address_country').val('').trigger('change');
            $('#delivery_address_postal_code').val('');
            $('#delivery_address_place').val('');
        });
        /* ------------------------------------------- */
        $('#btnCopy').click(function() {
            if ($('#address_street').val() ||
                $('#address_number').val() ||
                $('#address_country').find("option:selected").val() ||
                $('#address_postal_code').val() ||
                $('#address_place').val()
            ) {
                $('#delivery_address_street').val($('#address_street').val());
                $('#delivery_address_number').val($('#address_number').val());
                $('#delivery_address_country').val($('#address_country').find("option:selected").val()).trigger('change');
                $('#delivery_address_postal_code').val($('#address_postal_code').val());
                $('#delivery_address_place').val($('#address_place').val());
            } else {
                showToast({
                    type: 'info',
                    title: 'Address ...',
                    message: 'No address available.',
                });
            }
        });
        /* -------------------------------------------------------------------------------------------- */
        document.addEventListener('change', function(e) {
            if (e.target && $(e.target).attr('class').includes('js-identification')) {
                getCustomers(
                    document.querySelector('#customer_last_name').value,
                    document.querySelector('#customer_first_name').value,
                    document.querySelector('#company_name').value
                );
            }
        });
        /* -------------------------------------------------------------------------------------------- */
        function getCustomers(customerLastName, customerFirstName, companyName) {
            if (customerLastName || customerFirstName || companyName) {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('back.customers.getAlikes') }}",
                    data: {
                        customer_last_name: customerLastName,
                        customer_first_name: customerFirstName,
                        company_name: companyName,
                    },
                    success: function(response) {
                        document.getElementById('MyCustomers').innerHTML = response;
                    }
                });
            } else {
                document.getElementById('MyCustomers').innerHTML = '';
            }
        }
        /* -------------------------------------------------------------------------------------------- */
    </script>
@endpush
