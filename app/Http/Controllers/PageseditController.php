<?php

namespace App\Http\Controllers;

use App\Models\pagemedia;
use App\Models\pages;
use Illuminate\Http\Request;
use Cloudder;
use App\Models\applicants;
use App\Models\appointment;
use App\Models\blog;
use App\Models\events;
use App\Models\images;
use App\Models\mediatable;
use App\Models\projects;
use App\Models\User;
use App\Models\videos;
use App\Models\webpage;
use Illuminate\Support\Js;
use PhpParser\Node\Stmt\Echo_;

class PageseditController extends Controller
{
    public function createpage(Request $request)
    {
        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
               
                'section' => 'Required|String',
                'pagename' => 'Required|String',
              
            ]
        );
       
        $request = new pages();
        $request->title = $validate['title'];
        $request->description = $validate['description'];

    
        if (request('grid') != null) {
            $request->grid = request('grid');
        } else {
            $request->grid = null;
        
        } if (request('ip') != null) {
            $request->imageposition = request('ip');

        } else {
            $request->imageposition= 'null';
        }
        
        if (request('specialdata') != null) {
            $request->sprecialdata = request('specialdata');
        } else {
            $request->sprecialdata = 'null';
        }
        $request->sectionname = $validate['section'];
        $request->pagename = $validate['pagename'];
   
        $file = request('FILE');
        if ($file != null) {
            $request->mediaid = $validate['section'].$validate['pagename'];
        } else {
            $request->mediaid = 'null';
        } $request->save();
        if ($file != null) {
           
            $file = request('file');
            foreach ($file as $filer => $x) {
    
                $request = new pagemedia();
                $request->mediaid =  $validate['section'].$validate['pagename'];
    
                $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
                $tg = Cloudder::getResult($ft);
                $url = $tg['secure_url'];
                $type=$tg['resource_type'];
                $request->mediatype=$type;
                $request->url = $url;
                $imagename = request('title');
                $request->title = $imagename[$filer];
                $request->save();
    
               
            }
        } else {
          
        }
       
    }
    public function pagesections(Request $request)
    { 
      
        $section = request('pages');
        if ($section=="pages") {
            $request=pages::all();
            return response($request);  
        } else {
             $request=pages::where("pagename",$section)->get();
         return response($request);  
        }
        
      
    }   public function webpagenames(Request $request)
    { 
      
        $section = request('pages');
        $request=webpage::where("pagename",$section)->get();
        return response($request);
      
    }   public function pagewebs(Request $request)
    {  
        $request=webpage::all();
        return response($request);
      
    }
    public function pagenames(Request $request)
    { 
      
      
            $request=webpage::all();
            return response($request);  
         
        
      
    }
    
    public function pages(Request $request)
    { 
      
       
            $request=pages::all();
            return response($request);  
        
        
      
    }
    public function deletewebpage(Request $request)
    {
        $id=request('data');
         $event=webpage::where("id",$id)->delete();
         return response($event);
         $data=webpage::where("id",$id)->get();
         foreach ($data as $filer => $x) {
$title=$data['$filer']['pagename'];
$datad=pages::where("pagename",$title)->get();
         
foreach ($datad as $fil  => $x) {
    
$titled=$data['$filer']['mediaid'];
         $request=pagemedia::where("mediaid",$titled)->delete();}
         $request=pages::where("pagename",$title)->delete();
          
         return response($request); }
    }  public function deletepage(Request $request)
    {
        $id=request('data');
         $event=pages::where("id",$id)->delete();
         return response($event);
         $data=pages::where("id",$id)->get();
         foreach ($data as $filer => $x) {
$title=$data['$filer']['mediaid'];
         $request=pagemedia::where("mediaid",$title)->delete();
         return response($request); }
    } 

    public function updateppage(Request $request)
    {
       
        $id=request('updid');
      
        $file = request('FILE');
        
        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'specialdata' => 'string|required',
                'section' => 'Required|String', 
                'ip' => 'Required|String',
                'grid'=>'Required|String'
            ]
        );
    
        $id=request('updid');
        echo $id; 
        $requpdated = pages::where("id", $id)->update(['title' => $validate['title']]);
        $requpdated = pages::where("id", $id)->update(['description' => $validate['description']]);
        $requpdated = pages::where("id", $id)->update(['sprecialdata' => $validate['specialdata']]);
        $requpdated = pages::where("id", $id)->update(['sectionname' => $validate['section']]);
        $requpdated = pages::where("id", $id)->update(['imageposition' => $validate['ip']]);
        $requpdated = pages::where("id", $id)->update(['grid' => $validate['grid']]);
 
        $file = request('file');
         
        if($file==null){
 
        }else{
            $cav=request('section');
            $pate=request('pagename'); 
            $cp=$cav.$pate;
            $requpdated = pages::where("id", $id)->update(['mediaid' => $cp]);
 
        foreach ($file as $filer => $x) {

            $request = new pagemedia();
             
            $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
            $tg = Cloudder::getResult($ft);
            $url = $tg['secure_url'];
            $type=$tg['resource_type'];
            $request->mediatype=$type;
            $request->mediaid=$cp;
            $request->url = $url;
            $imagename = request('mediatitle');
            $request->title = $imagename[$filer];
            $request->save();

           
        }}
    }
    public function mediashow(Request $request)
    {
      
        $request=request('keyvalue');
       
        $request=pagemedia::where("mediaid",$request)->get();
         return response($request);
    }
    public function updatepid(Request $request)
    {
        $id=request('id');
         $event=pages::where("id",$id)->get();
         return response($event);
    }public function webpageid(Request $request)
    {
        $id=request('id');
         $event=webpage::where("id",$id)->get();
         return response($event);
    }public function webpagedid(Request $request)
    {
        $id=request('id');
         $event=webpage::where("pagename",$id)->get();
         return response($event);
    }
    public function updatepmedia(Request $request)
    {
            
        $id=request('updid');
      
         
      $chk=request('FILE');
       echo $chk;
        if ($chk == 'null') {
            $imagename=request('mediatitle');
        
        $requpdated = pagemedia::where("id", $id)->update(['title' => $imagename]);
            
        } else {
                   $ft =   Cloudder::upload(request('FILE'), null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
        
        $tg = Cloudder::getResult($ft);
        $thumbnail = $tg['secure_url'];
        $requpdated = pagemedia::where("id", $id)->update(['url' => $thumbnail]);  
        $thumbnaild = $tg['resource_type'];
        $requpdated =pagemedia::where("id", $id)->update(['mediatype' => $thumbnaild]);
        $imagename=request('mediatitle');
        
        $requpdated = pagemedia::where("id", $id)->update(['title' => $imagename]);
           
        }
      
       
    } public function deletepmedia(Request $request)
    {
        $id=request('data');
         $event=pagemedia::where("id",$id)->delete();
         return response($event);
        
    }

    public function ammedia(Request $request)
    {
         
  
        $request = new pagemedia();
                $request->mediaid = request('mtitle');
    
                $ft =   Cloudder::upload(request('file'), null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
                $tg = Cloudder::getResult($ft);
                $url = $tg['secure_url'];
                $type=$tg['resource_type'];
                $request->mediatype=$type;
                $request->url = $url; 
                $request->title =request('title');
                $request->save();

           
        
       
    }






    public function createwebpage(Request $request)
    {
        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'specialdata' => 'string|required',
                 
                'pagename' => 'Required|String',
                 
            ]
        );
       
        $request = new webpage();
        $request->title = $validate['title'];
        $request->description = $validate['description'];
        if (request('linkn') != null) {
            $request->linkname = request('linkn');
        } else {
            $request->linkname = null;
        
        } if (request('linka') != null) {
            $request->linkaddress = request('linka');
        } else {
            $request->linkaddress = null;
        
        } 
        if(request('file')!=null){
            $pic = request('file')->getClientOriginalName();
        $ft =   Cloudder::upload(request('file'), null, array("timeout" => 200000,'resource_type' => 'auto', 'original_filename' => $pic, "folder" => 'gapsw'));
        //  $image_url = Cloudder::show( array ("folder" => 'growgy'));
        $tg = Cloudder::getResult($ft); $type=$tg['resource_type'];
        $url = $tg['secure_url']; 
         $request->mediatype = $type;
            $request->image = $url;
        } else {
            $request->image = null;
            $request->mediatype = null;
        } 
    
        if (request('grid') != null) {
            $request->grid = request('grid');
        } else {
            $request->grid = null;
        
        } if (request('ip') != null) {
            $request->ip = request('ip');

        } else {
            $request->ip= 'null';
        }
        
        if (request('specialdata') != null) {
            $request->sprecialdata = request('specialdata');
        } else {
            $request->sprecialdata = 'null';
        }
        
        $request->pagename = $validate['pagename'];
    
          $request->save();
        
       
    }
    public function updatewebid(Request $request)
    {
       
        $id=request('updid');
      
        $file = request('FILE');
        
        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'specialdata' => 'string|required',
                'pagename' => 'Required|String', 
                'ip' => 'Required|String',
                'linkaddress'=>'required|String',
                'linkname'=>'required|String',
                'grid'=>'Required|String'
            ]
        );
    
        $id=request('updid');
        echo $id; 
        $requpdated = webpage::where("id", $id)->update(['title' => $validate['title']]);
        $requpdated = webpage::where("id", $id)->update(['description' => $validate['description']]);
        $requpdated = webpage::where("id", $id)->update(['sprecialdata' => $validate['specialdata']]);
        $requpdated = webpage::where("id", $id)->update(['pagename' => $validate['pagename']]);
        $requpdated = webpage::where("id", $id)->update(['ip' => $validate['ip']]);
        $requpdated = webpage::where("id", $id)->update(['grid' => $validate['grid']]);
        $requpdated = webpage::where("id", $id)->update(['linkaddress' => $validate['linkaddress']]);
        $requpdated = webpage::where("id", $id)->update(['linkname' => $validate['linkname']]);
 
        $file = request('file');
         
        if($file=='null'){
 
        }else{
            $pic = request('file')->getClientOriginalName();
            $ft =   Cloudder::upload(request('file'), null, array("timeout" => 200000,'resource_type' => 'auto', 'original_filename' => $pic, "folder" => 'gapsw'));
           
            $tg = Cloudder::getResult($ft); $type=$tg['resource_type'];
            $url = $tg['secure_url']; 
            
                $requpdated = webpage::where("id", $id)->update(['image' => $url]);
                $requpdated = webpage::where("id", $id)->update(['mediatype' => $type]);
         
           
        }
    }
}
