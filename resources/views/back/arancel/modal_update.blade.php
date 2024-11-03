<div>
    <div class="row">
        <div class="col-lg-8">
           
            <form method="POST" action="{{route('back.aranceles.update',[$arancele->id])}}">
                @csrf
                @method('PUT')
                <div class="card mb-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">Modificar - Arancel- -  {{$arancele->arancel}}</div>

                            <div class="col fs-5 text-end">
                                <img src="{{ asset('img/icons/person.png') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <label for="arancel" class="col-md-4 col-form-label is_required text-end">Arancel :</label>

                            <div class="col-md-8">
                                <input id="arancel" name="arancel" type="text" 
                                placeholder="Nombre Arancel" class="form-control @error('arancel') is-invalid @enderror" 
                                value="{{$arancele->arancel}}" required style="text-transform:uppercase" >

                                @error('arancel')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <hr class="narrow" />

                        <div class="row mb-2">
                            <label for="montov" class="col-md-4 col-form-label is_required text-end">Al Venezolano :</label>

                            <div class="col-md-8">
                                <input id="montov" name="montov" type="numeric" placeholder="Monto en $"  class="form-control @error('montov') is-invalid @enderror" value="{{$arancele->monto_venezolano}}" required >

                                @error('montov')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="montoe" class="col-md-4 col-form-label is_required text-end">Al Extranjero :</label>

                            <div class="col-md-8">
                                <input id="montoe" name="montoe" type="numeric" placeholder="Monto en $" 
                                class="form-control @error('montoe') is-invalid @enderror" value="{{$arancele->monto_extranjero}}" required >

                                @error('montoe')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                <a class="btn btn-secondary text-white btn-sm" href="{{ route('back.aranceles.index') }}"" role=" button" tabindex="-1">
                                    <i class="bi bi-arrow-left-short"></i>
                                </a>
                            </div>

                            <div class="col text-end">
                                <button type="submit" class="btn btn-primary text-white btn-sm">Actualizar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <div class="col-lg-4">

            <div class="card mb-2">
                <div class="card-header bg-info text-white">
                    <div class="row">
                        <div class="col">Ayuda</div>

                        <div class="col fs-5 text-end"><i class="bi bi-question"></i></div>
                    </div>
                </div>

                <div class="card-body">
                    <ul>
                        <li>Los Campos con (<span class="is_required"></span> ) son obligatorios.</li>
                        <li>Todos los montos son en Dolares ($).</li>
                        <li>Click boton <strong>Actualizar</strong> para Grabar.</li>
                        <li> El boton <a class="btn btn-secondary text-white btn-sm" href="" >
                            <i class="bi bi-arrow-left-short"></i>
                        </a> Es para Cancelar </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>