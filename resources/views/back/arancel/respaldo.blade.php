@extends('layouts.back')

@section('title')
    &vert; Aranceles
@endsection

@section('content')
    <div id="aranceles_card" class="card mb-3">
        <div class="card-header d-print-none">
            <div class="row">
                <div class="col">Aranceles</div>

                <div class="col fs-5 text-end">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
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
                            <th scope="col">Arancel</th>
                            <th scope="col">Monto Venezolano</th>
                            <th scope="col">Monto Extranjero</th>
                            <th scope="col">Fecha Creación</th>

                         </tr>
                    </thead>
                </table>
            </div>
            <div class="col-2"></div>
        </div>
        </div>
    </div>


    <div id="matricula_card" class="card mb-3">
        <div class="card-header d-print-none">
            <div class="row">
                <div class="col fs-4">Matrículas</div>

                <div class="col fs-5 text-end">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="d-flex justify-content-between p-1">
                <div id="ToolbarCenter2"></div>

            </div>
            <div class="row">
            <div class="col-2">
     
            </div>
            <div class="col">
                <table id="tableMatricula" class="table table-bordered table-striped table-sm table-hover dataTable" style="width: 80%">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col" width="4%">ID</th>
                            <th scope="col">Nombre Matrícula</th>
                            <th scope="col">Monto Venezolano</th>
                            <th scope="col">Monto Extranjero</th>
                            <th scope="col">Fecha Creación</th>

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
          {{-- DATATABLES ARANCELES --}}
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
                    url: "{{ route('back.aranceles.create')}}",
                    success: function(response) {
                        bootbox.dialog({
                            locale: 'nl',
                            title: 'Nuevo Arancel',
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
                    url: "{{ route('back.aranceles.edit', 'id') }}".replace("id", id),
                    success: function(response) {
                        bootbox.dialog({
                            locale: 'nl',
                            title: 'Editar Arancel',
                            message: response,
                            size: 'lg',
                            onEscape: false,
                            backdrop: false
                        });
                        
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
            url: "{{route('back.aranceles.Destroy') }}",
            action: function(e, dt, node, config) {
                let ids = $.map(dt.rows({
                    selected: true
                }).data(), function(entry) {
                    return entry.id;
                });


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
                url: "{{ route('back.aranceles.index') }}",
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
                    data: 'arancel',
                    name: 'arancel',
                    type: "text",
                    className: "text-center ",

                },
                {
                    data: 'monto_venezolano',
                    name: 'monto_venezolano',
                    type: 'numeric',
                    className: "text-center ",
                    render: DataTable.render.number(null, null, 2 ,null, ' $' ), 
                },
                {
                    data: 'monto_extranjero',
                    name: 'monto_extranjero',
                    type: 'numeric',
                    className: "text-center ",
                    render: DataTable.render.number(null, null, 2, null, ' $'),
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    type: "date",
                    searchable: false,
                    className: "text-center ",
                    render: function(data) {
                        return moment(data).utc().format('DD/MM/YYYY');
                    },
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
    </script>

     {{-- DATATABLES MATRICULA --}}

     <script type="module">
        /* ------------------------------------------------------------------------ */

       let dtButtonsCentro = [];

       /* ------------------------------------------------------------------------ */

       let nuevoButton = {
           className: 'btn-success',
           text: '<i class="bi bi-plus"></i>',
           titleAttr: 'Agregar',
           enabled: true,
           action: function(e, dt, node, config) 
           {
               $.ajax({
                   method: 'GET',
                   url: "{{route('back.matriculas.create')}}",
                   success: function(response) {
                       bootbox.dialog({
                           locale: 'nl',
                           title: 'Nueva Matrícula',
                           message: response,
                           size: 'lg',
                           onEscape: true,
                           backdrop: true
                       });
                   } //success
               }); // ajax               
           }  // action  

       } // nuevoButton

       dtButtonsCentro.push(nuevoButton)

       let editarButton = {
           extend: 'selectedSingle',
           className: 'btn-primary selectOne',
           text: '<i class="bi bi-pencil"></i>',
           titleAttr: 'Editar',
           enabled: false,

           action: function(e, dt, node, config) {
               const id = dt.row({ selected: true}).data().id;
               console.log( dt.row({ selected: true}).data() );
               $.ajax({
                   method: 'GET',
                   url: "{{ route('back.matriculas.edit', 'id') }}".replace("id", id),

                   success: function(response) {
                       bootbox.dialog({
                           locale: 'nl',
                           title: 'Editar Matrícula',
                           message: response,
                           size: 'lg',
                           onEscape: false,
                           backdrop: false
                       });
                   }, //success
                   error: function(result) 
                   {
                   if (result.status == 422) {
                       $("#help_card").css("display", "none");
                       $('#form_add').find(".print-error-msg").find("ul").html('');
                       $('#form_add').find(".print-error-msg").css('display','block');
                       $.each( result.responseJSON.errors, function( key, value ) {
                               $('#form_add').find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                       });
                   } else {
                       alert('Tipo de Error:' + result.status+' '+result.responseText);

                   }
                   }      
                       }); // ajax          
               // document.location.href = '{{ route('back.users.edit', 'id') }}'.replace("id", id);
           } //action
       }
       dtButtonsCentro.push(editarButton)


       let eliminarButton = {
           extend: 'selected',
           className: 'btn-danger selectMultiple',
           text: '<i class="bi bi-trash"></i>',
           titleAttr: 'Eliminar',
           enabled: false,
           url: "{{ route('back.matriculas.Destroy') }}",
           action: function(e, dt, node, config) {
               let ids = $.map(dt.rows({
                   selected: true
               }).data(), function(entry) {
                   return entry.id;
               });


               if (ids.length > 0) {
                   bootbox.confirm({
                       title: 'Eliminar Matrícula ' + ids.length + ' item(s) ...',
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
       dtButtonsCentro.push(eliminarButton)
       /* ------------------------------------------------------------------------ */
       let dtGlobalsmatriculas = {
           ajax: {
               url: "{{ route('back.matriculas.index') }}",
               data: function(d) {}
           },
           order: [[1, 'asc']],
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
                   data: 'nombre',
                   name: 'nombre',
                   type: 'text',
                   className: "text-center ",
               },                
               {
                   data: 'monto_venezolano',
                   name: 'monto_venezolano',
                   type: 'numeric',
                   className: "text-center ",
               },
               {
                   data: 'monto_extranjero',
                   name: 'monto_extranjero',
                   type: 'numeric',
                   className: "text-center ",
               },                                  
               {
                   data: 'created_at',
                   name: 'create_at',
                   type: "date",
                   className: "text-center ",
                   render: function(data) {
                       return moment(data).utc().format('DD/MM/YYYY');
                   }
               },



           ],
            // rowGroup: {
            // dataSrc: 'programa'
            // },   

           select: {
               selector: 'td:not(.no-select)',
           },
           ordering: true,

           preDrawCallback: function(settings) {
               oTableMatricula.columns.adjust();
           },
          
       };
       /* ------------------------------------------- */

       let oTableMatricula = $('#tableMatricula').DataTable(dtGlobalsmatriculas);
       /* ------------------------------------------------------------------------ */
       new $.fn.dataTable.Buttons(oTableMatricula, {
           name: 'BtnGroupCenter',
           buttons: dtButtonsCentro
       });

       oTableMatricula.buttons('BtnGroupCenter', null).containers().appendTo('#ToolbarCenter2');
       /* ------------------------------------------------------------------------ */
       oTableMatricula.on('select deselect', function(e, dt, type, indexes) {
           const selectedRows = oTableMatricula.rows({
               selected: true
           }).count();

           oTableMatricula.buttons('.selectOne').enable(selectedRows === 1);
           oTableMatricula.buttons('.selectMultiple').enable(selectedRows > 0);
       });

    </script>

@endpush
