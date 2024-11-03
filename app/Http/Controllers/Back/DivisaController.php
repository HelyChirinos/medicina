<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Divisa;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
class DivisaController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {
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
    

            return DataTables::of($a_divisas)
                ->only([
                    'id',
                    'fecha',
                    'valor',
                    'variacion',
                ])

                ->toJson();

        }

        return view('back.divisas.index');
    }

    public function create()
    {
        return view('back.divisas.modal_create');
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'fecha' => ['required', 'date', 'unique:divisas'],
            'valor' => ['required', 'numeric'],
        ]);

        Divisa::create([
            'divisa' => 'dolar',  
            'fecha' => $request->fecha,              
            'valor'=> $request->valor,
        ]);

        $notification = [
            'type' => 'success',
            'title' => 'Agregado ...',
            'message' => 'Dolar Agregado.',
        ];

        return response()->json(['success' => true]);
        // return redirect()->route('back.divisas.index')->with('notification', $notification);
    }

    public function edit(Divisa $divisa)
    {
        return view('back.divisas.modal_update', compact('divisa'));
    }

    public function update(Request $request, Divisa $divisa)
    {

        $validatedData = $request->validate([
            'fecha' =>['required', 'date', Rule::unique('divisas', 'fecha')->ignore($divisa->id)],
            'valor' => ['required', 'numeric'],
        ]);
        $divisa->update([
            'fecha' => $request->fecha,              
            'valor'=> $request->valor,
        ]);

        $notification = [
            'type' => 'success',
            'title' => 'Actualizar ...',
            'message' => 'Dolar Actualizado.',
        ];

        return response()->json(['success' => true]);
        // return redirect()->route('back.divisas.index')->with('notification', $notification);
    }

    public function refresh_data()
    {
        $dolar = cargar_divisa();
        session(['DOLAR' => $dolar]);
        return redirect()->route('back.divisas.index');
    }

    public function massDestroy(Request $request)
    {

        Divisa::whereIn('id', $request->ids)->delete();

        return response()->noContent();
    }


    public function showMessage(Request $request)
    {
    
        if (trim($request->message)=='Nuevo') {
            $notification = [
                'type' => 'success',
                'title' => 'Dolar BCV Agregado ...',
                'message' => 'Se agrego nueva Tasa BCV.',
            ];
        }
        if (trim($request->message)=='Actualizar') {
            $notification = [
                'type' => 'success',
                'title' => 'Bien Hecho ...',
                'message' => 'Dolar Actualizado.',
            ];
        }

        return redirect()->route('back.divisas.index')->with('notification', $notification);

    }




}
