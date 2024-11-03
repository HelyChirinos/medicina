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

    <form method="POST" action="{{ route('back.recibos.store') }}">
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
    
                            <div class="row mb-2">
                                <label for="email" class="col-md-3 col-form-label ">E-mail :</label>
    
                                <div class="col-md-9">
                                    <input type="email" class="form-control" value="{{ $estudiante->email}}" readonly>
                                </div>
                            </div>
    
                            <div class="row mb-2">
                                <label for="telefono" class="col-md-3 col-form-label">teléfono :</label>
    
                                <div class="col-md-9">
                                    <input type="text" class="form-control" value="{{ $estudiante->telefono}}" readonly>
                                </div>
                            </div>
    
                        </div>

                    </div>
               
            </div>
    
            <div class="col-lg-8">
                <div class="card mb-2">
                    <div class="card-header bg-primary text-white text-center">
                        <label for="recibo" class="col-md-3 col-form-label ">Tipo de Recibo :</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="recibo" id="inlineRadio1" value="Matricula" onclick="MostrarMatricula()" required="required">
                            <label class="form-check-label" for="inlineRadio1">Matricula</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="recibo" id="inlineRadio2" value="Arancel" onclick="MostrarArancel()">
                            <label class="form-check-label" for="inlineRadio2">Arancel</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="recibo" id="inlineRadio3" value="Otro" onclick="MostrarOtro()" >
                            <label class="form-check-label" for="inlineRadio3">Otro </label>
                        </div>
                    </div>
                </div>
                
                <div id="matriculas" class="card mb-2" style="display: none;">
                    <div class="card-header bg-info text-white">
                        <div class="row text-center">
                           <div class="col">Matricula</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="matri" class="col-md-3 col-form-label is_required">Matrícula:</label>
                                    <div class="col-md-8">
                                        <select name="matricula" id="matri" class="form-select" >
                                            <option value="">Matrícula ...</option>
                                            @foreach ($matriculas as $matricula)
                                                    <option value="{{ $matricula->id }}">{{ $matricula->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="fecha_recibo" class="col-md-3 col-form-label is_required">Fecha:</label>
                                    <div class="col-md-8">
                                        <input id="fecha_matricula" name="fecha_matricula" type="date" class="form-control" > 
                                    </div>
                                </div>                              
                            </div>                                                        
                        </div>
                    </div>
                </div>
                <div id="aranceles" class="card mb-2"  style="display: none;">
                    <div class="card-header bg-info text-white">
                        <div class="row text-center">
                           <div class="col">Aranceles</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-1">
                            

                            <div class="col-md-6">
                                <select name="arancel[]" id="arancel" data-placeholder="Seleccione Arancel.."   style="margin-left: -40px;" multiple>
                                    <option value="">Seleccione Arancel</option>
                                    @foreach ($aranceles as $arancel)
                                        <option value="{{ $arancel->id }}">{{ $arancel->arancel }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <label for="no_pag" class="col-md-1 col-form-label">No.Pg.:</label>

                            <div class="col-md-1">
                                <input id="no_pag" name="no_pag" type="text" class="form-control" >
                            </div>

                            <label for="fecha_arancel" class="col-md-1 col-form-label">fecha :</label>

                            <div class="col-md-3">
                                <input id="fecha_arancel" name="fecha_arancel" type="date" class="form-control" >

                            </div>

                        </div>
                    </div>
                </div> 
                <div id="otros" class="card mb-2" style="display: none" >
                    <div class="card-header bg-info text-white">
                        <div class="row text-center">
                           <div class="col">Otros</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-8">
                            </div>
                            <label for="fecha" class="col-md-1 col-form-label">fecha :</label>
                            <div class="col-md-3">
                                <input id="fecha_recibo_otro" name="fecha_recibo_otro" type="date" class="form-control" >
                            </div>

                        </div>
                    </div>
                </div>                  
                <div id="comprobantes" class="card mb-2" style="display: none">

                    <div  class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <p class="text-center">Deposito</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-center">Fecha</p>
                            </div>                  
                            <div class="col-md-4">
                                <p class="text-center">Monto</p>
                            </div>                                          
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input id="deposito1" name="deposito[]" placeholder="No. Comprobante 1" type="text" class="form-control" >
                            </div>
                            <div class="col-md-4">
                                <input id="fecha1" name="fecha[]" type="date" class="form-control" >
                            </div>                  
                            <div class="col-md-4">
                                <input id="monto1" name="monto[]" placeholder="Monto 1" type="numeric" class="form-control" >
                            </div>                                          
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input id="deposito2" name="deposito[]" placeholder="No. Comprobante 2" type="text" class="form-control" >
                            </div>
                            <div class="col-md-4">
                                <input id="fecha2" name="fecha[]" type="date" class="form-control">
                            </div>                  
                            <div class="col-md-4">
                                <input id="monto2" name="monto[]" placeholder="Monto 2" type="numeric" class="form-control" >
                            </div>                                          
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input id="deposito3" name="deposito[]" placeholder="No. Comprobante 3" type="text" class="form-control" >
                            </div>
                            <div class="col-md-4">
                                <input id="fecha3" name="fecha[]" type="date" class="form-control">
                            </div>                  
                            <div class="col-md-4">
                                <input id="monto3" name="monto[]" placeholder="Monto 3" type="numeric" class="form-control" >
                            </div>                                          
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input id="deposito4" name="deposito[]" placeholder="No. Comprobante 4" type="text" class="form-control" >
                            </div>
                            <div class="col-md-4">
                                <input id="fecha4" name="fecha[]" type="date" class="form-control">
                            </div>                  
                            <div class="col-md-4">
                                <input id="monto4" name="monto[]" placeholder="Monto 4" type="numeric" class="form-control" >
                            </div>                                          
                        </div>                                          
                    </div>
                </div>
                <div id="otros_comprobantes" class="card mb-2" style="display: none">
                    <div  class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-4">
                                <p class="text-center">Deposito</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-center">Fecha</p>
                            </div>                  
                            <div class="col-md-4">
                                <p class="text-center">Monto</p>
                            </div>                                          
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input id="deposito_otro" name="deposito_otro" placeholder="No. Comprobante" type="text" class="form-control" >
                            </div>
                            <div class="col-md-4">
                                <input id="fecha_otro" name="fecha_otro" type="date" class="form-control" >
                            </div>                  
                            <div class="col-md-4">
                                <input id="monto_otro" name="monto_otro" placeholder="Monto" type="numeric" class="form-control" >
                            </div>                                          
                        </div>
                        <div class="row mb-2 NT-2">
                            <label for="concepto" class="col-md-3 col-form-label">Concepto :</label>

                            <div class="col-md-9">
                                <textarea name="concepto" id="concepto" placeholder="INGRESE CONCEPTO" cols="50" rows="5" style="background-color: var(--bs-body-bg);"></textarea>
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

        <script type="text/javascript">
            function limpiar() {
                document.getElementById('matriculas').style.display = "none";
                document.getElementById('aranceles').style.display = "none";
                document.getElementById('otros').style.display = "none";
                document.getElementById('comprobantes').style.display = "none";
                document.getElementById('otros_comprobantes').style.display = "none";
            }
             
            function MostrarArancel(){
                limpiar();
                div = document.getElementById('aranceles');
                div.style.display = 'block';
                div = document.getElementById('comprobantes');
                div.style.display = 'block';  
                PonerRequiredArancel();
                              
            }
        
            function MostrarMatricula(){
                limpiar();
                div = document.getElementById('matriculas');
                div.style.display = 'block';
                div = document.getElementById('comprobantes');
                div.style.display = 'block';      
        
            }
            function MostrarOtro(){
                limpiar();
                div = document.getElementById('otros');
                div.style.display = 'block';
                div = document.getElementById('otros_comprobantes');
                div.style.display = 'block';      
            }

           function PonerRequiredArancel() {
            $('#arancel').attr("required", true);
            $("[name='fecha_arancel']").attr("required", true);
           }
        
        </script>


    <script type="module">
        $('#arancel').select2({

        });           
    </script>

@endsection

