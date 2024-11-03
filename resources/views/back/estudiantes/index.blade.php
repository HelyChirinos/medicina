@extends('layouts.back')

@section('title')
    &vert; Aranceles
@endsection

@section('content')
    <div class="card mb-2">
        <div class="card-header d-print-none">
            <div class="row">
                <div class="col-11 text-center fs-5">Estudiantes</div>

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

            </div>
            <div class="col">
                <table id="sqltable" class="table table-bordered table-striped table-sm table-hover dataTable" style="width: 80%">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col" width="4%">ID</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Número</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Programa</th>
                            <th scope="col">Mención</th>
                            <th scope="col">Cohorte</th>                            
                            <th scope="col">Email</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Pagos</th>
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
                $.ajax({
                    method: 'GET',
                    url: "{{ route('back.estudiantes.create')}}",
                    success: function(response) {
                        bootbox.dialog({
                            locale: 'nl',
                            title: 'Nuevo Estudiante',
                            message: response,
                            size: 'xl',
                            onEscape: true,
                            backdrop: true
                        });
                    } //success
                }); // ajax               
            }  // action  

        } // createButton

        dtButtonsCenter.push(createButton)

        let editButton = {
            extend: 'selectedSingle',
            className: 'btn-primary selectOne',
            text: '<i class="bi bi-pencil"></i>',
            titleAttr: 'Editar',
            enabled: false,

            action: function(e, dt, node, config) {
                const id = dt.row({
                    selected: true
                }).data().id;
                console.log('ID: '+id);
                $.ajax({
                    method: 'GET',
                    url: "{{ route('back.estudiantes.edit', 'id') }}".replace("id", id),
                    success: function(response) {
                        bootbox.dialog({
                            locale: 'nl',
                            title: 'Actualizar Estudiante',
                            message: response,
                            size: 'xl',
                            onEscape: false,
                            backdrop: false
                        });
                        
                    },
                    error: function(response) {
                     alert('Tipo de Error:' + response.status+' '+response.responseText);
                                    } 
                }); // ajax          
                // document.location.href = '{{ route('back.users.edit', 'id') }}'.replace("id", id);
            } //action
        }
        dtButtonsCenter.push(editButton)

        let clearButton = {
            className: 'btn-secondary',
            text: '<i class="bi bi-arrow-counterclockwise"></i>',
            titleAttr: 'Recargar Tabla',
            action: function(e, dt, node, config) {
                dt.state.clear();

                document.location.href = '{{ route('back.estudiantes.index')}}';
            }
        }
        dtButtonsRight.push(clearButton)

        let deleteButton = {
            extend: 'selected',
            className: 'btn-danger selectMultiple',
            text: '<i class="bi bi-trash"></i>',
            titleAttr: 'Eliminar',
            enabled: false,
            url: "{{route('back.estudiantes.Destroy') }}",
            action: function(e, dt, node, config) {
                let ids = $.map(dt.rows({
                    selected: true
                }).data(), function(entry) {
                    return entry.id;
                });


                if (ids.length > 0) {
                    bootbox.confirm({
                        title: 'Eliminar Estudiante' + ids.length + ' item(s) ...',
                        message: '<div class="alert alert-danger" role="alert">Seguro de Eliminar?</div>',
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
                                        ids: ids,
                                        _method: 'DELETE'
                                    },
                                    success: function(response) {
                                        dt.draw();

                                        showToast({
                                            type: 'success',
                                            title: 'Eliminado ...',
                                            message: 'La selección(es) (' + ids.length + ' items) ha sido eliminado.',
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
        }
        dtButtonsRight.push(deleteButton)
        /* ------------------------------------------------------------------------ */
        let dtOverrideGlobals = {
            ajax: {
                url: "{{ route('back.estudiantes.index') }}",
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
                    data: 'tipo_doc',
                    name: 'tipo_doc',
                    type: "text",
                    className: "text-center",

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
                    data: 'cohorte',
                    name: 'cohorte',
                    type: "text",                    
                    className: "text-center",
                },
                {
                    data: 'email',
                    name: 'email',
                    type: "text",                    
                    className: "text-center",
                },                                     
                {
                    data: 'telefono',
                    name: 'telefono',
                    type: "text",                    
                    className: "text-center",
                },
                {
                    data: null,
                    searchable: false,
                    sortable: false,
                    className: 'dt-center consulta-recibos no-select no-export',
                    defaultContent: '<i class="fa-light fa-file-invoice-dollar fa-fw"></i>',
                    orderable: false
                },        

            ],
            select: {
                selector: 'td:not(.no-select)',
            },
            ordering: true,
            order: [
                [1, 'asc']
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



        $('#sqltable tbody').on('click', '.consulta-recibos', function() {
            const data = oTable.row($(this).closest('tr')).data();
            console.log(data.id);
            $.ajax({
                method: 'GET',
                url: "{{ route('back.estudiantes.recibos', 'id') }}".replace("id", data.id),

                success: function(response) {
                    bootbox.dialog({
                            locale: 'nl',
                            title: 'Pagos de Estudiante',
                            message: response,
                            size: 'xl',
                            onEscape: false,
                            backdrop: false
                        });
                }
            });
        });



    </script>
 


@endpush
