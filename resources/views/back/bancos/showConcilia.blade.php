@extends('layouts.back')

@section('title')
    &vert; Conciliación
@endsection


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-body"  style="padding-top: 0px; padding-bottom: 0px;">
                        <div class="row mb-2 mt-3">
                            <table id="concilia" class="styled-table mb-3 mt-3" style="width: 100%">
                                <thead >
                                    <tr class="titulo text-center">
                                        <th class="text-center no-select no-export fs-5" colspan="7" data-dt-order="disable">CONCILIADO</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" >Ref.</th>
                                        <th scope="col" >Recibo</th>
                                        <th scope="col" >No. Doc.</th>
                                        <th scope="col" >Nombre</th>
                                        <th scope="col" >F.Banco</th>
                                        <th scope="col" >Monto.Banco</th>
                                        <th scope="col" >Monto.Deposito</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @forelse($conciliados as $item)
                                    <tr>
                                        <td>{{$item->referencia}}</td>
                                        <td>{{$item->recibo}}</td>
                                        <td>{{$item->no_doc}}</td>
                                        <td>{{$item->nombre}}</td>
                                        <td>{{formatFecha($item->banco_fecha)}}</td>
                                        <td style="text-align: right">{{formatMoney(($item->banco_monto))}} Bs.</td>
                                        <td style="text-align: right">{{formatMoney(($item->deposito_monto))}} Bs.</td>
                                         
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="p-3 text-center text-bold">NO HAY CONCIDENCIA CON EL BANCO.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>                     
                        </div>

                    </div>    
                </div> 
            </div>    
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-body"  style="padding-top: 0px; padding-bottom: 0px;">
                        <div class="row mb-2 mt-3">
                            <table id="transito" class="styled-table mb-3 mt-3" style="width: 100%">
                                <thead >
                                    <tr class="titulo">
                                        <th colspan="5" class="text-center no-select no-export fs-5" data-dt-order="disable">EN TRANSITO</th>
                                    </tr>
                                    <tr>
                                        <th scope="col">F.Operación</th>
                                        <th scope="col" class="text-center" >Referencia</th>
                                        <th scope="col">F.Valor</th>
                                        <th scope="col" >Descipción</th>
                                        <th scope="col" >Abono</th>
    
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @forelse($transito as $item)
                                    <tr>
                                        <td>{{formatFecha($item->fecha_operacion)}}</td>
                                        <td class="text-center">{{$item->referencia}}</td>
                                        <td>{{formatFecha($item->fecha_valor)}}</td>
                                        <td >{{$item->descripcion}}</td>
                                        <td style="text-align: right">{{formatMoney(($item->abono))}} Bs.</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="p-3 text-center text-bold">NO EXISTE INFORMACIÓN EN TRANSITO.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>                     
                        </div>

                    </div>    
                </div> 
            </div>                
        </div>
    </div>

@endsection

@push('scripts')
          {{-- DATATABLES CONCILIACION --}}
    <script type="module">
        /* ------------------------------------------------------------------------ */
        let dtOverrideGlobals = {
            serverSide: false,
            retrieve: false,
            layout: null,
            lengthMenu: null,
            layout: {
                top2Start: null,
                top2End: null,    
                topEnd: 'search',
                topStart: {
                    pageLength: {
                        menu:  [[10, 25, 50, 100, -1],[10, 25, 50, 100, 'Todos']]
                    },
                },
                topEnd: 'search',    
                bottomStart: 'info',
                bottomEnd: 'paging'
            },    
            pageLength: 10,        

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

        };
        /* ------------------------------------------- */

        let oTable = $('#concilia').DataTable(dtOverrideGlobals);
        /* ------------------------------------------------------------------------ */

        let Table2 = $('#transito').DataTable(dtOverrideGlobals);
        table2.page.len(25).draw();
    </script>

@endpush

