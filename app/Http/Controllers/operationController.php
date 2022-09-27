<?php

namespace App\Http\Controllers;

use App\Models\applicants;
use Illuminate\Http\Request;

use Cloudder;
class operation extends Controller
{
   //create methods

   public function createuser(Request $request )
   { 
       $validate=$request->validate([
           'name'=>'Required|String',
           'email'=>'required|string|email',
           'contact'=>'required|string',
           'address'=>'required|string',
           'about'=>'required|string',
           'status'=>'nullable|string',
           'image'=>'image|required']
       );
       $pic = $validate['image']->getClientOriginalName();
    $ft=   Cloudder::upload($validate['image'], null, array("timeout" => 200000, 'original_filename' => $pic, "folder" => 'gapsw'));
       //  $image_url = Cloudder::show( array ("folder" => 'growgy'));
       $tg = Cloudder::getResult($ft);
       $url = $tg['url'];
       $request=new applicants();
$request->fullname=$validate['name'];
$request->email=$validate['email'];
$request->contact=$validate['contact'];
$request->Address=$validate['address'];
$request->about=$validate['about'];
$request->status=$validate['status'];
$request->image=$url;
$request->save();


   }

    
}
