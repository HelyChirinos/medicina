<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Userlog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('userlogs')->withCount('userlogs')->get();

            return DataTables::of($users)
                ->only([
                    'id',
                    'nombre',
                    'apellido',
                    'cedula',
                    'email',
                    'is_propietario',
                    'userlogs_count',
                ])
                ->addColumn('DT_RowId', function ($row) {
                    return $row->id;
                })
                ->toJson();
        }

        return view('back.users.index');
    }

    public function create()
    {
        return view('back.users.modal_create');
    }

    // public function store(UserStoreRequest $request)
    // {
    //     User::create($request->all());
    //     Password::sendResetLink($request->only(['email']));
    //     $notification = [
    //         'type' => 'success',
    //         'title' => 'Agregado ...',
    //         'message' => 'El usuario se agregó.',
    //     ];

    //     return redirect()->route('back.users.index')->with('notification', $notification);
    // }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:101'],
            'apellido' => ['required', 'string', 'max:101'],
            'cedula' => ['required', 'string', 'max:101', 'unique:users'],
            'email' => ['required', 'string', 'max:191', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'same:password_confirmation'],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:255'],
            'fecha_nac' => ['nullable', 'date', 'max:101'], 
            'telefono' => ['nullable', 'string', 'max:101'], 
            'is_propietario' => ['required','string','min:1','max:1'],
        ]);
     
        User::create($validatedData);
        Password::sendResetLink($request->only(['email']));
     
        $notification = [
            'type' => 'success',
            'title' => 'Agregado ...',
            'message' => 'El usuario se agregó.',
        ];
        return response()->json(['success' => true]);
//        return redirect()->route('back.users.index')->with('notification', $notification);
    }

    public function edit(User $user)
    {
        return view('back.users.modal_update', compact('user'));
    }

    public function update(Request $request, User $user)
    {
    
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:101'],
            'apellido' => ['required', 'string', 'max:101'],
            'cedula' => ['required', 'string', 'max:101', Rule::unique('users', 'cedula')->ignore($user->id)],
            'email' => ['required', 'string', 'max:191', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($user->id)],
            'fecha_nac' => ['nullable', 'date', 'max:101'], 
            'telefono' => ['nullable', 'string', 'max:101'], 
            'is_propietario' => ['required','string','min:1','max:1'],
        ]);
     

        $user->update($request->except(['token']));
    
            $notification = [
                'type' => 'success',
                'title' => 'Editado ...',
                'message' => 'Usuario updated.',
            ];
       
        return redirect()->route('back.users.index')->with('notification', $notification);
    }

    public function massDestroy(Request $request)
    {
        User::where('id', '>', 2)->whereIn('id', $request->ids)->delete();

        return response()->noContent();
    }

    public function getUserlogs(Request $request)
    {
        $date = Carbon::now();

        $userlogs_by_date = Userlog::where('user_id', $request->id)
            ->select('userlogs.created_at')
            ->where('created_at', '>=', $date->startOfMonth()->subMonths(3)->format('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('date');

        return view('back.users.get-userlogs', compact('userlogs_by_date'))->render();
    }


    public function showMessage(Request $request)
    {
    
        if (trim($request->message)=='Nuevo') {
            $notification = [
                'type' => 'success',
                'title' => 'Excelente ...',
                'message' => 'Nuevo usuario agregado.',
            ];
        }
        if (trim($request->message)=='Actualizar') {
            $notification = [
                'type' => 'success',
                'title' => 'Bien Hecho ...',
                'message' => 'Usuario Actualizado.',
            ];
        }

        return redirect()->route('back.users.index')->with('notification', $notification);

    }

    public function showModal()
    {
        return view('back.users.prueba_modal');

    }

}
