<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request)
    {
        $usuario = new Usuario;
        $usuario->nomeUsuario = $request->nome;
        $usuario->emailUsuario = $request->email;
        $usuario->loginUsuario = $request->login;
        // $usuario->senhaUsuario = Hash::make($request->senha);
        $usuario->senhaUsuario = $request->senha;
        $usuario->api_key = '';
        $usuario->save();
        return response()->json($usuario);
    }

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'senha' => 'required'
        ]);
        $usuario = Usuario::where('emailUsuario', $request->input('email'))->first();
        echo $usuario;

        // if (Hash::check($request->input('senha'), $usuario->senhaUsuario)) {
        if ($request->input('senha') == $usuario->senhaUsuario) {
            $apikey = base64_encode(Str::random(40));
            Usuario::where('emailUsuario', $request->input('email'))->update(['api_key' => "$apikey"]);;
            return response()->json(['status' => 'success', 'api_key' => $apikey]);
        } else {
            return response()->json(['status' => 'fail'], 401);
        }
    }

    // public function show($id)
    // {
    //     $usuario = Usuario::find($id);
    //     return response()->json($usuario);
    // }

    // public function update(Request $request, $id)
    // {
    //     $usuario = Usuario::find($id);

    //     $usuario->nomeUsuario = $request->input('nome');
    //     $usuario->emailUsuario = $request->input('email');
    //     $usuario->loginUsuario = $request->input('login');
    //     $usuario->senhaUsuario = $request->input('senha');
    //     $usuario->save();
    //     return response()->json($usuario);
    // }

    // public function destroy($id)
    // {
    //     $usuario = Usuario::find($id);
    //     $usuario->delete();
    //     return response()->json('usuario removido com sucesso');
    // }

    // public function index()
    // {
    //     $usuario = Usuario::all();
    //     return response()->json($usuario);
    // }
}
