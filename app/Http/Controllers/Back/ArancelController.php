<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Arancel;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ArancelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $aranceles=Arancel::orderBy('arancel')->get();
        if ($request->ajax()) {
            return DataTables::of($aranceles)
                ->only([
                    'id',
                    'arancel',
                    'monto_venezolano',
                    'monto_extranjero',
                    'created_at'
                ])
                ->toJson();

        }

        return view('back.arancel.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('back.arancel.modal_create');
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        Arancel::create([
            'arancel' => strtoupper($request->arancel),  
            'monto_venezolano' => $request->montov,              
            'monto_extranjero'=> $request->montoe,
        ]);

   
        $notification = [
            'type' => 'success',
            'title' => 'Agregado ...',
            'message' => 'Arancel Agregado Agregado.',
        ];

        return redirect()->route('back.aranceles.index')->with('notification', $notification);

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Arancel $arancele)
    {

        return view('back.arancel.modal_update', compact('arancele'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Arancel $arancele)
    {
        $arancele->update([
            'arancel' => strtoupper($request->arancel),  
            'monto_venezolano' => $request->montov,              
            'monto_extranjero'=> $request->montoe,
        ]);

        $notification = [
            'type' => 'success',
            'title' => 'Actualizado ...',
            'message' => 'Arancel Modificado.',
        ];

        return redirect()->route('back.aranceles.index')->with('notification', $notification);
    }

    /**
     * Delete .
     */

    public function arancelesDestroy(Request $request)
    {

        Arancel::whereIn('id', $request->ids)->delete();

        return response()->noContent();
    }


    public function matriculas_index(Request $request)
    {
        $matriculas=Matricula::orderBy('nombre')->get();

        if ($request->ajax()) {
            return DataTables::of($matriculas)
                ->only([
                    'id',
                    'nombre',
                    'monto_venezolano',
                    'monto_extranjero',
                    'created_at'
                ])
                ->toJson();

                
        }
        return view('back.arancel.index');  
   
    }     
    
    public function matricula_create()
    {

        return view('back.arancel.modal_matricula_create');
    }

    public function matricula_store(Request $request)
    {
        Matricula::create([
            'nombre' => strtoupper($request->nombre),  
            'monto_venezolano' => $request->montov,              
            'monto_extranjero'=> $request->montoe,
        ]);

   
        $notification = [
            'type' => 'success',
            'title' => 'Agregado ...',
            'message' => 'Matrícula Agregada.',
        ];

        return redirect()->route('back.aranceles.index')->with('notification', $notification);
    }

    public function matricula_edit(Matricula $matricula)
    {
       

        return view('back.arancel.modal_matricula_update', compact('matricula'));
    }


    public function matricula_update(Request $request, Matricula $matricula)
    {
        $matricula->update([
            'nombre' => strtoupper($request->nombre),  
            'monto_venezolano' => $request->montov,              
            'monto_extranjero'=> $request->montoe,
        ]);

        $notification = [
            'type' => 'success',
            'title' => 'Actualizado ...',
            'message' => 'Matricula Modificado.',
        ];

        return redirect()->route('back.aranceles.index')->with('notification', $notification);
    }

    public function matriculasDestroy(Request $request)
    {

        Matricula::whereIn('id', $request->ids)->delete();

        return response()->noContent();
    }



}