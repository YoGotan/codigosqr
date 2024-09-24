<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('nombre', function ($row) {
                    $btn = $row->nombre. ' ' . $row->apellidos;
                    return $btn;
                })
                ->addColumn('fecha', function ($row) {
                    $fecha = new DateTime($row->created_at);
                    $btn = '<span class="d-block pb-2">' . $fecha->format('d/m/Y') . '</span>' . '<span class="hora d-block text-muted">' . $fecha->format('H:m:s') . '</span>';
                    return $btn;
                })
                ->addColumn('editar', function ($row) {
                    $btn = '<a data-toggle="tooltip" title="Editar usuario" href="' .  route('profile.edit', ['user' => $row->id]) . '" class="d-inline-block"><span class="badge editar p-2 fd-muted txt-muted"><span class="icono icon-editar"></span></span></a>';

                    return $btn;
                })
                ->rawColumns(['fecha', 'editar'])
                ->make(true);
        }
        return view('usuarios.ver');
    }

    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
