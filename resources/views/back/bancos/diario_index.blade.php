@extends('layouts.back')

@section('title')
    &vert; Import
@endsection


@section('content')
    <div class="container">
        <div class="row">
            <div class="card mb-2">
                <div class="card-body"  style="padding-top: 0px; padding-bottom: 0px;">
                    <div class="d-flex justify-content-between p-1 mt-3">
                        <div id="ToolbarLeft"></div>
                        <div id="ToolbarCenter"></div>
                        <div id="ToolbarRight"></div>
                    </div>
                    <div class="row mb-2 mt-2">
                        <table id="sqltable" class="styled-table mb-3 mt-0" style="width: 100%">
                            <thead >
                                <tr class="titulo">
                                    <td class="text-center no-select no-export fs-5" colspan="8" data-dt-order="disable">
                                        MOVIMIENTOS DIARIOS - PERIODO: {{$periodo}} </td>
                                 </tr>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">F.Operación</th>
                                    <th scope="col">F.Valor</th>
                                    <th scope="col" >Código</th>
                                    <th scope="col" >Referencia</th>                                    
                                    <th scope="col" >Concepto</th>
                                    <th scope="col" >Importe</th>
                                    <th scope="col" >Oficina</th>
 
                                 </tr>
                            </thead>
                        </table>                     
                    </div>

                </div>    
            </div> 
        </div>
    </div>

@endsection


@push('scripts')
          {{-- DATATABLES BANCOS --}}
    <script type="module">

        let dtButtonsLeft = [];
        let dtButtonsRight = [];

        /* ------------------------------------------------------------------------ */
        let uploadButton = {
            className: 'btn-success',
            text: 'Cierre de Mes',
            titleAttr: 'Cierre de Mes',
            enabled: true,
            action: function(e, dt, node, config) 
            {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('back.bancos.upload')}}",
                    success: function(response) {
                        bootbox.dialog({
                            locale: 'nl',
                            title: 'Archivo Excel del Banco: Cierre de mes',
                            message: response,
                            size: 'lg',
                            onEscape: true,
                            backdrop: true
                        });
                    } //success
                }); // ajax               
            }  // action  

        } // uploadButton

        dtButtonsLeft.push(uploadButton)

        let diarioButton = {
            className: 'btn-success',
            text: 'Movimientos Díarios',
            titleAttr: 'Movimientos Díarios',
            enabled: true,
            action: function(e, dt, node, config) 
            {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('back.bancos.uploadDiario')}}",
                    success: function(response) {
                        bootbox.dialog({
                            locale: 'nl',
                            title: 'Archivo Excel del Banco: Movimientos Díarios',
                            message: response,
                            size: 'lg',
                            onEscape: true,
                            backdrop: true
                        });
                    } //success
                }); // ajax               
            }  // action  

        } // uploadButton

        dtButtonsRight.push(diarioButton)

        let conciliaButton = {
            className: 'btn-success',
            text: 'Conciliación Bancaria',
            titleAttr: 'Recargar Tabla',
            action: function(e, dt, node, config) {
                dt.state.clear();

                document.location.href = '{{ route('back.bancos.conciliacion')}}';
            }
        }
  /*       dtButtonsRight.push(conciliaButton)

        /* ------------------------------------------------------------------------ */
        let dtOverrideGlobals = {
            layout: {
                top2Start: null,
                top2End: null,    
                topEnd: 'search',
                topStart: {
                    pageLength: {
                        menu: [20, 25, 50, 75, 100, { label: 'Todos', value: -1 }]
                    },
                },
                topEnd: 'search',    
                bottomStart: 'info',
                bottomEnd: 'paging'
            },    
            pageLength: 25,        
            ajax: {
                url: "{{ route('back.bancos.ajax_diario') }}",
                data: function(d) {}
            },
            select: false,
            language: {
                url: "{{ asset('json/datatables/i18n/es-ES.json') }}",
                paginate: {
                    next: '<i class="fa fa-forward" title="próximo"></i>',
                    previous: '<i class="fa fa-backward" title="anterior"></i>',
                    first: '<i class="fa fa-step-backward" title="primero"></i>',
                    last: '<i class="fa fa-step-forward" title="último"></i>',
                }

            },
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return data.toString().padStart(4, '0');
                    }
                },
                {
                    data: 'f_operacion',
                    name: 'f_operacion',
                    type: "date",
                    className: "text-center ",
                    render: function(data) {
                        return (data) ? moment(data).utc().format('DD-MM-YYYY') : '';
                    },                    

                },
                {
                    data: 'f_valor',
                    name: 'f_valor',
                    type: "date",
                    className: "text-center ",
                    render: function(data) {
                        return (data) ? moment(data).utc().format('DD-MM-YYYY') : '';
                    },
                },
                {
                    data: 'codigo',
                    name: 'codigo',
                    type: 'text',
                    className: "text-center",
                },
                {
                    data: 'no_doc',
                    name: 'no_doc',
                    type: 'text',
                    className: "text-center",
                },                
                {
                    data: 'concepto',
                    name: 'concepto',
                    type: 'text',
                },                

                {
                    data: 'importe',
                    name: 'importe',
                    type: 'numeric',
                    className: "text-center ",
                    render: function(data) {
                        return ( data === 0 || data === 0.00 ) ? '' : $.fn.dataTable.render.number(null, null, 2 ,null, ' Bs.' ).display( data );
                    },
                },
                {
                    data: 'oficina',
                    name: 'oficina',
                    type: 'text',
                },     

            ],
                

            preDrawCallback: function(settings) {
                oTable.columns.adjust();
            },
        };
        /* ------------------------------------------- */

        let oTable = $('#sqltable').DataTable(dtOverrideGlobals);
        /* ------------------------------------------------------------------------ */

        new $.fn.dataTable.Buttons(oTable, {
            name: 'BtnGroupLeft',
            buttons: dtButtonsLeft
        });

        new $.fn.dataTable.Buttons(oTable, {
            name: 'BtnGroupRight',
            buttons: dtButtonsRight
        });

        oTable.buttons('BtnGroupLeft', null).containers().appendTo('#ToolbarLeft');

        oTable.buttons('BtnGroupRight', null).containers().appendTo('#ToolbarRight');





    </script>

@endpush


