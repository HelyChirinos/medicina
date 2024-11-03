@extends('layouts.back')

@section('title')
    &vert; Recibos
@endsection

@section('content')

@push('styles')

<style>

.select2.select2-container {
  width: 100% !important;
}

.select2.select2-container .select2-selection {
  border: 1px solid #ccc;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  height: 34px;
  margin-bottom: 15px;
  outline: none !important;
  transition: all .15s ease-in-out;
}

.select2.select2-container .select2-selection .select2-selection__rendered {
  color: #333;
  line-height: 32px;
  padding-right: 33px;
}

.select2.select2-container .select2-selection .select2-selection__arrow {
  background: #f8f8f8;
  border-left: 1px solid #ccc;
  -webkit-border-radius: 0 3px 3px 0;
  -moz-border-radius: 0 3px 3px 0;
  border-radius: 0 3px 3px 0;
  height: 32px;
  width: 33px;
}

.select2.select2-container.select2-container--open .select2-selection.select2-selection--single {
  background: #f8f8f8;
}

.select2.select2-container.select2-container--open .select2-selection.select2-selection--single .select2-selection__arrow {
  -webkit-border-radius: 0 3px 0 0;
  -moz-border-radius: 0 3px 0 0;
  border-radius: 0 3px 0 0;
}

.select2.select2-container.select2-container--open .select2-selection.select2-selection--multiple {
  border: 1px solid #34495e;
}

.select2.select2-container .select2-selection--multiple {
  height: auto;
  min-height: 34px;
  background-color: var(--bs-body-bg)
}

.select2.select2-container .select2-selection--multiple .select2-search--inline .select2-search__field {
  margin-top: 0;
  height: 32px;

}

.select2.select2-container .select2-selection--multiple .select2-selection__rendered {
  display: block;
  padding: 0 4px;
  line-height: 29px;
}

.select2.select2-container .select2-selection--multiple .select2-selection__choice {
  background-color:#274D8F;
  border: 1px solid #ccc;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  margin: 4px 4px 0 0;
  padding: 0 6px 0 22px;
  height: 24px;
  line-height: 24px;
  font-size: 12px;
  color:white;
  position: relative;
}

.select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
  position: absolute;
  top: 0;
  left: 0;
  height: 22px;
  width: 22px;
  margin: 0;
  text-align: center;
  color: #e74c3c;
  font-weight: bold;
  font-size: 16px;
}

.select2-container .select2-dropdown {
  background: transparent;
  border: none;
  margin-top: -5px;
}

.select2-container .select2-dropdown .select2-search {
  padding: 0;
}

.select2-container .select2-dropdown .select2-search input {
  outline: none !important;
  border: 1px solid #34495e !important;
  border-bottom: none !important;
  padding: 4px 6px !important;
  background: blue;
}

.select2-container .select2-dropdown .select2-results {
  padding: 0;
}

.select2-container .select2-dropdown .select2-results ul {
  background: #fff;
  border: 1px solid #34495e;
}

.select2-container .select2-dropdown .select2-results ul .select2-results__option--highlighted[aria-selected] {
  background-color: #3498db;
}



</style>

@endpush

