<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Hashing;

class AdminController extends Controller
{
    public function index()
    {
        return view('layout_login.login');
    }

    public function auth(Request $request)
    {
        //Recebe os valores da requisição
        $credentials = $request->only('email', 'password');  

        //Verifica se o token é valido
        $request->validate(['_token' => 'required|in:'.csrf_token(),]); 
        
        // Tenta autenticar o usuário
        if (Auth::attempt($credentials)) {

            $user = User::where('email', $credentials['email'])->first();

            if(!$user || !password_verify($credentials['password'], $user->password)){
                return back()->withErrors(['message' => 'Credenciais inválidas.']);
            }

            $userData = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ];
            session()->start();
            $request->session()->put('user', $userData);

            // Redireciona o usuário para outra view
            return redirect()->action([MainController::class, 'index']); //45144617Jow@
        }else{
            // Se as credenciais estiverem incorretas, exibe uma mensagem de erro
            return back()->withErrors(['message' => 'Credenciais inválidas.']);
        }
    }

    public function logout(Request $request)
    {
        $request->validate(['_token' => 'required|in:'.csrf_token(),]); 

        session()->forget('user');
        return redirect()->action([AdminController::class, 'index'])->with('success', 'Deslogado com sucesso.');
    }

}
