<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Userlog;
use Illuminate\Support\Carbon;
use App\Models\Divisa;
use App\Models\Programa;
use App\Models\Mencion;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Estudiante;
use App\Models\Recibo;


class PruebasController extends Controller
{
    public function mostrar_log()
    {
        $user = Usuario::with('userlogs')->withCount('userlogs')->get();

        dd($user);

        return view('/admin');

    }
   // =================================================================//
    // =================================================================//  
  

    public function mostrar_dolar()
    {
        $dolar_bcv = 1;

        $data = file_get_contents("https://pydolarvenezuela-api.vercel.app/api/v1/dollar");
        if ($data == false) {
            $divisa = Divisa::orderBy('fecha', 'DESC')->first();
            if ($divisa) {
                $dolar_bcv = $divisa->dolar; 
            }            
            return $dolar_bcv;
        }   
     
        $cambio = json_decode($data,true);
        $dolar_bcv = $cambio['monitors']['bcv']['price'];
        $fecha_bcv = $cambio['monitors']['bcv']['last_update'];
        $hora_bcv = trim(substr($fecha_bcv,strpos($fecha_bcv,",")+1));
        $fecha_bcv = trim(substr($fecha_bcv,0,strpos($fecha_bcv,",")));
        $fecha = Carbon::createFromFormat('d/m/Y', $fecha_bcv,'America/Caracas')->toDate();
        $divisa = Divisa::whereDate('fecha',$fecha)->first();
        if (!$divisa) {
            Divisa::create([
                'divisa' => 'dolar',  
                'fecha' => $fecha,              
                'valor'=> $dolar_bcv,
            ]);
        } else {

            $dolar_bcv = $divisa->valor;
            dd('Dolar: '.$divisa->valor);
        }
       // session(['DOLAR' => $dolar]);
        dd('Dolar: '.$dolar_bcv);
        return $dolar_bcv;
    }
    // =================================================================//
    // =================================================================//
    public function arreglos()
    {
        $a_divisas = Divisa::orderBy('fecha')->get()->toArray();

        $cont = 0;
        $valor_ayer = 0;
        foreach ($a_divisas as &$divisa) {
            if ($cont == 0) {
                $divisa["variacion"] = number_format($cont,2);
            } else {
                $divisa["variacion"] = number_format((($divisa["valor"]-$valor_ayer)/$valor_ayer)*100,2);
            }  
            $valor_ayer = $divisa['valor']; 
            $cont++;
        }

        $a_divisas= json_encode($a_divisas,JSON_PRETTY_PRINT);
        dd($a_divisas);

        return view('/admin');
 
    }

    public function programas()
    {
        $programas = Programa::with('menciones')->orderBy('programa')->get();
        $a_programas=[];

        foreach ($programas as $programa) {
            foreach ($programa->menciones as $mencion) {
                array_push($a_programas, (object)[
                    'id' => $mencion->id,
                    'programa' => $programa->programa,
                    'mencion' => $mencion->mencion,
                    'fecha' => $mencion->created_at->toDateString() ,
            ]);
            }

        }

        $datos = DataTables::of($a_programas)
        ->addColumn('DT_RowId', function ($row) {
            return $row->id;
        })
        ->toJson();
        
        dd($datos);

    }

    public function relaciones()
    {
        $recibo=Recibo::first();
        dd($recibo->estudiante->mencion->mencion);
        
        $estudiantes=Estudiante::with(['programa:id,programa','mencion:id,mencion'])->get();
        $a_estud=[];
        foreach ($estudiantes as $estudiante) {
            array_push($a_estud, (object)[
                'id' => $estudiante->id,
                'nombre'=>$estudiante->nombre,
                'programa' => $estudiante->programa->programa,
                'mencion' => $estudiante->mencion->mencion,
                'telefono' => $estudiante->telefono ,
                'direccion'=> $estudiante->direccion,
            ]);
            
        }
        dd($a_estud);
    
    }

    public function dropdown()
    {
         $programas =  Programa::orderBy('programa')->get();
         return view('back.estudiantes.dropdown', compact('programas'));

    }



    public function cambiar_data()
    {
        $recibos=Recibo::all();
        foreach ($recibos as $recibo) {
            if ($recibo->fecha_registro =='') {
                $recibo->update([
                    'fecha_registro' => null,
                ]);
            } else {

                $time = strtotime($recibo->fecha_registro);
                $newformat = date('Y-m-d',$time);
                $recibo->update([
                    'fecha_registro' => $newformat,
                ]);

            }

        }
        dd($newformat);
       
    
    }    

    public function recibos()
    {
        $recibos=Recibo::orderBy('no_recibo','DESC')->get();
        $a_recibos=[];
        foreach ($recibos as $recibo) {
            array_push($a_recibos, (object)[
                'id' => $recibo->id,
                'no_recibo'=>$recibo->no_recibo,
                'no_doc' => $recibo->no_doc,
                'nombre' => $recibo->estudiante->nombre,
                'programa' => $recibo->estudiante->programa->programa,
                'mencion'=>  $recibo->estudiante->mencion->mencion,
                'concepto'=> $recibo->concepto,
                'fecha' => $recibo->fecha,
                'status'=> $recibo->status,
            ]);
            
        }
        dd($a_recibos);
    
    }
    public function dolar_del_dia()
    {
        
        $date = '30-05-2024';
        $tasa = tasaDeFecha($date);
        
        if(!$tasa) {
            dd('tasa con error:'.$tasa);
        }else{
           
            dd('El dolar al: '.$date.' era de:'.$tasa);
        }
    }

}
