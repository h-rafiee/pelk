<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = \App\Administrator::orderBy('created_at','DESC')->paginate(10);
        $data=[
            'model'=>$admins,
        ];
        return view('admin.administrators',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.administrator_create');
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
            'username' => 'required|unique:administrators',
            'email' => 'required|email|unique:administrators',
            'password'=>'required|min:5',
        ]);
        $admin = new \App\Administrator();
        $admin->name = $request->get('name');
        $admin->username = $request->get('username');
        $admin->email = $request->get('email');
        $admin->password = \Hash::make($request->get('password'));
        $admin->mobile = (empty($request->get('mobile')))?'':$request->get('mobile');
        $admin->save();
        return redirect('admin/administrators')->with('success', 'مدیر ارشد جدید اضافه شد.');
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
        $admin = \App\Administrator::find($id);
        if(empty($admin)){
            abort(404);
            return;
        }
        $data = [
            'model'=>$admin,
        ];
        return view('admin.administrator_create',$data);
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
        $admin = \App\Administrator::find($id);
        if(empty($admin)){
            abort(404);
            return;
        }
        $username_unq_str='';
        $email_unq_str ='';

        if($request->get('username')!=$admin->username)
            $username_unq_str = '|unique:administrators';

        if($request->get('email')!=$admin->email)
            $email_unq_str ='|unique:administrators';

        $this->validate($request,[
            'name'=>'required',
            'username' => 'required'.$username_unq_str,
            'email' => 'required|email'.$email_unq_str,
            'password'=>'min:5',
        ]);

        $admin->name = $request->get('name');
        $admin->username = $request->get('username');
        $admin->email = $request->get('email');
        if(!empty($request->get('password'))){
            $admin->password = \Hash::make($request->get('password'));
        }
        $admin->mobile = (empty($request->get('mobile')))?'':$request->get('mobile');
        $admin->save();
        return redirect('admin/administrators')->with('success', 'مدیر ارشد ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = \App\Administrator::find($id);
        if(empty($admin)){
            echo "ERROR";
            return;
        }
        $admin->delete();
        echo "DESTORY";
        return;
    }
}
