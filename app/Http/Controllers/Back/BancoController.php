<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Banco;
use App\Imports\BancoImport;
use App\Imports\DiarioImport;
use App\Models\Deposito;
use App\Models\Estudiante;
use App\Models\Diario;
use Illuminate\Http\Request;
use App\Models\Recibo;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class BancoController extends Controller
{

    public function index(Request $request) 
    {
        $banco=Banco::get(); 

        if ($banco->count()){
            $temp = Banco::whereNotNull('fecha_operacion')->orderBy('fecha_operacion','asc')->first();
            $desde = formatFecha($temp->fecha_operacion);
            $temp = Banco::whereNotNull('fecha_operacion')->orderBy('fecha_operacion','desc')->first();
            $hasta = formatFecha($temp->fecha_operacion);
            $periodo =' del: '.$desde.' al: '.$hasta;
        }else {
            return redirect()->route('back.bancos.index_diario');
            $periodo =' del: --------  al: --------';
        }
        return view('back.bancos.index', compact(['banco' , 'periodo' ]));
    }


     /**
     * Diario
     */
    public function index_diario(Request $request) 
    {
        $diario=Diario::get(); 

        if ($diario->count()){
            $temp = Diario::whereNotNull('f_operacion')->orderBy('f_operacion','asc')->first();
            $desde = formatFecha($temp->f_operacion);
            $temp = Diario::whereNotNull('f_operacion')->orderBy('f_operacion','desc')->first();
            $hasta = formatFecha($temp->f_operacion);
            $periodo =' del: '.$desde.' al: '.$hasta;            

        }else {

            $periodo =' del: --------  al: --------';
        }
        return view('back.bancos.diario_index', compact(['diario' , 'periodo' ]));
    }

     /**
     * Ajax
     */


    public function ajax_cierre(Request $request) 
    {
        $banco=Banco::get(); 
        if ($request->ajax()) {
            return DataTables::of($banco)
                ->toJson();
        }
    }

    public function ajax_diario(Request $request) 
    {
        $diario=Diario::get(); 
        if ($request->ajax()) {
            return DataTables::of($diario)
                ->toJson();
        }
    }
 
     /**
     * Cargar Archivos
     */

 
    public function upload() 
    {
        return view('back.bancos.uploadFile');
    }

     
    public function uploadDiario() 
    {
        return view('back.bancos.uploadDiario');
    }

       /**
     *   Leer Archivos Excel
     */     

    public function importFile(Request $request) 
    {
        Banco::truncate();
        try {
            Excel::import(new BancoImport, request()->file('excel_file'));
        } catch (\Throwable $th) {
            $notification = [
                'type' => 'error',
                'title' => 'Error Archivo!!!',
                'message' => 'NO SE CARGO EL ARCHIVO EXCEL, VERIFIQUE',
            ];
            return back()->with('notification', $notification);
        }
        Diario::truncate();

        $notification = [
            'type' => 'success',
            'title' => 'TODO BIEN!!!',
            'message' => 'SE CARGO EL ARCHIVO EXCEL',
        ];

        
        return redirect()->route('back.bancos.index')->with('notification', $notification);
    }


    public function conciliacion(Request $request) 
    {
   
        $referencias=Banco::where('abono','<>',0)->where('fecha_operacion','<>',null)->get(); 
        $a_concilia=[];
        $a_transito=[];
        $depositos = DB::table('depositos')
        ->select('*', DB::raw('substr(numero, -5) as referencia'))
        ->get();

    
        foreach ($referencias as $item) {
           if ($esta=$depositos->where('referencia',$item->referencia)->first()){
                $estud = Estudiante::find($esta->estudiante_id);
                $recibo = Recibo::find($esta->recibo_id);
                array_push($a_concilia, (object)[
                    'referencia'=>$item->referencia,
                    'banco_id' => $item->id,
                    'recibo' => ($recibo) ? $recibo->no_recibo : '----',
                    'no_doc' => $estud->no_doc,                    
                    'nombre' => $estud->nombre,
                    'banco_fecha' =>$item->fecha_operacion,
                    'deposito_fecha'=>$esta->fecha,
                    'banco_monto' =>$item->abono,
                    'deposito_monto'=>$esta->monto,                
                ]);

           } else { 
                array_push($a_transito,$item->id);
            }       
        }


        $transito = Banco::whereIn('id', $a_transito)->get();
        $conciliados = $a_concilia;
        
        return view('back.bancos.showConcilia', compact(['transito','conciliados']));
    }

    public function importDiario(Request $request) 
    {
        Diario::truncate();
        try {
            Excel::import(new DiarioImport, request()->file('diario_excel'));
        } catch (\Throwable $th) {
            $notification = [
                'type' => 'error',
                'title' => 'Error Archivo!!!',
                'message' => 'NO SE CARGO EL ARCHIVO EXCEL, VERIFIQUE',
            ];
            return back()->with('notification', $notification);
        }
        Diario::whereNull('f_operacion')->delete();
        Banco::truncate();
        $notification = [
            'type' => 'success',
            'title' => 'TODO BIEN!!!',
            'message' => 'SE CARGO EL ARCHIVO EXCEL',
        ];

        
        return redirect()->route('back.bancos.index_diario')->with('notification', $notification);
    }


}


