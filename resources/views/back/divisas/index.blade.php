@extends('layouts.back')

@section('title')
    &vert; Divisas
@endsection

@section('content')
    <div class="card mb-2">
        <div class="card-header d-print-none">
            <div class="row">
                <div class="col fs-5 text-center">Divisas - Dolar BCV </div>
            </div>
        </div>

        <div class="card-body ">
            <div class="d-flex justify-content-between p-1">
                <div id="ToolbarLeft"></div>
                <div id="ToolbarCenter"></div>
                <div id="ToolbarRight"></div>
            </div>
            <div class="row">
            <div class="col-2">
     
            </div>
            <div class="col">
                <table id="sqltable" class="table table-bordered table-striped table-sm table-hover dataTable" style="width: 80%">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col" width="4%">ID</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Dolar-Bs.</th>
                            <th scope="col">Variación</th>
                         </tr>
                    </thead>
                </table>
            </div>
            <div class="col-2"></div>
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
                    url: "{{ route('back.divisas.create')}}",
                    success: function(response) {
                        bootbox.dialog({
                            locale: 'nl',
                            title: 'Ingresar Divisa',
                            message: response,
                            size: 'lg',
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
                $.ajax({
                    method: 'GET',
                    url: "{{ route('back.divisas.edit', 'id') }}".replace("id", id),
                    success: function(response) {
                        bootbox.dialog({
                            locale: 'nl',
                            title: 'Editar Dolar',
                            message: response,
                            size: 'lg',
                            onEscape: false,
                            backdrop: false
                        });
                    } //success
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

                document.location.href = '{{ route('back.divisas.refresh')}}';
            }
        }
        dtButtonsRight.push(clearButton)

        let deleteButton = {
            extend: 'selected',
            className: 'btn-danger selectMultiple',
            text: '<i class="bi bi-trash"></i>',
            titleAttr: 'Eliminar',
            enabled: false,
            url: "{{ route('back.divisas.massDestroy') }}",
            action: function(e, dt, node, config) {
                let ids = $.map(dt.rows({
                    selected: true
                }).data(), function(entry) {
                    return entry.id;
                });

                // remove protected users from selection
                for (let i = 1; i <= 2; i++) {
                    if (ids.includes(i)) {
                        ids = ids.filter(item => item !== i);

                        dt.row('#' + i).deselect();

                        showToast({
                            type: 'warning',
                            title: 'Eliminado ...',
                            message: 'Un item (ID = ' + i + ') se quito de la selección por protección.',
                        });
                    }
                }

                if (ids.length > 0) {
                    bootbox.confirm({
                        title: 'Eliminar Dolar ' + ids.length + ' item(s) ...',
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
                                console.log('IDs: '+ids )
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
                url: "{{ route('back.divisas.index') }}",
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
                    data: 'fecha',
                    name: 'fecha',
                    type: "date",
                    className: "text-center ",
                    render: DataTable.render.date('DD-MM-YYYY'),
                },
                {
                    data: 'valor',
                    name: 'valor',
                    type: 'numeric',
                    className: "text-center ",
                    render: DataTable.render.number(null, null, 2),
                },

                {
                    data: 'variacion',
                    name: 'variacion',
                    searchable: false,
                    className: "text-center ",
                    render: function(data, type, row, meta) {
                        if (data == 0) {
                            return data+'%   ' +'<i class="bi bi-arrows" style="color: yellow;font-size: 20px;"></i>';
                        }; 
                        if (data > 0) {
                            return data+'%   ' +'<i class="bi bi-arrow-up" style="color:green;font-size:20px;"></i>';
                        }; 
                        if (data < 0) {
                            return data+'%   '+'<i class="bi bi-arrow-down" style="color:red; font-size:20px;"></i>';
                        };                            
                    },
                    createdCell: function(td, cellData, rowData, row, col) {
                        if (cellData == 1) {
                            $(td).addClass('table-success');
                        }
                    },
                },
            ],
            select: {
                selector: 'td:not(.no-select)',
            },
            ordering: true,
            order: [
                [1, 'desc']
            ],
            preDrawCallback: function(settings) {
                oTable.columns.adjust();
            },
        };
        /* ------------------------------------------- */
        DataTable.datetime('DD-MM-YYYY');
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
    </script>
    
@endpush
