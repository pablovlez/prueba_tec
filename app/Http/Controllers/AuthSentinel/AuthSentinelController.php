<?php

namespace App\Http\Controllers\AuthSentinel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Estudiante;
use App\Model\FamiliarEstudiante;
use Mail;

class AuthSentinelController extends Controller {

    public function postLogin() {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required',
        );

        $validator = \Validator::make(\Input::all(), $rules);

        // Login Process
        if (!$validator->fails()) {

            $credentials = [
                'email' => \Input::get('email'),
                'password' => \Input::get('password'),
            ];

            $user = \Sentinel::authenticate($credentials);

            if ($user === false) {
                // error de credenciales
                return response()->json(['msg' => 'No se puede Autenticar'], 401);
            } else {
                // login Success
                $user = User::where('id', '=', $user->id)->with('tipoPersona')->first();
                if($user->tipo_persona !== null){
                   $user->tipo_persona->persona;
                }
                return response()->json(['success' => true, 'data' => $user], 200);
            }
        } else {
            // Falla validador del Input
            return response()->json([ 'msg' => 'Error Login', 'data' => $validator->errors()], 401);
        }
    }

    public function getLogout() {
        \Sentinel::logout();
        \Session::flush();
        return response()->json(['msg' => 'Logout'], 200);
    }

    public function getAuthuser() {
        $user = \Sentinel::getUser();
        if (is_null($user)) {
            return response()->json(['success' => false, 'data' => ['code' => 401], 'msg' =>  'No hay usuario', 'error' => 40], 200);
        } else {
            $user = User::with('perfil')->where('id', '=', $user->id)->with('tipoPersona')->with('roles')->first();
            if($user->tipo_persona !== null){
                $user->tipo_persona->persona;
            }


            $tipopersona = explode('\\' , $user->tipo_persona_type);
            $user->tipo_persona_type = $tipopersona[2];

            if($user->tipo_persona_type == "Familiar")
            {
                //$estudiante = Estudiante::with('persona')->find($user->tipo_persona->tipo_persona_id);
                //$user->nombre_estudiante = $estudiante->persona->nombre_completo;

                $familiarEstudiantes = FamiliarEstudiante::where('familiar_id', '=', $user->tipo_persona_id)->get();
                $array_estudiantes = array();
                foreach ($familiarEstudiantes as $familiarEstudiante) {
                    $estudianteObj = Estudiante::with('user')->find($familiarEstudiante->estudiante_id);
                    if(!is_null($estudianteObj)){
                        $estudiante = new \stdClass();
                        $estudiante->id = $estudianteObj->id;
                        $estudiante->user_id = $estudianteObj->user[0]->id;
                        $estudiante->nombre_completo = $estudianteObj->persona->nombre_completo;
                        $estudiante->path_foto_persona = $estudianteObj->persona->path_foto_persona;
                        $array_estudiantes[] = $estudiante;
                    }
                }
                $user->estudiantes = $array_estudiantes;
            }


            switch ($tipopersona[2]) {
                case 'UsuarioInstitucion':
                    $user->tipo_persona_type = 'Administrativo';
                    break;
                
                default:
                    $user->tipo_persona_type;
                    break;
            }

            return response()->json(['success' => true , 'msg' => 'usuario', 'data' => $user], 200);
        }
    }

    public function postCreateuserwithcredentials() {
        $credentials = [
            'email' => \Input::get('email'),
            'password' => \Input::get('password'),
        ];

        $rules = array(
            'email' => 'required|email|unique:users',
            'password' => 'required',
        );

        $validator = \Validator::make(\Input::all(), $rules);

        // Login Process
        if ($validator->fails()) {
            \Log::error($validator->errors());
            return response()->json(['msg' => 'No se pudo crear Usuario', 'data' => $validator->errors()], 200);
        }

        try {

            $user = \Sentinel::registerAndActivate($credentials);
            return response()->json(['msg' => 'Usuario', 'data' => $user], 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'No se pudo crear Usuario'], 200);
        }
    }

    public function getConfigurarroles() {
        $role = \Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Preinscripcion',
            'slug' => 'Preinscripcion',
        ]);
        $role->permissions = [
            'preinscripcion.aprobar' => true,
            'preinscripcion.denegar' => true,
            'preinscripcion.index' => true,
            'preinscripcion.aprobados' => true,
            'preinscripcion.denegados' => true,
            'estudiante.index' => true,
            'estudiante.show' => true,
        ];

        $role->save();

        $user = \Sentinel::findById(18);

        $role->users()->attach($user);
    }

    public function postPasswordreminder()
    {
        $rules = array(
            'email' => 'required',
            'url' => 'required'
        );

        $validator = \Validator::make(\Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'msg' => 'Error', 'data' => $validator->errors()], 200);
        }

        $user = User::where('email', '=', \Input::get('email'))->first();

        if(is_null($user)){
            return response()->json(['success' => false, 'data' => 'Usuario no válido.'], 200);
        }

        $sentinelUser = \Sentinel::findById($user->id);

        $reminder = \Reminder::exists($sentinelUser);
        if ($reminder == false) {
            $reminder = \Reminder::create($sentinelUser);
        }

        $obj = new \stdClass();
        $obj->code = $reminder->code;
        $obj->user_id = $reminder->user_id;
        //$url = \Input::get('url').'?code='.$obj->code . '&user_id=' . $obj->user_id. '&change=true'.'#authentication.passwordchange';
        $url = 'www.present.com.co/'.'?code='.$obj->code . '&user_id=' . $obj->user_id. '&change=true'.'#authentication.passwordchange';
        $obj->url = $url;
        $obj->email = \Input::get('email');
        $obj->name = $user->first_name.' '.$user->last_name;

        $email = Mail::send('emails.auth.password_reminder', ['obj' => $obj], function ($m) use ($obj) {
            $m->from('info@institucion_1.edu.co', 'Institución 1');
            $m->to($obj->email, $obj->name)->subject('Reset Password!');
        });
        
        return response()->json(['success' => true, 'data' => $obj], 200);
        
    }

    public function postPasswordreset()
    {
        $rules = array(
            'password' => 'required',
            'code' => 'required',
            'user_id' => 'required',
        );

        $validator = \Validator::make(\Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'msg' => 'Error', 'data' => $validator->errors()], 200);
        }

        $user = \Sentinel::findById(\Input::get('user_id'));

        if(is_null($user)){
            return response()->json(['success' => false, 'data' => 'Usuario no válido.'], 200);
        }

        $reminder_code = \Input::get('code');
        $new_password = \Input::get('password');

        if ($reminder = \Reminder::complete($user, $reminder_code, $new_password))
        {
            return response()->json(['success' => true, 'data' => 'Cambio de contraseña exitoso.'], 200);
        }
        else
        {
            return response()->json(['success' => false, 'data' => 'Cambio de contraseña NO pudo ser completado.'], 406);
        }

    }

    // Funcion para cambiar la contraseña de un usurio desde el administrador
    public function cambiarPasswordUsuario($user_id, $password)
    {
        $sentinelUser = \Sentinel::findById($user_id);

        if(is_null($sentinelUser)){
            return response()->json(['success' => false, 'data' => 'Usuario no válido.'], 200);
        }

        $reminder = \Reminder::create($sentinelUser);
        $reminder->code;

        if ($reminder = \Reminder::complete($sentinelUser, $reminder->code, $password))
        {
            return response()->json(['success' => true, 'data' => 'Cambio de contraseña exitoso.'], 200);
        }
        else
        {
            return response()->json(['success' => false, 'data' => 'Cambio de contraseña NO pudo ser completado.'], 406);
        }

    }

}
