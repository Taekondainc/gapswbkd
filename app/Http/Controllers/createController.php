<?php

namespace App\Http\Controllers;

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

use App\Mail\gapswmail;
use App\Mail\Subscribe;
use App\Models\memorial;
use App\Models\message;
use App\Models\pdfs;
use App\Models\personell;
use App\Models\subscribers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Js;
use PhpParser\Node\Stmt\Echo_;

class createController extends Controller
{

    //create methods

    public function createuser(Request $request)
    {
        $validate = $request->validate(
            [
                'name' => 'Required|String',
                'email' => 'required|string|email',
                'contact' => 'required|string',
                'address' => 'required|string',
                'about' => 'required|string',
                'exp' => 'required|string',
                'qual' => 'required|string',
                'mbs' => 'required|string',
                'status' => 'nullable|string',
                'file' => 'image|required'
            ]
        );
        $pic = $validate['file']->getClientOriginalName();
        $ft =   Cloudder::upload($validate['file'], null, array("timeout" => 200000, 'original_filename' => $pic, "folder" => 'gapsw'));
        //  $image_url = Cloudder::show( array ("folder" => 'growgy'));
        $tg = Cloudder::getResult($ft);
        $url = $tg['secure_url'];
        $request = new applicants();
        $request->fullname = $validate['name'];
        $request->email = $validate['email'];
        $request->contact = $validate['contact'];
        $request->Address = $validate['address'];
        $request->about = $validate['about'];

        $request->exp = $validate['exp'];
        $request->mbs= $validate['mbs'];
        $request->qual = $validate['qual'];
        $request->status = "null";
        $request->image = $url;
        $request->save();

        return response($tg);
    }
    public function createpersonells(Request $request)
    {
        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'file' => 'image|required'
            ]
        );
        $pic = $validate['file']->getClientOriginalName();
        $ft =   Cloudder::upload($validate['file'], null, array("timeout" => 200000, 'original_filename' => $pic, "folder" => 'gapsw'));
        //  $image_url = Cloudder::show( array ("folder" => 'growgy'));
        $tg = Cloudder::getResult($ft);
        $url = $tg['secure_url'];
        $request = new personell();
        $request->name = $validate['title'];
        $request->description = $validate['description'];
        $request->url = $url;
        $request->save();


    }
    public function applicants(Request $request)
    {

        $request = applicants::all();


        return response($request);
    }  public function applicantview(Request $request)
    {
        $id=request("id");
        $request = applicants::where("id",$id)->get();


        return response($request);
    }
    public function createappointment(Request $request)
    {
        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'time' => 'required|string|date',
            ]
        );

        $request = new appointment();
        $request->title = $validate['title'];
        $request->description = $validate['description'];
        $request->start = $validate['time'];

        $request->save();
           $request=appointment::all();
             return response($request,200);


    }


    public function createevent(Request $request)
    {
        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'thumbnail' => 'image|required',

            ]
        );
        $ft =   Cloudder::upload($validate['thumbnail'], null, array("timeout" => 200000,  "folder" => 'gapsw'));
        //  $image_url = Cloudder::show( array ("folder" => 'growgy'));
        $tg = Cloudder::getResult($ft);

        $thumbnail = $tg['secure_url'];
        $request = new events();
        $request->title = $validate['title'];
        $request->description = $validate['description'];
        $request->thumbnail = $thumbnail;

        $request->archive = "null";
        if (request('specialdata') != null) {
            $request->sprecialdata = request('specialdata');
        } else {
            $request->sprecialdata = 'null';
        }

        $request->mediaid = $validate['title'];
        $request->save();
        $file = request('file');

        if ($file != null) {

            $file = request('file');

            foreach ($file as $filer => $x) {
                $imagename = request('mediatitle');
                echo $file[$filer];
                $request = new mediatable();
                $request->mediaid = $validate['title'];

                $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
                $tg = Cloudder::getResult($ft);
                $url = $tg['secure_url'];
                $type=$tg['resource_type'];
                $request->mediatype=$type;
                $request->url = $url;
                $imagename = request('mediatitle');
                $request->medianame = $imagename[$filer];
                $request->save();


            }
        } else {

        }

        $filed = request('filed');

        if ($filed != null) {

            $filed = request('filed');

            foreach ($filed as $filer => $x) {
                $request = new pdfs();

                $ft =   Cloudder::upload($filed[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'pdfs'));
                $tg = Cloudder::getResult($ft);
                $url = $tg['secure_url'];
                $type=$tg['resource_type'];
                $request->url = $url;
                $request->nod =$filed[$filer]->getClientOriginalName();
                $request->mediaid = $validate['title'];
                $request->save();


            }
        } else {

        }


    }



    public function createproject(Request $request)
    {
        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'thumbnail' => 'image|required',
                'date'=>'required|string'
            ]
        );
        $ft =   Cloudder::upload($validate['thumbnail'], null, array("timeout" => 200000,  "folder" => 'gapsw'));
        //  $image_url = Cloudder::show( array ("folder" => 'growgy'));
        $tg = Cloudder::getResult($ft);
        $thumbnail = $tg['secure_url'];
        $request = new projects();
        $request->title = $validate['title'];
        $request->description = $validate['description'];
        $request->thumbnail = $thumbnail;
        $request->archive = "null";
        $request->projectdate= $validate['date'];
        if (request('specialdata') != null) {
            $request->sprecialdata = request('specialdata');
        } else {
            $request->sprecialdata = 'null';
        }

            $request->mediaid = $validate['title'];
       $request->save();
         if ($file != null) {

        $file = request('file');
        foreach ($file as $filer => $x) {

            $request = new mediatable();
            $request->mediaid = $validate['title'];

            $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
            $tg = Cloudder::getResult($ft);
            $url = $tg['secure_url'];
            $type=$tg['resource_type'];
            $request->mediatype=$type;
            $request->url = $url;
            $imagename = request('mediatitle');
            $request->medianame = $imagename[$filer];
            $request->save();


        }
        } else {
        }
        $filed = request('filed');

        if ($filed != null) {

            $filed = request('filed');

            foreach ($filed as $filer => $x) {
                $request = new pdfs();

                $ft =   Cloudder::upload($filed[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'pdfs'));
                $tg = Cloudder::getResult($ft);
                $url = $tg['secure_url'];
                $type=$tg['resource_type'];
                $request->url = $url;
                $request->nod =$filed[$filer]->getClientOriginalName();
                $request->mediaid = $validate['title'];
                $request->save();


            }
        } else {

        }

    }


    public function blogpost(Request $request)
    {
        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'thumbnail' => 'image|required',
                'category' => 'String|required',
            ]
        );
        $ft =   Cloudder::upload($validate['thumbnail'], null, array("timeout" => 200000,  "folder" => 'gapsw'));
        //  $image_url = Cloudder::show( array ("folder" => 'growgy'));
        $tg = Cloudder::getResult($ft);
        $thumbnail = $tg['secure_url'];
        $request = new blog();
        $request->title = $validate['title'];
        $request->category = $validate['category'];
        $request->description = $validate['description'];
        $request->thumbnail = $thumbnail;
        $request->archive = "null";
        if (request('specialdata') != null) {
            $request->sprecialdata = request('specialdata');
        } else {
            $request->sprecialdata = 'null';
        }


            $request->mediaid = $validate['title'];


        $request->save();
        $file = request('file');
        if ($file != null) {
         $file = request('file');
        foreach ($file as $filer => $x) {

            $request = new mediatable();
            $request->mediaid = $validate['title'];

            $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
            $tg = Cloudder::getResult($ft);
            $url = $tg['secure_url'];
            $type=$tg['resource_type'];
            $request->mediatype=$type;
            $request->url = $url;
            $imagename = request('mediatitle');
            $request->medianame = $imagename[$filer];
            $request->save();


        }
        } else {
        }


        $filed = request('filed');

        if ($filed != null) {

            $filed = request('filed');

            foreach ($filed as $filer => $x) {
                $request = new pdfs();

                $ft =   Cloudder::upload($filed[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'pdfs'));
                $tg = Cloudder::getResult($ft);
                $url = $tg['secure_url'];
                $type=$tg['resource_type'];
                $request->url = $url;
                $request->nod =$filed[$filer]->getClientOriginalName();
                $request->mediaid = $validate['title'];
                $request->save();


            }
        } else {

        }



    }

    public function subscribe(Request $request)
    {   $email =request('email');
        $request=new subscribers();
        $request->email=$email;
$request->save();


    return response("Welcome Subscriber");


    }
    public function message(Request $request)
    {   $validated=$request->validate([
        'name'=>"string|Required",
        'email'=>"String|email|required",
        'message'=>"String|required"
    ]);

        $request=new message();
        $request->name=request("name");
        $request->email=request("email");
        $request->message=request("message");
$request->save();


    return response("Message sent !!!");


    } public function messages(Request $request)
    { $request=message::orderBy('created_at')->get();
         return response($request,200);
    }
    public function createmedia(Request $request)
    {

          $file = request('file');
        if ($file != null) {

        foreach ($file as $filer => $x) {

            $request = new images();
            $title = request('title');
            $request->title = $title[$filer];
            $description = request('description');
            $request->description = $description[$filer];
            $file = request('file');
            $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
            $tg = Cloudder::getResult($ft);
            $url = $tg['secure_url'];
            $type=$tg['resource_type'];
            $request->mediatype=$type;
            $request->url = $url;

            $request->save();


        }
        } else {
        }

    }


    public function users(Request $request)
    {


         $request=User::where("role","user")->select('id','name', 'email','role')->get();
         return response($request,200);
    }
    public function events(Request $request)
    {
         $request=events::orderBy('created_at')->get();
         return response($request,200);
    }public function uevents(Request $request)
    {
         $request=events::where("archive","null")->orderBy('created_at')->get();
         return response($request,200);
    }
    public function personells(Request $request)
    {
         $request=personell::orderBy('created_at')->get();
         return response($request,200);
    } public function media(Request $request)
    {
         $request=images::all();
         return response($request,200);
    }
        public function appointments(Request $request)
    {
         $request=appointment::all();
         return response($request,200);
    }  public function projects(Request $request)
    {
         $request=projects::orderBy('created_at')->get();
         return response($request,200);
    }
    public function uprojects(Request $request)
    {
         $request=projects::where("archive","null")->orderBy('created_at')->get();
         return response($request,200);
     } public function posts(Request $request)
    {
         $request=blog::orderBy('created_at')->get();
         return response($request,200);
    }public function uposts(Request $request)
    {
         $request=blog::where("archive","null")->orderBy('created_at')->get();
         return response($request,200);
    }
    public function updatepersonellid(Request $request)
    {
        $id=request('id');
         $event=personell::where("id",$id)->get();
         return response($event);
    }
    public function updateblogid(Request $request)
    {
        $id=request('id');
         $event=blog::where("id",$id)->get();
         return response($event);
    }
    public function updateeventid(Request $request)
    {
        $id=request('id');
         $event=events::where("id",$id)->get();
         return response($event);
    } public function eventid(Request $request)
    {
        $id=request('id');
         $event=events::where("id",$id)->get();
         return response($event);
    }public function projectid(Request $request)
    {
        $id=request('id');
         $event=projects::where("id",$id)->get();
         return response($event);
    }public function blogid(Request $request)
    {
        $id=request('id');
         $event=blog::where("id",$id)->get();
         return response($event);
    }
    public function updateuserid(Request $request)
    {
        $id=request('id');
         $event=User::where("id",$id)->get();
         return response($event);
    }
    public function updatemediaid(Request $request)
    {
        $id=request('id');
         $event=images::where("id",$id)->get();
         return response($event);
    }
    public function vmediaid(Request $request)
    {
        $id=request('id');
         $event=images::where("id",$id)->get();
         return response($event);
    }
    public function updateprojectid(Request $request)
    {
        $id=request('id');
         $event=projects::where("id",$id)->get();
         return response($event);
    }
    public function mediashow(Request $request)
    {

        $request=request('keyvalue');

        $request=mediatable::where("mediaid",$request)->get();
         return response($request);
    } public function pdfs(Request $request)
    {

        $request=request('keyvalue');
        $request=pdfs::where("mediaid",$request)->get();
         return response($request);
    }
    public function statusapp(Request $request)
    {

        $request=applicants::where("status","Approved")->get();
         return response($request);
    }
    public function status(Request $request)
    {
        $req=request('status');
        $request=applicants::where('status',$req)->get();
         return response($request);
    } public function delapplicantview(Request $request)
    {
        $req=request('data');
        $request=applicants::where('id',$req)->delete();
         return response($request);
    }
    public function updateapplicant(Request $request)
    {

        $req=request('approval');

        $id=request('id');


            $requpdated = applicants::where("id", $id)->update(['status' => $req]);
}
    public function updateevent(Request $request)
    {

        $req=request('updatetitle');
        $title=request('title');

        $id=request('updid');

        $file = request('file');
            $requpdated = mediatable::where("mediaid", $req)->update(['mediaid' => $title]);
            $requpdated = events::where("id", $id)->update(['mediaid' => $title]);

        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',


            ]
        );
      $chk=request('thumbnail');

        if ($chk != "empty") {

            $ft =   Cloudder::upload(request('thumbnail'), null, array("timeout" => 200000,  "folder" => 'gapsw'));

        $tg = Cloudder::getResult($ft);
        $thumbnail = $tg['secure_url'];
        $requpdated = events::where("id", $id)->update(['thumbnail' => $thumbnail]);
        } else {

        }
        $id=request('updid');
        echo $id;
        $requpdated = events::where("id", $id)->update(['title' => $validate['title']]);
        $requpdated = events::where("id", $id)->update(['description' => $validate['description']]);


        if (request('specialdata') != null) {
            $sprecialdata = request('specialdata');
            $requpdated = events::where("id", $id)->update(['sprecialdata' => $sprecialdata]);

        } else {

        }



        $file = request('file');

        if($file==null){
echo "here";
        }else{
        foreach ($file as $filer => $x) {

            $request = new mediatable();
            $request->mediaid = $validate['title'];

            $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
            $tg = Cloudder::getResult($ft);
            $url = $tg['secure_url'];
            $type=$tg['resource_type'];
            $request->mediatype=$type;
            $request->url = $url;
            $imagename = request('mediatitle');
            $request->medianame = $imagename[$filer];
            $request->save();


        }}

        $filed = request('filed');

        if ($filed != null) {

            $filed = request('filed');

            foreach ($filed as $filer => $x) {
                $request = new pdfs();

                $ft =   Cloudder::upload($filed[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'pdfs'));
                $tg = Cloudder::getResult($ft);
                $url = $tg['secure_url'];
                $type=$tg['resource_type'];
                $request->url = $url;
                $request->nod =$filed[$filer]->getClientOriginalName();
                $request->mediaid = $validate['title'];
                $request->save();


            }
        } else {

        }



    } public function updmedia(Request $request)
    {

        $id=request('updid');


      $chk=request('FILE');
       echo $chk;
        if ($chk == 'null') {
            $imagename=request('imagename');

        $requpdated = mediatable::where("id", $id)->update(['medianame' => $imagename]);

        } else {
                   $ft =   Cloudder::upload(request('FILE'), null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));

        $tg = Cloudder::getResult($ft);
        $thumbnail = $tg['secure_url'];
        $requpdated = mediatable::where("id", $id)->update(['url' => $thumbnail]);
        $thumbnaild = $tg['resource_type'];
        $requpdated = mediatable::where("id", $id)->update(['mediatype' => $thumbnaild]);
        }
        $imagename=request('imagename');

        $requpdated = mediatable::where("id", $id)->update(['medianame' => $imagename]);





    }
    public function updpersonell(Request $request)
    {


      $chk=request('FILE');

        if ($chk == 'null') {
            $name=request('name');
            $id=request('id');
      $description=request('description');
    $requpdated = personell::where("id", $id)->update(['name' => $name]);
      $requpdated = personell::where("id", $id)->update(['description' => $description]);

        } else {echo "updateimage";
            $id=request('id');

            $ft =   Cloudder::upload(request('FILE'), null, array("timeout" => 200000,
            'resource_type' => 'auto',  "folder" => 'gapsw'));
                    $tg = Cloudder::getResult($ft); $thumbnail = $tg['secure_url'];
        $requpdated = personell::where("id", $id)->update(['url' => $thumbnail]);
        $name=request('name');
  $description=request('description');
$requpdated = personell::where("id", $id)->update(['name' => $name]);
  $requpdated = personell::where("id", $id)->update(['description' => $description]);

        }





    }

    public function deleteevent(Request $request)
    {
        $id=request('data');
         $event=events::where("id",$id)->delete();
         return response($event);
         $data=events::where("id",$id)->get();
         foreach ($data as $filer => $x) {
$title=$data['$filer']['mediaid'];
         $request=mediatable::where("mediaid",$title)->delete();
         return response($request);
         $request=pdfs::where("mediaid",$title)->delete();
         return response($request); }
    }
    public function deletepdf(Request $request)
    {
        $id=request('data');

         $request=pdfs::where("id",$id)->delete();
         return response($request);
    }

    public function deletepersonell(Request $request)
    {
        $id=request('data');
         $event=personell::where("id",$id)->delete();
         return response($event);

    }
     public function deleteproject(Request $request)
    {
        $id=request('data');
         $event=projects::where("id",$id)->delete();
         return response($event);
         $data=projects::where("id",$id)->get();
         foreach ($data as $filer => $x) {
$title=$data['$filer']['mediaid'];
         $request=mediatable::where("mediaid",$title)->delete();
         return response($request); }
    }
    public function deletegmedia(Request $request)
    {
        $id=request('data');
         $event=images::where("id",$id)->delete();
         return response($event);

    }
    public function deleteuser(Request $request)
    {
        $id=request('data');
         $event=user::where("id",$id)->delete();
         return response($event);

    }

    public function deleteblogpost(Request $request)
    {
        $id=request('data');
         $event=blog::where("id",$id)->delete();
         return response($event);
         $data=blog::where("id",$id)->get();
         foreach ($data as $filer => $x) {
$title=$data['$filer']['mediaid'];
         $request=mediatable::where("mediaid",$title)->delete();
         return response($request); }
    }

    public function deletemedia(Request $request)
    {

        $request=request('data');

        $request=mediatable::where("id",$request)->delete();
         return response($request);
    }

    public function search(request $request)
    {
        $id = request("data");

        $lb = blog::where("title", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(["data" => $lb], 200);
    }

    public function updateproject(Request $request)
    {

        $req=request('updatetitle');
        $title=request('title');

        $id=request('updid');

        $file = request('file');

            $requpdated = mediatable::where("mediaid", $req)->update(['mediaid' => $title]);
            $requpdated = projects::where("id", $id)->update(['mediaid' => $title]);

        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'date'=>'required|string'

            ]
        );
      $chk=request('thumbnail');

        if ($chk != "empty") {

            $ft =   Cloudder::upload(request('thumbnail'), null, array("timeout" => 200000,  "folder" => 'gapsw'));

        $tg = Cloudder::getResult($ft);
        $thumbnail = $tg['secure_url'];
        $requpdated = projects::where("id", $id)->update(['thumbnail' => $thumbnail]);
        } else {

        }$id=request('updid');
        echo $id;
        $requpdated = projects::where("id", $id)->update(['projectdate' => $validate['date']]);
        $id=request('updid');
        echo $id;
        $requpdated = projects::where("id", $id)->update(['title' => $validate['title']]);
        $requpdated = projects::where("id", $id)->update(['description' => $validate['description']]);


        if (request('specialdata') != null) {
            $sprecialdata = request('specialdata');
            $requpdated = projects::where("id", $id)->update(['sprecialdata' => $sprecialdata]);

        } else {

        }



        $file = request('file');

        if($file==null){
echo "here";
        }else{
        foreach ($file as $filer => $x) {

            $request = new mediatable();
            $request->mediaid = $validate['title'];

            $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
            $tg = Cloudder::getResult($ft);
            $url = $tg['secure_url'];
            $type=$tg['resource_type'];
            $request->mediatype=$type;
            $request->url = $url;
            $imagename = request('mediatitle');
            $request->medianame = $imagename[$filer];
            $request->save();


        }}

        $filed = request('filed');

        if ($filed != null) {

            $filed = request('filed');

            foreach ($filed as $filer => $x) {
                $request = new pdfs();

                $ft =   Cloudder::upload($filed[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'pdfs'));
                $tg = Cloudder::getResult($ft);
                $url = $tg['secure_url'];
                $type=$tg['resource_type'];
                $request->url = $url;
                $request->nod =$filed[$filer]->getClientOriginalName();
                $request->mediaid = $validate['title'];
                $request->save();


            }
        } else {

        }


    }


    public function updateblogpost(Request $request)
    {

        $req=request('updatetitle');
        $title=request('title');

        $id=request('updid');

        $file = request('file');

        $validate = $request->validate(
            [
                'title' => 'Required|String',
                'description' => 'required|string',
                'category'=>'required|String'

            ]
        );
        $requpdated = mediatable::where("mediaid", $req)->update(['mediaid' => $title]);


             $requpdated = blog::where("id", $id)->update(['mediaid' => $validate['title']]);

      $chk=request('thumbnail');

        if ($chk != "empty") {

            $ft =   Cloudder::upload(request('thumbnail'), null, array("timeout" => 200000,  "folder" => 'gapsw'));

        $tg = Cloudder::getResult($ft);
        $thumbnail = $tg['secure_url'];
        $requpdated = blog::where("id", $id)->update(['thumbnail' => $thumbnail]);
        } else {

        }
        $id=request('updid');
        echo $id;
        $requpdated = blog::where("id", $id)->update(['title' => $validate['title']]);
        $requpdated = blog::where("id", $id)->update(['description' => $validate['description']]);
        $requpdated = blog::where("id", $id)->update(['category' => $validate['category']]);


        if (request('specialdata') != null) {
            $sprecialdata = request('specialdata');
            $requpdated = blog::where("id", $id)->update(['sprecialdata' => $sprecialdata]);

        } else {

        }



    $file = request('file');      if($file==""){
echo "here";}else{
        foreach ($file as $filer => $x) {




            $request = new mediatable();
            $request->mediaid = $validate['title'];

            $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
            $tg = Cloudder::getResult($ft);
            $url = $tg['secure_url'];
            $type=$tg['resource_type'];
            $request->mediatype=$type;
            $request->url = $url;
            $imagename = request('mediatitle');
            $request->medianame = $imagename[$filer];
            $request->save();


        }}

        $filed = request('filed');

        if ($filed != null) {

            $filed = request('filed');

            foreach ($filed as $filer => $x) {
                $request = new pdfs();

                $ft =   Cloudder::upload($filed[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'pdfs'));
                $tg = Cloudder::getResult($ft);
                $url = $tg['secure_url'];
                $type=$tg['resource_type'];
                $request->url = $url;
                $request->nod =$filed[$filer]->getClientOriginalName();
                $request->mediaid = $validate['title'];
                $request->save();


            }
        } else {

        }
    }

    public function updatemediapost(Request $request)
    {



        $file = request('FILE');

        if($file=='null'){
            $validate = $request->validate(
                [
                    'title' => 'Required|String',
                    'description' => 'required|string',


                ]
            );


            $title=request('title');

            $id=request('updid');




            $requpdated = images::where("id", $id)->update(['title' => $validate['title']]);
            $requpdated = images::where("id", $id)->update(['description' => $validate['description']]);


        }else{
            echo "here";
        foreach ($file as $filer => $x) {

            $validate = $request->validate(
                [
                    'title' => 'Required|String',
                    'description' => 'required|string',


                ]
            );


            $title=request('title');

            $id=request('updid');

            $file = request('file');


            $requpdated = images::where("id", $id)->update(['title' => $validate['title']]);
            $requpdated = images::where("id", $id)->update(['description' => $validate['description']]);




            $ft =   Cloudder::upload($file[$filer], null, array("timeout" => 200000, 'resource_type' => 'auto',  "folder" => 'gapsw'));
            $tg = Cloudder::getResult($ft);
            $url = $tg['secure_url'];
            $type=$tg['resource_type'];
            $request->mediatype=$type;
            $request->url = $url;
            $requpdated = images::where("id", $id)->update(['mediatype' => $type]);
            $requpdated = images::where("id", $id)->update(['url' => $url]);




        }}
    }

    public function updateuser(Request $request)
    {



        $password = request('password');

        if($password=='null'){
            $validated=$request->validate([
                'name'=>'Required',
                'email'=>'required|email',

                'role'=>'required ',

            ]);
             $id=request('updid');


            $name=request('name');
          $requpdated = User::where("id", $id)->update(['name' => $validated['name']]);
          $requpdated = User::where("id", $id)->update(['email' => $validated['email']]);
          $requpdated = User::where("id", $id)->update(['role' => $validated['role']]);


        }else{
            $validated=$request->validate([
                'name'=>'Required',
                'email'=>'required|email',
                'password'=>'required',
                'role'=>'required ',

            ]);
             $id=request('updid');


           $password= bcrypt($validated['password']);
          $requpdated = User::where("id", $id)->update(['name' => $validated['name']]);
          $requpdated = User::where("id", $id)->update(['email' => $validated['email']]);
          $requpdated = User::where("id", $id)->update(['password' => $password]);
          $requpdated = User::where("id", $id)->update(['role' => $validated['role']]);


        }
    }public function updateappointment(Request $request)
    {



        $id=request('id');
      $title=request('title');
      $description=request('description');
      $date=request('time');

            $requpdated =appointment::where("id", $id)->update(['title' => $title]);
            $requpdated = appointment::where("id", $id)->update(['description' => $description]);

            $requpdated = appointment::where("id", $id)->update(['start' => $date]);
          return response(200);
    }
    public function deleteappointment(Request $request)
    {
        $id=request('data');
         $event=appointment::where("id",$id)->delete();
         return response($event);
    }

    public function searchm(request $request)
    {
        $id = request("key");

        $lb =message::where("name", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(  $lb );
    }
    public function searche(request $request)
    {
        $id = request("key");

        $lb =events::where("title", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(  $lb );
    } public function searchp(request $request)
    {
        $id = request("key");

        $lb =projects::where("title", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(  $lb );
    }
    public function searcha(request $request)
    {
        $id = request("key");

        $lb =appointment::where("title", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(  $lb );
    }
    public function searcharticle(request $request)
    {
        $id = request("key");

        $lb =blog::where("title", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(  $lb );
    }
    public function searchmedia(request $request)
    {
        $id = request("key");

        $lb =images::where("title", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(  $lb );
    }
    public function searchapplicants(request $request)
    {
        $id = request("key");
$lb=applicants::where("name", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(  $lb );
    }
    public function searchusers(request $request)
    {
        $id = request("key");

        $lb =User::where("name", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(  $lb );
    }
    public function searchpersonells(request $request)
    {
        $id = request("key");

        $lb =personell::where("name", 'like', "%{$id}%")->orderByDesc('id')->get();

        return response()->json(  $lb );
    }
    public function earchive(Request $request)
    {


        $id=request('key');
        $requpdated = events::where("id", $id)->where("archive", "null")->get();
$count=count($requpdated);
        if($count ==1){
            $requpdated = events::where("id", $id)->update(['archive' => "archive"]);
            }else{

            $requpdated = events::where("id", $id)->update(['archive' => "null"]);
            }

    }
    public function parchive(Request $request)
    {

        $id=request('key');
        $requpdated = projects::where("id", $id)->where("archive", "null")->get();
$count=count($requpdated);
        if($count ==1){
            $requpdated = projects::where("id", $id)->update(['archive' => "archive"]);
            }else{

            $requpdated = projects::where("id", $id)->update(['archive' => "null"]);
            }

    }
    public function barchive(Request $request)
    {

        $id=request('key');
        $requpdated = blog::where("id", $id)->where("archive", "null")->get();
$count=count($requpdated);
        if($count ==1){
            $requpdated = blog::where("id", $id)->update(['archive' => "archive"]);
            }else{

            $requpdated = blog::where("id", $id)->update(['archive' => "null"]);
            }

    }
     public function marchive(Request $request)
    {


        $id=request('key');


            $requpdated = memorial::where("id", $id)->update(['archive' => "archive"]);
    }

    public function adprojects(Request $request)
    {

        $lb = projects::where("archive", "archive")->get();
        return response()->json(  $lb );

    }
    public function adevents(Request $request)
    {

        $lb = events::where("archive", "archive")->get();
        return response()->json(  $lb );

    }
    public function adarticles(Request $request)
    {

        $lb = blog::where("archive", "archive")->get();
        return response()->json(  $lb );

    }
}
