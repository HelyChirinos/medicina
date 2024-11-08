<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function setValueDB(Request $request)
    {
        try {
            DB::table($request->table)->where('id', $request->id)->update([$request->key => $request->value]);

            $notification = [
                'type' => 'success',
                'title' => 'Editar ...',
                'message' => 'Item actualizado.',
            ];

        } catch (QueryException $e) {
            $notification = [
                'type' => 'error',
                'title' => 'Editar ...',
                'message' => $e->getMessage(),
            ];
        }

        return response()->json($notification);
    }

    public function setValueSession(Request $request)
    {
        $request->session()->put($request->key, $request->value);

        return response()->noContent();
    }

    public function getDatatablesHelp()
    {
        return view('back.components.datatables-help')->render();
    }
}