<div class="card">

    <div class="card-header header-primary text-center fs-5">Crear Recibo</div> 

    <form id="form_recibo" method="POST" action="">
        @csrf
        <div class="row">
            <div class="col-lg-4">

                    <div class="card mb-2">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">Datos del Estudiante</div>
    
                                <div class="col fs-5 text-end">
                                    <img src="{{ asset('img/icons/person.png') }}" />
                                </div>
                            </div>
                        </div>
    
                        <div class="card-body">
     
                            <div class="row mb-2">
                                <input type="hidden" name="estudiante_id" value="{{ $estudiante->id}}" >
                                <label for="tipo_doc" class="col-md-3 col-form-label">Doc.:</label>
    
                                <div class="col-md-3">
                                    <input id="tipo_doc" name="tipo_doc" type="text"  class="form-control" value="{{ $estudiante->tipo_doc}}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <input id="no_doc" name="no_doc" type="text" class="form-control" value="{{ $estudiante->no_doc}}" readonly>
                                </div>
    
                            </div>     
                      
                            <div class="row mb-2">
                                <label for="nombre" class="col-md-3 col-form-label">Nombre :</label>
    
                                <div class="col-md-9">
                                    <input id="nombre" name="nombre" type="text" class="form-control" value="{{ $estudiante->nombre}}" readonly>
                                </div>
                            </div>
                            <hr class="narrow" />
                            <div class="row mb-2">
                                <label for="programa" class="col-md-3 col-form-label">Programa:</label>
    
                                <div class="col-md-9">
                                    <input  type="text" class="form-control" value="{{ $estudiante->programa->programa}}" readonly>
                                </div>

                            </div>     
                            <div class="row mb-2">
                                <label for="mencion" class="col-md-3 col-form-label">Mención :</label>
    
                                <div class="col-md-9">
                                    <input  type="text" class="form-control" value="{{ $estudiante->mencion->mencion}}" readonly>
                                </div>
                            </div>    
    
                            <hr class="narrow" />
    
                            <div class="row mb-2" id="showEmail" style="display: block;">
                                <label for="email" class="col-md-3 col-form-label ">E-mail :</label>
    
                                <div class="col-md-9">
                                    <input type="email" class="form-control" value="{{ $estudiante->email}}" readonly>
                                </div>
                            </div>
    
                            <div class="row mb-2" id="showTelefono" style="display: block">
                                <label for="telefono" class="col-md-3 col-form-label">teléfono :</label>
    
                                <div class="col-md-9">
                                    <input type="text" class="form-control" value="{{ $estudiante->telefono}}" readonly>
                                </div>
                            </div>
    
                        </div>

                    </div>
                    <div id="error_card" class="card mb-2 print-error-msg" style="display: none;">
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
    
            <div class="col-lg-8">

                

                <div id="aranceles" class="card mb-2"  >
                    <div class="card-header bg-primary text-white text-center">
                        <div class="row text-center">
                           <div class="col">Datos del Recibo</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-1">
                            
                            <div class="col-md-6">
                                        <select name="matricula[]" id="matri" data-placeholder="Seleccione Matrícula.."   multiple >
                                            @foreach ($matriculas as $matricula)
                                                    <option value="{{ $matricula->id }}" {{(in_array($matricula->id, $a_matricula)) ? 'selected':'' }} >{{ $matricula->nombre }}</option>
                                            @endforeach
                                        </select>
                            </div>
                            <div class="col-md-6">
                                <select name="arancel[]" id="arancel" data-placeholder="Seleccione Arancel.."   multiple>
                                    <option value="">Seleccione Arancel</option>
                                    @foreach ($aranceles as $arancel)
                                        <option value="{{ $arancel->id }}" {{(in_array($arancel->id, $a_arancel)) ? 'selected':'' }} >{{ $arancel->arancel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">    
                            <label for="concepto:" class="col-md-2 col-form-label">Concepto:</label>
                            <div class="col-md-5">
                                <input id="concepto" name="concepto" type="text" class="form-control" style="margin-left:-30px;" value="{{$concepto}}" >
                            </div>

                            <label for="fecha_recibo" class="col-md-2 col-form-label">Fecha Recibo :</label>

                            <div class="col-md-3">
                                <input id="fecha_recibo" name="fecha_recibo" type="date" class="form-control"  value="{{$fecha_r}}" required >

                            </div>

                        </div>
                    </div>
                </div> 
          
                <div id="comprobantes" class="card mb-2">

                    <div  class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <p class="text-center m-0">Deposito</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-center m-0">Fecha</p>
                            </div>                  
                            <div class="col-md-4">
                                <p class="text-center m-0">Monto</p>
                            </div>                                          
                        </div>
                        @php
                            $long = count($depositos);

                        @endphp
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input id="deposito1" name="deposito[]" placeholder="No. Comprobante 1" type="text" class="form-control" value="{{$depositos[0]['no_deposito']}}" required >
                            </div>
                            <div class="col-md-4">
                                <input id="fecha1" name="fecha[]" type="date" class="form-control" value="{{\Carbon\Carbon::parse($depositos[0]['fecha'])->format('Y-m-d')  }}" required>
                            </div>                  
                            <div class="col-md-4">
                                <input id="monto1" name="monto[]" placeholder="Monto 1" type="numeric" class="form-control" value="{{$depositos[0]['monto']}}"  required >
                            </div>                                          
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input id="deposito2" name="deposito[]" placeholder="No. Comprobante 2" type="text" class="form-control" value="{{($long>1) ? $depositos[1]['no_deposito'] : ''}}"  >
                            </div>
                            <div class="col-md-4">
                                <input id="fecha2" name="fecha[]" type="date" class="form-control" value="{{($long>1) ? \Carbon\Carbon::parse($depositos[1]['fecha'])->format('Y-m-d')  : ''}}">
                            </div>                  
                            <div class="col-md-4">
                                <input id="monto2" name="monto[]" placeholder="Monto 2" type="numeric" value="{{($long>1) ? $depositos[1]['monto'] : ''}}" class="form-control" >
                            </div>                                          
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input id="deposito3" name="deposito[]" placeholder="No. Comprobante 3" type="text" class="form-control"  value="{{($long>2) ? $depositos[2]['no_deposito'] : ''}}" >
                            </div>
                            <div class="col-md-4">
                                <input id="fecha3" name="fecha[]" type="date" class="form-control"  value="{{($long>2) ? \Carbon\Carbon::parse($depositos[2]['fecha'])->format('Y-m-d')  : ''}}">
                            </div>                  
                            <div class="col-md-4">
                                <input id="monto3" name="monto[]" placeholder="Monto 3" type="numeric" class="form-control" value="{{($long>2) ? $depositos[2]['monto'] : ''}}"  >
                            </div>                                          
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input id="deposito4" name="deposito[]" placeholder="No. Comprobante 4" type="text" class="form-control"  value="{{($long>3) ? $depositos[3]['no_deposito'] : ''}}">
                            </div>
                            <div class="col-md-4">
                                <input id="fecha4" name="fecha[]" type="date" class="form-control" value="{{($long>3) ? \Carbon\Carbon::parse($depositos[3]['fecha'])->format('Y-m-d')  : ''}}">
                            </div>                  
                            <div class="col-md-4">
                                <input id="monto4" name="monto[]" placeholder="Monto 4" type="numeric" class="form-control" value="{{($long>3) ? $depositos[3]['monto'] : ''}}" >
                            </div>                                          
                        </div>                                          
                    </div>
                </div>
           
            </div>
        </div>
   
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <a class="btn btn-secondary text-white btn-sm" href="{{ route('back.recibos.index') }}" role=" button" tabindex="-1">
                        <i class="bi bi-arrow-left-short"></i>
                    </a>
                </div>

                <div class="col text-end">
                    <button type="submit" class="btn btn-primary text-white btn-sm">Validar</button>
                </div>
            </div>
        </div>
    </form>
</div>


    <script type="module">
        $('#matri').select2({
                      
        });  

        $('#arancel').select2({
           
        });  
        


        $('#form_recibo').submit(function(e) 
        {
            e.preventDefault();

            let datos = $('#form_recibo').serialize();
            $.ajax({
                url: "{{ route('back.recibos.validaRecibo') }}",
                method: 'get',
                data:  datos,
                success: function(result)
                {
              
                   console.log('recibo:'+result.recibo_id);
                   let recibo_id = result.recibo_id;
                   var url_locate = "{{ route('back.recibos.showVerificado', 'id') }}".replace("id", recibo_id);
                   window.location.href = url_locate;
                //         $("#help_card").css("display", "block");
                //         $("#error_card").css("display", "none");
                //         $('#lista_error').html('');                  
                //         $('#add_modal').modal('hide');
                //         resetAddForm();
                //         var url_mensaje = '{{ route("back.users.message","Nuevo") }}';
                //         window.location.href=url_mensaje;                    
                 },
                error: function(result) 
                {
                if (result.status == 422) {
                    console.log(result.responseJSON.error);
                    $("#showEmail").css("display", "none");
                    $("#showTelefono").css("display", "none");
                    $('#form_recibo').find(".print-error-msg").find("ul").html('');
                    $('#form_recibo').find(".print-error-msg").css('display','block');
                    $.each( result.responseJSON.error, function( key, value ) {
                            $('#form_recibo').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                    });
                } else {
                    alert('Tipo de Error:' + result.status+' '+result.responseText);

                }
                }        
            });
        });


        
    </script>

@endsection

