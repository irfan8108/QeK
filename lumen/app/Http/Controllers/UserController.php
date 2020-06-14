<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // return response(json_encode($request->all()), 200);

        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'mobile' => 'min:10|max:15',
         ];
 
        $customMessages = [
             'required' => 'Please fill attribute :attribute'
        ];
        $this->validate($request, $rules, $customMessages);
 
        try {
            $hasher = app()->make('hash');
            
            $user['name'] = $request->input('name');
            $user['email'] = $request->input('email');
            $user['password'] = $hasher->make($request->input('password'));
            $user['api_token'] = '';
            $user['status'] = true;

            if( $request->has('role') ){
                
                // Assign User Role/Permission Role, Otherwise will set default for Normal User as u
                $user['role'] = $request->input('role');

                // DeActivate the Status immediately, if User is a Admin. Subject to approve by Super Admin
                if($request->input('role') == 'a' ){
                    $user['status'] = false;
                }
            }

            // Set User Mobile, if Exist
            if( $request->has('mobile') ){
                $user['mobile'] = $request->input('mobile');
            }

 
            $save = User::create($user);
            $res['status'] = true;
            $res['message'] = 'Registration success!';
            return response($res, 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['status'] = false;
            $res['message'] = $ex->getMessage();
            return response($res, 500);
        }
    }
 
    public function getUsers()
    {
        $user = User::all();
        if ($user) {
              $res['status'] = true;
              $res['message'] = 'Users successfully retrived';
              $res['data'] = $user;
 
              return response($res);
        }else{
          $res['status'] = false;
          $res['message'] = 'Cannot find user!';
 
          return response($res);
        }
    }
}