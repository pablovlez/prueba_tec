<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \App\User;

class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $users =  User::with('cities')->get();
        return response()->json($users);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {

        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'age' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        } else {
            $user = new User();
            $user->fill($request->all());
            $user->save();
            return response()->json($user);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    public function saveCities(Request $request, $user_id)
    {
        $rules = array(
            'cities_id' => 'required',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 200);
        } else {
            $user = User::find($user_id);
            if(!is_null($user)){
                $user->cities()->sync(explode( ',', $request->cities_id));
                $user->save();
                
            }
            return response()->json($user, 200);

        }
    }

    public function setDefaultCity(Request $request, $user_id)
    {
        $rules = array(
            'city_id' => 'required',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 200);
        } else {
            $user = User::with('cities')->find($user_id);
            if(!is_null($user)){
                foreach ($user->cities as $city) {

                    $user->cities()->detach([$city->id]);
                    if($city->id == $request->city_id){
                        $user->cities()->attach([$city->id => ['default' => true]]);
                    } else {
                        $user->cities()->attach([$city->id => ['default' => false]]);
                    }
                }
            }
            return response()->json($user, 200);
        }
    }
}
