<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Arancel;
use Illuminate\Http\Request;
use App\Models\Recibo;
use App\Models\Tmp_recibo;
use App\Models\Tmp_solicitud;
use App\Models\Tmp_deposito;
use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\Deposito;
use App\Models\Donativo;
use App\Models\Petitorio;
use Yajra\DataTables\Facades\DataTables;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

use NumberFormatter;

class ReciboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $fullInput;
    private $fullDeposito;
    private $error_fechas;
    private $duplicados;
    private $duplicado_BD;

    public function __construct()
    {
         $this->fullInput = false;
         $this->fullDeposito = false;
         $this->error_fechas=[];
         $this->duplicados=false;
         $this->duplicado_BD=false;

    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
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
                    'fecha' => $recibo->fecha_recibo,
                    'status'=> $recibo->status,
                ]);
            }

            return DataTables::of($a_recibos)
            ->addColumn('DT_RowId', function ($row) {
                return $row->id;
            })
            ->toJson();

        }


        return view('back.recibos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Estudiante $estudiante)
    {
        $matriculas = Matricula::orderBy('nombre')->get();
        $aranceles = Arancel::orderBy('arancel')->get();
        return view('back.recibos.create',compact(['estudiante','matriculas','aranceles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $tmp_recibo = Tmp_recibo::find($request->tmp_recibo_id);
        $solicitudes = Tmp_solicitud::where('tmp_recibo_id',$tmp_recibo->id)->get();
        $depositos = Tmp_deposito::where('tmp_recibo_id',$tmp_recibo->id)->get();
        $estudiante = Estudiante::find($tmp_recibo->estudiante_id);
        $concepto= [];

        foreach ($solicitudes as $solicitud) {
            if ($solicitud->tipo=='A') {
                $arancel = Arancel::find($solicitud->tipo_id);
                if ($arancel) 
                {
                   array_push($concepto,$arancel->arancel);                 
                };
            } else {
                $matricula = Matricula::find($solicitud->tipo_id);
                if($matricula){
                    array_push($concepto,$matricula->nombre);                 
                }
            }
 
        }    
        if ($tmp_recibo->concepto){
            array_push($concepto,$tmp_recibo->concepto);     
        }
        $concepto = implode("-", $concepto);

        $recibo =  Recibo::create([
            'estudiante_id' => $tmp_recibo->estudiante_id,
            'no_doc' => $estudiante->no_doc,  
            'fecha_recibo' => $tmp_recibo->fecha_recibo,
            'concepto' => $concepto,
            'status' => 'VIGENTE'
        ]); 

        $no_recibo =  sprintf("%04s", $recibo->id);
        $recibo->update(['no_recibo' => $no_recibo]);
        $id_recibo = $recibo->id;
        $cod_petitorio = ''; 
        foreach ($depositos as $deposito) {
            if (substr($deposito->no_deposito,0,3)=='DON'){
                $cod_petitorio = $deposito->no_deposito;
            }
            Deposito::create([
                'recibo_id' => $id_recibo,  
                'estudiante_id' => $tmp_recibo->estudiante_id,
                'numero' => $deposito->no_deposito,
                'fecha' => $deposito->fecha,
                'monto' => $deposito->monto
            ]);       
        }

        if($cod_petitorio!=''){
            $petitorio = Petitorio::firstWhere('codigo',$cod_petitorio);
            if($petitorio)  {
                $petitorio->update([
                    'status' => 'USADO',              
                ]);
            }

        }

       
        if($request->accion=='donar') {

            Donativo::create([
                'recibo_id' => $id_recibo,  
                'estudiante_id' => $tmp_recibo->estudiante_id,
                'monto' => $request->dif_Bs,
                'fecha' => $tmp_recibo->fecha_recibo,
            ]);       

        };

        $notification = [
            'type' => 'success',
            'title' => 'Recibo ...',
            'message' => 'Se ha creado un Nuevo Recibo.',
        ];
   
        return redirect()->route('back.recibos.index')->with('notification', $notification);


    }
    


    /**
     * Muestra Recibo Validado
     */
    public function showVerificado(Tmp_recibo $tmp_recibo)
    {
        $id_estudiante = $tmp_recibo->estudiante_id;
        $estudiante = Estudiante::find($id_estudiante);
        $concepto = $tmp_recibo->concepto;
        $nac = ($estudiante->tipo_doc=='CI') ? 'VE' : 'EX';
        $solicitudes = Tmp_solicitud::where('tmp_recibo_id',$tmp_recibo->id)->get();
        $a_solicitud=[];
        /* Dolar para el calculo es la fecha del Ultimo deposito */
        $fecha_calculo = Tmp_deposito::where('tmp_recibo_id',$tmp_recibo->id)->orderBy('fecha','desc')->first();
        if ($fecha_calculo) {
            $dolar_hoy=tasaDeFecha($fecha_calculo->fecha);
        } else {
            $dolar_hoy=tasaDeFecha($tmp_recibo->fecha_recibo);
        }

        foreach ($solicitudes as $solicitud) {
            if ($solicitud->tipo=='A') {
                $arancel = Arancel::find($solicitud->tipo_id);
                if ($arancel) 
                {
                    if ($nac='VE'){
                        $costo_dolar =$arancel->monto_venezolano;    
                        $costo_Bs = $dolar_hoy*$costo_dolar;
                    } else {
                        $costo_dolar =$arancel->monto_extranjero;    
                        $costo_Bs = $dolar_hoy*$costo_dolar;
                    }
                
                    array_push($a_solicitud, (object)[
                        'id' => $arancel->id,
                        'tipo'=>'ARANCEL',
                        'nombre' => $arancel->arancel,
                        'costo_Bs'=>$costo_Bs,
                        'costo_dolar'=>$costo_dolar,
                    ]);
                
                };
            } else {
                $matricula = Matricula::find($solicitud->tipo_id);
                if($matricula){
                    if ($nac='VE'){
                        $costo_dolar =$matricula->monto_venezolano;    
                        $costo_Bs = $dolar_hoy*$costo_dolar;
                    } else {
                        $costo_dolar =$matricula->monto_extranjero;    
                        $costo_Bs = $dolar_hoy*$costo_dolar;
                    }
                    array_push($a_solicitud, (object)[
                        'id' => $matricula->id,
                        'tipo'=>'MATRICULA',
                        'nombre' => $matricula->nombre,
                        'costo_Bs'=>$costo_Bs,
                        'costo_dolar'=>$costo_dolar,
                    ]);
                                    
                }
            }
 
        }    

        $total_costo_Bs = array_sum(array_column($a_solicitud, 'costo_Bs'));
        $total_costo_dolar = array_sum(array_column($a_solicitud, 'costo_dolar'));
        // DEPOSITOS - PAGOS
////////////////////////////////// ROUN DE DEPOSITOS    //////////////////////////////////////////
        $a_pagos=[];
        $depositos = Tmp_deposito::where('tmp_recibo_id',$tmp_recibo->id)->get();
        foreach ($depositos as $deposito) {
            if($deposito->fecha) {
                $tasa=tasaDeFecha($deposito->fecha);
                if ($tasa){
                    $pago_dolar =round($deposito->monto/$tasa,2);     ///// AQUI EL ROUND
                    array_push($a_pagos, (object)[
                        'id' => $deposito->id,
                        'deposito' => $deposito->no_deposito,
                        'fecha'=>$deposito->fecha,
                        'pago_Bs'=>$deposito->monto,
                        'pago_dolar'=>$pago_dolar,
                   ]);
               }

            }
        }
      
        $total_pago_Bs = array_sum(array_column($a_pagos, 'pago_Bs'));
        $total_pago_dolar = array_sum(array_column($a_pagos, 'pago_dolar'));
////////////////////////////////// ROUN DE COSTO    //////////////////////////////////////////        
        if ($concepto) {
            $dif_Bs = $total_pago_Bs - $total_costo_Bs;
            if ($dif_Bs > 0) {
                $costo_Bs=$dif_Bs;
                $costo_dolar=round($costo_Bs/$dolar_hoy,2);
                array_push($a_solicitud, (object)[
                    'id' => '11',
                    'tipo'=>'CONCEPTO',
                    'nombre' => $concepto,
                    'costo_Bs'=>$costo_Bs,
                    'costo_dolar'=>$costo_dolar,
                ]);
                $total_costo_Bs = array_sum(array_column($a_solicitud, 'costo_Bs'));
                $total_costo_dolar = array_sum(array_column($a_solicitud, 'costo_dolar'));    
            } 

        }
       
        
    
        return view('back.recibos.verificado', compact(['estudiante','a_solicitud','a_pagos','total_costo_Bs',
    'total_costo_dolar','total_pago_dolar','total_pago_Bs', 'tmp_recibo']));

    }





    /**
     * Display the specified resource.
     */
    public function validaRecibo(Request $request)
    {


        // VALIDACIONES
       $fechas = $request->fecha;
       $montos = $request->monto;
       $depositos = $request->deposito;
       $aranceles = $request->arancel;
       $matriculas =$request->matricula;
       $filtered_fechas = count(array_filter($fechas, 'strlen'));
       $filtered_depo = count(array_filter($depositos, 'strlen'));
       $filtered_montos = count(array_filter($montos, 'strlen'));
       $this->fullInput  = (($aranceles) OR ($matriculas) OR ($request->concepto));
       $this->fullDeposito = (($filtered_montos==$filtered_depo) AND ($filtered_depo==$filtered_fechas));
       $this->error_fechas=[];
       for ($i=0; $i <3 ; $i++) { 
            if($fechas[$i]) {
                $tasa=tasaDeFecha($fechas[$i]);
                if (!$tasa){
                    array_push($this->error_fechas,$fechas[$i]);
                }

            }
        }
        if ($filtered_depo>1) {
            $filtered_array = array_filter($depositos, 'strlen');
            $temp_array = array_unique($filtered_array);
            $this->duplicados = sizeof($filtered_array) != sizeof($temp_array);
        } else {
            $temp_array = array_filter($depositos, 'strlen');
        }
        $this->duplicado_BD=false;
        foreach ($temp_array as $key => $value) {
            $tmp_dep = Deposito::where('numero',$value)->first();
           
            if ($tmp_dep){
                $this->duplicado_BD=true;
            }
        }

       $validator = Validator::make($request->all(), [
            'fecha_recibo' => 'required|date',

        ]);



        $validator->after(function ($validator) {
            if (!$this->fullInput) {
                $validator->errors()->add(
                    'concepto', 'Faltan Datos: matricula o arancel o concepto'
                );
            }
            if (!$this->fullDeposito) {
                $validator->errors()->add(
                    'telefono', 'Datos Incompletos (deposito, fecha o monto)'
                );
                
            }
            if(!empty($this->error_fechas))  {
                $validator->errors()->add(
                    'email', 'Falta tasa BCV en fecha de Deposito'
                );
                
            }
            if($this->duplicados)  {
                $validator->errors()->add(
                    'deposito', 'Revise números de Deposito (2 o más repetidos)'
                );
                
            }              
            if($this->duplicado_BD)  {
                $validator->errors()->add(
                    'deposito', 'Número de Deposito ya existe en la Base de Datos'
                );
                
            }            
        });

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 422);
        } 

        $id_recibo =Tmp_recibo::create([
            'estudiante_id'=>$request->estudiante_id,  
            'fecha_recibo' => $request->fecha_recibo,  
            'concepto' => $request->concepto,  
       ]);

       for ($i=0; $i <3 ; $i++) { 
            if ($depositos[$i]) {
                $id_deposito = Tmp_deposito::create([
                    'tmp_recibo_id'=>$id_recibo->id,  
                    'no_deposito' => $depositos[$i],  
                    'fecha' => $fechas[$i],  
                    'monto' => $montos[$i],
               ]);                

            }
       }

       if ($aranceles) {
            foreach ($aranceles as $value) {
                $solicitudes = Tmp_solicitud::create([
                    'tmp_recibo_id'=>$id_recibo->id,  
                    'tipo' => 'A',  
                    'tipo_id' => $value,  
               ]);                
            }
        }


        if ($matriculas) {
            foreach ($matriculas as $value) {
                $solicitudes = Tmp_solicitud::create([
                    'tmp_recibo_id'=>$id_recibo->id,  
                    'tipo' => 'M',  
                    'tipo_id' => $value,  
               ]);                
            }

        }    
        return response()->json(['recibo_id'=>$id_recibo->id]);


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    public function goBack(Tmp_recibo $tmp_recibo)
    {
        $id_estudiante = $tmp_recibo->estudiante_id;
        $fecha_r = Carbon::parse($tmp_recibo->fecha_recibo)->format('Y-m-d');
        $estudiante = Estudiante::find($id_estudiante);
        $solicitudes = Tmp_solicitud::select('tipo','tipo_id')->where('tmp_recibo_id',$tmp_recibo->id)->get();
        $concepto = $tmp_recibo->concepto;
        $a = $solicitudes->where('tipo','A')->toArray();
        $m = $solicitudes->where('tipo','M')->toArray();
        $a_arancel = array_column($a, 'tipo_id');
        $a_matricula = array_column($m, 'tipo_id');
        $depositos = Tmp_deposito::select('no_deposito','fecha','monto')->where('tmp_recibo_id',$tmp_recibo->id)->get()->toArray();
        $matriculas = Matricula::orderBy('nombre')->get();
        $aranceles = Arancel::orderBy('arancel')->get();
        
        return view('back.recibos.goBack', compact(['estudiante','a_matricula','a_arancel','depositos','matriculas',
        'aranceles','fecha_r','concepto']));

    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function RecibosDestroy(Request $request)
    {
  
        $recibo =  Recibo::find($request->id);
        if($recibo)  {
            $recibo->update([
                'status' => 'ANULADO',              
            ]);
        }
  
        return response()->noContent();
    }

    public function sugerencias(Request $request)
    {
      
        $nac =$_GET['nac'];
        $dolarHoy = dolarDelDia();
        $dif_Bs =$_GET['dif_Bs'];
        $monto = $dif_Bs/$dolarHoy;
        if ($nac=='CI') {
            $matriculas = Matricula::where('monto_venezolano','<=',$monto)->get();
        } else {
            $matriculas = Matricula::where('monto_extranjero','<=',$monto)->get();
        }
        if ($nac=='CI') {
            $aranceles = Arancel::where('monto_venezolano','<=',$monto)->get();
        } else {
            $aranceles = Arancel::where('monto_extranjero','<=',$monto)->get();
        }        
        return view('back.recibos.sugerencias', compact(['matriculas','aranceles','dolarHoy']));
    }


    
// =================================================================//
// VALIDACION EXISTE ESTUDIANTE
// =================================================================//  

public function validaEstudiante(Request $request)
{
   $estudiante=Estudiante::firstWhere('no_doc', $request->id);
   if($estudiante)
   {   return response()->json(['encontrado' => true, 'id'=>$estudiante->id]);
   }else{
        return response()->json(['encontrado' => false]);
   }
}


public function petitorio(Request $request)
{
    $estudiante=Estudiante::find($request->id_estudent);
    $recibo_id = $request->recibo_id;
    $tmp_recibo=Tmp_recibo::find($recibo_id);    
    $dif_Bs = abs($request->dif_Bs);
    $codigo = 'DON-'.sprintf("%04s", $recibo_id);
    $petitorio = Petitorio::create([
        'tmp_recibo_id'=>$request->recibo_id,
        'estudiante_id'=>$request->id_estudent,
        'codigo' => $codigo,  
        'fecha' => $tmp_recibo->fecha_recibo,  
        'monto' => $dif_Bs,
        'status' => 'EN ESPERA',  
   ]);
    return view('back.recibos.petitorio', compact(['estudiante','codigo','dif_Bs']));

}

public function validaPetitorio(Request $request)
{
   $petitorio=Petitorio::firstWhere('codigo', $request->codigo);
   if($petitorio) { 
        if($petitorio->status=='APROBADO'){
            return response()->json(['aprobado' => true, 'id'=>$petitorio->tmp_recibo_id]);
        } else {
            return response()->json(['aprobado' => false]);
       }
   } else {
        return response()->json(['aprobado' => false]);
   }

}




// =================================================================//
// IMPRIMIR RECIBO - PDF
// =================================================================//  

public function printRecibo(Recibo $recibo)
{
    $fecha = Carbon::parse($recibo->fecha_recibo);
    $date = $fecha->locale(); //con esto revise que el lenguaje fuera es
    $texto_fecha = $fecha->day.' de '.$fecha->monthName.' del '. $fecha->year;  
    // dd($fecha->isoFormat('LL')); //y con esta obtengo el mes al fin en español!
    // dd($recibo->fecha_recibo);
    $image_path = public_path('img\logo');
    $pdf = new FPDF('P','mm','A3');
    $pdf->AddPage();    
    
    for ($x = 1; $x <= 3; $x++) {
    
        $pdf->SetMargins(15, 10, 10);
        $image_path = public_path('img/logo');
        $pdf->Image($image_path.'/logoeste.png', 30 ,10, 28, 18,'png');
        $pdf->Image($image_path.'/decanato.jpg', 230 ,10, 20, 25,'jpg');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(255,4,'UNIVERSIDAD CENTROCCIDENTAL ',0,0,'C');
        $pdf->ln();
        $pdf->Cell(255,4,'"LISANDRO ALVARADO" ',0,0,'C');
        $pdf->ln();
        $pdf->Cell(255,4,utfToIso('COORDINACIÓN DE POSTGRADO CIENCIAS DE LA SALUD '),0,0,'C');
        $pdf->ln();$pdf->ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(2000,8,'               RIF: G-20000077-5 ',0,0,'J');
        $pdf->ln();
        $pdf->Cell(220,0,'               NIT: 0120858858 ',0,0,'J');
        $pdf->ln();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(230,10,'                        RECIBO DE PAGO ',0,0,'C');
        $pdf->SetFont('Arial','',9,'J');
        $fecha1=date('d-m-Y');
        $fecha=$fecha1;
        $pdf->SetFont('Arial','B',12,'J');
        $hoy=date('y/m/d');  
        $pdf->SetTextColor(218,43,6);
        $pdf->SetFont('Arial','B',16,'');
        $pdf->Cell(10 ,12,utfToIso('N°:'.$recibo->no_recibo.''),0,0,'J');
        $pdf->ln(); 
        $pdf->SetFont('Arial','B',8,'J');
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(50,5,utfToIso('FECHA DE REGISTRO: '.formatFecha($recibo->fecha_recibo).''),0,0,'C');
        $pdf->Cell(370,5,utfToIso('       FECHA DEL RECIBO: '.formatFecha($recibo->fecha_recibo).''),0,0,'C');
        $pdf->SetFont('Arial','B',12,'J');
        $pdf->ln(); 
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(90 ,8,utfToIso(' N° de Documento:   '.$recibo->no_doc.''),1,0,'J');
        $pdf->Cell(180 ,8,utfToIso(' He recibido:   '.$recibo->estudiante->nombre.''),1,0,'J');
    
        $pdf->ln();
        $depositos = Deposito::where('recibo_id',$recibo->id)->get();
        $total = $depositos->sum('monto');
        $letras=$total;
        $numeros = $depositos->pluck('numero')->toArray();
        $comprobantes = implode("-", $numeros);
        $total_comprobantes= count($numeros);
        $for= new NumberFormatter("es", NumberFormatter::SPELLOUT);
        $estee=$for->format($total);
        $este= str_replace ('coma','con', $estee);

        $pdf->Cell(200 ,8,utfToIso(' Cantidad de bolívares(en letras):   '.$este.' Bolívares'),1,0,'J');

        $pdf->Cell(70 ,8,utfToIso(' Monto (en número):   '.$total.' Bs.'),1,0,'J');
        $pdf->ln();
        $pdf->Cell(200 ,8,utfToIso(' Programa de:    '.$recibo->estudiante->programa->programa.''),1,0,'J');

        $pdf->Cell(70 ,8,utfToIso(' Mención:    '.$recibo->estudiante->mencion->mencion.''),1,0,'J');
        $pdf->ln();
        $pdf->Cell(270 ,8,utfToIso(' Concepto:    '.$recibo->concepto.''),1,0,'J');     

        $pdf->ln();
        $pdf->Cell(70 ,8,utfToIso(' N° de Depósitos:  '.$total_comprobantes.''),1,0,'J');


        $pdf->Cell(200 ,8,utfToIso(' Comprobante(s):   '.$comprobantes.'  '),1,0,'J');
        $pdf->ln();
        $pdf->ln();


        $pdf->Cell(270 ,8,utfToIso(' Funcionario Responsable:                                                            Firma:                                                      Sello:               '),0,0,'J');
        $pdf->ln();$pdf->ln();$pdf->ln();
        if($x==1){
            $pdf->Cell(270 ,8,utfToIso('ORIGINAL'),0,0,'J');$pdf->ln();
        }else {
            $pdf->Cell(270 ,8,utfToIso('COPIA'),0,0,'J');$pdf->ln();
        }
        $pdf->Cell(270 ,8,utfToIso('______________________________________________________________________________________________________________'),0,0,'J');
        $pdf->ln();
   
    }
    $pdf->Output();



}




  // =================================================================//  



}