@extends('layouts.back')

@section('title')
    &vert; Recibos
@endsection

@section('content')
    <div class="card mb-2">
        <div class="card-header d-print-none">
            <div class="row">
                <div class="col-11 text-center fs-5">Recibos</div>

                <div class="col fs-5 text-end">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="d-flex justify-content-between p-1 mt-2">
                <div id="ToolbarLeft"></div>
                <div id="ToolbarCenter"></div>
                <div id="ToolbarRight"></div>
            </div>
            <div class="row">
            <div class="col">
                <table id="sqltable" class="table table-bordered table-striped table-sm table-hover dataTable" style="width: 80%">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col" width="4%">ID</th>
                            <th scope="col" width="6%">Nº Doc.</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Programa</th>
                            <th scope="col">Mención</th>
                            <th scope="col">Concepto</th>
                            <th scope="col" width="6%">Fecha</th>
                            <th scope="col" width="4%" >Estado </th>
                            <th scope="col"></th>                     

                         </tr>
                    </thead>
                </table>
            </div>

        </div>
        </div>
    </div>
@endsection

@push('scripts')
     
    <script type="module">
        var url_locate = location.protocol+'//'+location.host;
        url_locate = "{{asset('/json/datatables/i18n/es-ES.json')}}";
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
            url: url_locate,
            },
        });        
        let DolarHoy = '{{dolarDelDia ()}}';
        console.log('Dolar :'+ DolarHoy);
        /* ------------------------------------------------------------------------ */
        let dtButtonsLeft = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        let dtButtonsCenter = [];
        let dtButtonsRight = [];
        /* ------------------------------------------------------------------------ */

        let createButton = {
            className: 'btn-success',
            text: '<i class="bi bi-plus"></i>',
            titleAttr: 'Agregar',
            enabled: true,
            action: function(e, dt, node, config) 
            {

                if (DolarHoy) {
                    bootbox.prompt({
                        size: 'small',
                        title: 'Estudiante',
                        placeholder:'No. de Cédula o Pasaporte',
                        callback: function(result) 
                        {
                        if (result != '' && result !== null) {
                            console.log('Result:'+result);
                            bootbox.hideAll();
                            let no_documento = result;
                            $.ajax({
                                method: 'GET',
                                url: "{{ route('back.recibos.validaEstudiante') }}",
                                data: {
                                    id: result,
                                },
                                success: function(response) {
                                    
                                    if (response.encontrado==true){
                                        let id = response.id;
                                        console.log('href:'+window.location.href);
    
                                        window.location.href = "recibos/create/"+id;
                                    } else {
                                        bootbox.confirm({
                                            title: 'No Hay Registro del Estudiante',
                                            message: '<p style= "color:white; font-size: 14px;  text-align: center;">Estudiante con no. de Doc.: '+no_documento+' no esta registrado, Desea Agregarlo? </p>',
                                            buttons: {
                                                confirm: {
                                                    label: 'Si',
                                                    className: 'btn-primary'
                                                },
                                                cancel: {
                                                    label: 'No',
                                                    className: 'btn-secondary'
                                                }
                                            },
                                            callback: function(confirmed) {
                                                if (confirmed) {
                                                    $.ajax({
                                                        method: 'GET',
                                                        url: "{{ route('back.estudiantes.create')}}",
                                                        success: function(response) {
                                                        
                                                            bootbox.dialog({
                                                                locale: 'nl',
                                                                title: 'Nuevo Estudiante',
                                                                message: response,
                                                                size: 'xl',
                                                                onShown: function(e) {
                                                                    document.getElementById("ruta").value = "recibo";
                                                                    document.getElementById("no_doc").value = no_documento;
                                                                },
                                                                onEscape: true,
                                                                backdrop: true
                                                            });
                                                        } //success
                                                    }); // ajax   
                                                    
                                                }
                                            }
                                        });
                                    } // ELSE

                                },
                                error: function(result) {
                                    alert('Tipo de Error:' + result.status+' '+result.responseText);
                                }
                            });                             
                        }
                        }
                    });
                } else { 
                    bootbox.alert('<p style= "color:#EC7063; font-size: 16px;  text-align: center;">Para poder continuar debe Registrar $ la Tasa BCV del Día</p>');
                }  

            }  // action  

        } // createButton

        dtButtonsCenter.push(createButton)
    
        let clearButton = {
            className: 'btn-secondary',
            text: '<i class="bi bi-arrow-counterclockwise"></i>',
            titleAttr: 'Recargar Tabla',
            action: function(e, dt, node, config) {
                dt.state.clear();

                document.location.href = '{{ route('back.recibos.index')}}';
            }
        }



        let PetiButton = {
            className: 'btn-info',
            text: '<i class="fa-regular fa-hands-praying fa-fw"></i>',
            titleAttr: 'Recargar Tabla',
            action: function(e, dt, node, config) {
                bootbox.prompt({
                size: 'small',
                title: 'Petitorio',
                placeholder:'Ingrese Código de Aprobación',
                callback: function(result){
                    if (result === null) {
                        return;} 
                    if (result === '') {
                        return false;
                    }
                    let codigo = result;
                    $.ajax({
                                method: 'GET',
                                url: "{{ route('back.recibos.validaPetitorio') }}",
                                data: {
                                    codigo: result,
                                },
                                success: function(response) {
                                    var my_obj_str = JSON.stringify(response);
                                    console.log('response: '+my_obj_str);
                                    console.log('response: '+response.aprobado);
                                    if (response.aprobado==true){
                                        let tmp_recibo_id = response.id;
                                        window.location.href = "recibos/verificado/"+tmp_recibo_id;
                                    } else {
                                        bootbox.alert('<p style= "color:#EC7063; font-size: 16px; font-weight: bold; text-align: center;">El Código no existe ó no ha sido APROBADO</p>');

                                    } // ELSE

                                },
                                error: function(result) {
                                    alert('Tipo de Error:' + result.status+' '+result.responseText);
                                }
                            });      

                }

                
                });
            }
        }

        dtButtonsRight.push(PetiButton)

        let deleteButton = {
            extend: 'selectedSingle',
            className: 'btn-danger selectOne',
            text: '<i class="bi bi-trash"></i>',
            titleAttr: 'Anular Recibo',
            enabled: false,
            url: "{{route('back.recibos.Destroy') }}",
            action: function(e, dt, node, config) {
                const id = dt.row({
                    selected: true
                }).data().id;
                    bootbox.confirm({
                        title: 'Anular Recibo: ' + id,
                        message: '<div class="alert alert-danger" role="alert">Seguro de Anular El Recibo?</div>',
                        buttons: {
                            confirm: {
                                label: 'Si',
                                className: 'btn-primary'
                            },
                            cancel: {
                                label: 'No',
                                className: 'btn-secondary'
                            }
                        },
                        callback: function(confirmed) {
                            if (confirmed) {
                            
                                $.ajax({
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        id: id,
                                          _method: 'DELETE'
                                    },
                                    success: function(response) {
                                        dt.draw();

                                        showToast({
                                            type: 'success',
                                            title: 'Anulado ...',
                                            message: 'El Recibo ha sido Anulado.',
                                        });
                                    },
                                    error: function(result) {
                                        alert('Tipo de Error:' + result.status+' '+result.responseText);
                                    }
                                });
                            }
                        }
                    });
                
            }
        }
        dtButtonsCenter.push(deleteButton)
        /* ------------------------------------------------------------------------ */

        let dtOverrideGlobals = {
            ajax: {
                url: "{{ route('back.recibos.index') }}",
                data: function(d) {}
            },
            language: {
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
                    data: 'no_doc',
                    name: 'no_doc',
                    type: "text",
                    className: "text-center",

                },                                
                {
                    data: 'nombre',
                    name: 'nombre',
                    type: "text",
                    className: "text-center",

                },
                {
                    data: 'programa',
                    name: 'programa',
                    type: "text",                    
                    className: "text-center ",
                },
                {
                    data: 'mencion',
                    name: 'mencion',
                    type: "text",                    
                    className: "text-center",
                },
                {
                    data: 'concepto',
                    name: 'concepto',
                    type: "text",                    
                    className: "text-center",

                },
                {
                    data: 'fecha',
                    name: 'fecha',
                    type: "date",                    
                    className: "text-center",
                    render: function(data) {
                        return moment(data).utc().format('DD-MM-YYYY');                    
                    }
                },                    
                {
                    data: 'status',
                    name: 'status',
                    type: "text",
                    className: "text-center ",
                    render: function(data,td) {
                        if (data == 'ANULADO'){
                            
                            return '<span style="color:' + 'red' + '">' + data + '</span>';
                        } else {
                            return data;
                        };
                                            
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sortable: false,
                    className: 'dt-center print-recibo no-select no-export',
                    defaultContent: '<i class="fa-light fa-print"></i>',
                    orderable: false
                },

            ],
            select: {
                selector: 'td:not(.no-select)',
            },
            ordering: true,
            order: [
                [0, 'desc']
            ],
            preDrawCallback: function(settings) {
                oTable.columns.adjust();
            },
        };
        /* ------------------------------------------- */

        let oTable = $('#sqltable').DataTable(dtOverrideGlobals);
        oTable.order([0, 'desc']).draw();
        /* ------------------------------------------------------------------------ */
        new $.fn.dataTable.Buttons(oTable, {
            name: 'BtnGroupLeft',
            buttons: dtButtonsLeft
        });
        new $.fn.dataTable.Buttons(oTable, {
            name: 'BtnGroupCenter',
            buttons: dtButtonsCenter
        });
        new $.fn.dataTable.Buttons(oTable, {
            name: 'BtnGroupRight',
            buttons: dtButtonsRight
        });

        oTable.buttons('BtnGroupLeft', null).containers().appendTo('#ToolbarLeft');
        oTable.buttons('BtnGroupCenter', null).containers().appendTo('#ToolbarCenter');
        oTable.buttons('BtnGroupRight', null).containers().appendTo('#ToolbarRight');
        /* ------------------------------------------------------------------------ */
        oTable.on('select deselect', function(e, dt, type, indexes) {
            const selectedRows = oTable.rows({
                selected: true
            }).count();

            oTable.buttons('.selectOne').enable(selectedRows === 1);
            oTable.buttons('.selectMultiple').enable(selectedRows > 0);
        });


        $('#sqltable tbody').on('click', '.print-recibo', function() {
            const data = oTable.row($(this).closest('tr')).data();
            console.log(data.id);
            let url= "{{ route('back.recibos.Pdf', 'id') }}".replace("id", data.id);
            console.log(url);
            let newTab = document.createElement('a');
            newTab.href = url;
            newTab.target = "_blank";
            newTab.click();

        });




    </script>
 


@endpush






