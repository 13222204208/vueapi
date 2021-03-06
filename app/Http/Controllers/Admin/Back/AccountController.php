<?php

namespace App\Http\Controllers\Admin\Back;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $all= $request->all();
        $limit = $all['limit'];
        $page = ($all['page'] -1)*$limit;
        $username = false;
        if($request->has('username')){
            $username = $all['username'];
        }
    
        $item = Admin::when($username,function($query) use ($username){
            return $query->where('username','like','%'.$username.'%');
        })->skip($page)->take($limit)->get();
        $total = Admin::count();
        $data['item'] = $item;
        $data['total'] = $total;
        $data['all'] = $all;
        return $this->success($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['username','password','name']);
        $data['password'] = Hash::make($data['password']);
         
        $state= Admin::create($data);
        if($state){
            return $this->success();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $state = Admin::where('id',$id)->update([
            'name' => $request->name
            ]    
        );
        if($state){
            return $this->success();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $state = Admin::destroy($id);
        if($state){
            return $this->success();
        }
    }
}
