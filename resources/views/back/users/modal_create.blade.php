<div class="modal fade" id="add_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="AddModalLabel">Agregar Usuario </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form_add"  >
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">Datos Personales</div>
                
                                        <div class="col fs-5 text-end">
                                            <img src="{{ asset('img/icons/person.png') }}" />
                                        </div>
                                    </div>
                                </div>
                
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <label for="nombre" class="col-md-3 col-form-label is_required">Nombre :</label>
                
                                        <div class="col-md-8">
                                            <input  name="nombre" type="text" class="form-control  @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required autofocus>
                
                                            @error('nombre')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="apellido" class="col-md-3 col-form-label is_required">Apellido :</label>
                
                                        <div class="col-md-8">
                                            <input  name="apellido" type="text" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" required>
                
                                            @error('apellido')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="cedula" class="col-md-3 col-form-label is_required">Cédula :</label>
                
                                        <div class="col-md-8">
                                            <input  name="cedula" type="text" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula') }}" required>
                
                                            @error('cedula')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                
                                    <div class="row mb-2">
                                        <label for="email" class="col-md-3 col-form-label is_required">E-mail :</label>
                
                                        <div class="col-md-8">
                                            <input  name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                
                                    <div class="row mb-2">
                                        <label for="telefono" class="col-md-3 col-form-label">teléfono :</label>
                
                                        <div class="col-md-8">
                                            <input  name="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" >
                
                                            @error('telefono')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                
                                    <div class="row">
                                        <label for="fecha_nac" class="col-md-3 col-form-label">Fecha Nac. :</label>
                
                                        <div class="col-md-8">
                                            <input  name="fecha_nac" type="date" class="form-control @error('fecha_nac') is-invalid @enderror" >
                
                                            @error('fecha_nac')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr class="narrow" />
                
                                    <div class="row">
                                        <label for="is_propietario" class="col-md-4 col-form-label">Administrador ?</label>
                
                                        <div class="col-md-3">
                                            <select class="form-select" name="is_propietario" >
                                                <option value="0" @if (old('is_propietario') == '0') selected @endif>No</option>
                                                <option value="1" @if (old('is_propietario') == '1') selected @endif>Si</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                
                            </div>
                
                    </div>
                
                    <div class="col-lg-6">
                        <div class="card mb-2">
                            <div class="card-header bg-primary text-white">
                                <div class="row">
                                    <div class="col">Claves de Ingreso</div>
                
                                    <div class="col fs-5 text-end"><i class="bi bi-unlock"></i></div>
                                </div>
                            </div>
                
                            <div class="card-body">
                
                                <div class="row mb-2">
                                    <label for="password" class="col-md-4 col-form-label is_required">Password :</label>
                
                                    <div class="col-md-7">
                                        <input  name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                
                                <div class="row">
                                    <label for="password_confirmation" class="col-md-4 col-form-label is_required">Confirm password :</label>
                
                                    <div class="col-md-7">
                                        <input  name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <hr class="narrow" />
                
                            </div>
                        </div>
                        <div id="help_card" class="card mb-2" style="display: block;">
                            <div   class="card-header bg-info text-white">
                                <div class="row">
                                    <div class="col">Ayuda</div>
                
                                    <div class="col fs-5 text-end"><i class="bi bi-question"></i></div>
                                </div>
                            </div>
                
                            <div  class="card-body">
                                <ul>
                                    <li>Los Campos con (<span class="is_required"></span> ) son obligatorios.</li>
                                    <li>Click boton <strong>Agregar</strong> para Grabar.</li>
                                    <li> El boton <a class="btn btn-secondary text-white btn-sm" href="" >
                                        <i class="bi bi-arrow-left-short"></i>
                                    </a> Es para Cancelar </li>
                                </ul>
                            </div>
                
                            
                        </div>
                        <div id="error_card" class="card mb-2 print-error-msg" style="display:none;">
                            <div   class="card-header bg-danger text-white">
                                <div class="row">
                                    <div class="col">Errores</div>
                
                                    <div class="col fs-5 text-end"><i class="bi bi-question"></i></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul id="lista_error">
                                </ul>
                            </div>
                
                            
                        </div>            
                    </div>
                </div>
                
            </div>
            <div class="modal-footer justify-content-between">
                <a class="btn btn-secondary text-white btn-sm" href="" data-bs-dismiss="modal" role="button" tabindex="-1">
                    <i class="bi bi-arrow-left-short"></i>
                </a>
                <button type="submit" id="botonSubmit" class="btn btn-primary text-white btn-sm">Agregar</button>
            </div>
        </form>    
      </div>
    </div>
  </div>
