<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users  = \App\User::orderBy('created_at','DESC')->paginate(10);
        $data=[
            'model'=>$users,
        ];
        return view('admin.users',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password'=>'required|min:5',
        ]);
        $user = new \App\User();
        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = \Hash::make($request->get('password'));
        $user->mobile = (empty($request->get('mobile')))?'':$request->get('mobile');
        $user->save();
        return redirect('admin/users')->with('success', 'کاربر جدید اضافه شد.');
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
        $user = \App\User::find($id);
        if(empty($user)){
            abort(404);
            return;
        }
        $data = [
            'model'=>$user,
        ];
        return view('admin.user_create',$data);
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
        $user = \App\User::find($id);
        if(empty($user)){
            abort(404);
            return;
        }
        $username_unq_str='';
        $email_unq_str ='';

        if($request->get('username')!=$user->username)
            $username_unq_str = '|unique:users';

        if($request->get('email')!=$user->email)
            $email_unq_str ='|unique:users';

        $this->validate($request,[
            'name'=>'required',
            'username' => 'required'.$username_unq_str,
            'email' => 'required|email'.$email_unq_str,
            'password'=>'min:5',
        ]);

        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        if(!empty($request->get('password'))){
            $user->password = \Hash::make($request->get('password'));
        }
        $user->mobile = (empty($request->get('mobile')))?'':$request->get('mobile');
        $user->save();
        return redirect('admin/users')->with('success', 'کاربر ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \App\User::find($id);
        if(empty($user)){
            echo "ERROR";
            return;
        }
        $user->delete();
        echo "DESTORY";
        return;
    }
}
