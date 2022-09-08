<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        try {
            $attr = $request->all();

            if(!isset($request->role)){
                $attr["role"] = 2;
            }else{
                $attr["role"] = $request->role;
            }

            if(User::where('email',$attr["email"])->exists()){
                return $this->error("El usuario que intenta ingresar ya existe.",200,[]);
            }

            $attr["confirmation_code"] = Str::random(35);

            $user = User::create([
                'name' => $attr['name'],
                'phone' => $attr['phone'],
                'dni' => $attr['dni'],
                'password' => bcrypt($attr['password']),
                'email' => $attr['email'],
                'role_id' => $attr["role"],
                'institution_id' => $attr["institution"]
            ]);
            //si el rol no es el de cliente, no hace falta la verificación

            return $this->success(["user"=>$user],"Usuario registrado correctamente.");
        } catch (\Exception $e) {
            return $this->error("Ha ocurrido un error en el servidor",500,$e);
        }
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(!isset($credentials["email"])){
            return response()->json([
                "error"=>true,
                "message"=>"Debe ingresar un correo"
            ]);
        }

        $credentials["email"] = strtolower($credentials["email"]);

        $user = User::where('email',$credentials["email"])->first();

        if(empty($user)) {
            return response()->json([
                "error"=>true,
                "message"=>"No se ha encontrado el usuario con el correo indicado."
            ]);
        }

        try {
            //si no se crea el token
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(["error"=>true,
                                        "message"=>"Las credenciales son incorrectas"]);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Ha ocurrido un error en el servidor'], 500);
        }

        $user = Auth::user();
        return response()->json(["user"=>$user,"token"=>$token]);

    }

    public function logout( Request $request ) {
        Auth::logout();
        $token = $request->header('Authorization');

        try {
            JWTAuth::parseToken()->invalidate( $token );
            return $this->success([],'Se ha cerrado la sesión correctamente');
        } catch ( TokenExpiredException $exception ) {
            return response()->json( [
                'error'   => true,
                'message' => trans( 'Token expirado' )

            ], 401 );
        } catch ( TokenInvalidException $exception ) {
            return response()->json( [
                'error'   => true,
                'message' => 'Token inválido'
            ], 401 );

        } catch ( JWTException $exception ) {
            return response()->json( [
                'error'   => true,
                'message' => trans( 'Falta el token' )
            ], 500 );
        }
    }
}
