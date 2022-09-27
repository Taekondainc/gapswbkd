<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\mediaController;
use  App\Http\Controllers\createController;
use App\Http\Controllers\PageseditController;
use GuzzleHttp\Middleware;
use PHPUnit\TextUI\XmlConfiguration\Group;
use PHPUnit\TextUI\XmlConfiguration\Groups;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/logout',  [AuthController::class, 'logout']);
Route::middleware(['auth:sanctum'])->group(function () {

    //Auth
    Route::post('/Admininfoupdate',  [createController::class, 'Admininfoupdate']);



    Route::post('msearch',  [createController::class, 'searchm']);
    Route::post('esearch',  [createController::class, 'searche']);
    Route::post('psearch',  [createController::class, 'searchp']);
    Route::post('asearch',  [createController::class, 'searcha']);
    Route::post('articlesearch',  [createController::class, 'searcharticle']);
    Route::post('applicantsearch',  [createController::class, 'searchapplicants']);
    Route::post('mediasearch',  [createController::class, 'searchmedia']);
    Route::post('usersearched',  [createController::class, 'searchusers']);
    Route::post('personellsearched',  [createController::class, 'searchpersonells']);


    //creates routes
    Route::post('/createuser',  [createController::class, 'createuser']);
    Route::post('/createblogpost',  [createController::class, 'blogpost']);
    Route::post('/createproject',  [createController::class, 'createproject']);
    Route::post('/createevent',  [createController::class, 'createevent']);
    Route::post('/createappointment',  [createController::class, 'createappointment']);
    Route::post('/createpersonell',  [createController::class, 'createpersonells']);
    Route::post('/ammedia',  [PageseditController::class, 'ammedia']);


    //media
    Route::post('/createmedia',  [createController::class, 'createmedia']);
    Route::post('/updatemediapost',  [createController::class, 'updatemediapost']);
    Route::post('/deletegmedia',  [createController::class, 'deletegmedia']);
    Route::post('/generalupload',  [createController::class, 'media']);
    Route::post('/updatemediaid',  [createController::class, 'updatemediaid']);
    //update routes
    Route::post('/updateuser',  [createController::class, 'updateuser']);
    Route::post('/updadminregisteruser',  [AuthController::class, 'updateregister']);
    Route::post('/updateblog',  [createController::class, 'updateblogpost']);
    Route::post('/updateproject',  [createController::class, 'updateproject']);
    Route::post('/updatemedia',  [createController::class, 'updmedia']);

    Route::post('/updatepersonell',  [createController::class, 'updpersonell']);
    Route::post('/updateevent',  [createController::class, 'updateevent']);


    Route::post('/updateappointments',  [createController::class, 'updateappointment']);

    //update ids
    Route::post('/updateuserid',  [createController::class, 'updateuserid']);
    Route::post('/updadminregisteruserid',  [AuthController::class, 'updateregisterid']);
    Route::post('/updateblogid',  [createController::class, 'updateblogid']);
    Route::post('/updatepersonellid',  [createController::class, 'updatepersonellid']);
    Route::post('/updateprojectid',  [createController::class, 'updateprojectid']);
    Route::post('/updateeventid',  [createController::class, 'updateeventid']);
    Route::post('/updateapplicant',  [createController::class, 'updateapplicant']);

    Route::post('/status',  [createController::class, 'status']);


    //delete routes
    Route::post('/deleteuser',  [createController::class, 'deleteuser']);
    Route::post('/deladminregisteruser',  [AuthController::class, 'deleteregister']);
    Route::post('/deleteblog',  [createController::class, 'deleteblogpost']);
    Route::post('/deleteproject',  [createController::class, 'deleteproject']);
    Route::post('/applicantid',  [createController::class, 'applicantview']);
    Route::post('/deleteapplicant',  [createController::class, 'delapplicantview']);
    Route::post('/deletepersonell',  [createController::class, 'deletepersonell']);
    Route::post('/updatepdf',  [createController::class, 'deletepdf']);


    Route::post('/deleteappointment',  [createController::class, 'deleteappointment']);
    Route::post('/deletemedia',  [createController::class, 'deletemedia']);
    Route::post('/deleteevent',  [createController::class, 'deleteevent']);
    Route::post('/subscriberdelete',  [createController::class, 'subscriberdelete']);

    //view routes
    Route::post('/users',  [createController::class, 'users']);
    Route::post('/register',  [AuthController::class, 'register']);
    Route::post('/blogposts',  [createController::class, 'posts']);
    Route::post('/projects',  [createController::class, 'projects']);
    Route::post('/events',  [createController::class, 'events']);
    Route::post('/appointments',  [createController::class, 'appointments']);
    Route::post('/medialow',  [createController::class, 'mediashow']);
    Route::post('/subscribers',  [createController::class, 'subscribers']);
    Route::post('/personells',  [createController::class, 'personells']);

    //Sub view routes
       Route::post('/users',  [createController::class, 'users']);
       Route::post('/register',  [AuthController::class, 'register']);
       Route::post('/blogposts',  [createController::class, 'posts']);
       Route::post('/projects',  [createController::class, 'projects']);
       Route::post('/events',  [createController::class, 'events']);
       Route::post('/applicants',  [createController::class, 'applicants']);
       Route::post('/subscribers',  [createController::class, 'subscribers']);

    //page edit routes
    Route::post('/createpage',  [PageseditController::class, 'createpage']);
    Route::post('/mediaget',  [PageseditController::class, 'mediashow']);
    Route::post('/pagesections',  [PageseditController::class, 'pagesections']);
    Route::post('/pages',  [PageseditController::class, 'pages']);
    Route::post('/deletepage',  [PageseditController::class, 'deletepage']);
    Route::post('/updateppage',  [PageseditController::class, 'updateppage']);
    Route::post('/updatepid',  [PageseditController::class, 'updatepid']);
    Route::post('/updatepmedia',  [PageseditController::class, 'updatepmedia']);
    Route::post('/deletepmedia',  [PageseditController::class, 'deletepmedia']);

    Route::post('/createwebpage',  [PageseditController::class, 'createwebpage']);
    Route::post('/mediaget',  [PageseditController::class, 'mediashow']);
    Route::post('/pagesections',  [PageseditController::class, 'pagesections']);

    Route::post('/pagenames',  [PageseditController::class, 'pagenames']);
    Route::post('/updatewebid',  [PageseditController::class, 'updatewebid']);
    Route::post('/webpagesections',  [PageseditController::class, 'webpagenames']);
    Route::post('/pages',  [PageseditController::class, 'pages']);
    Route::post('/deletewebpage',  [PageseditController::class, 'deletewebpage']);
    Route::post('/webpageid',  [PageseditController::class, 'webpageid']);
});

Route::post('/gapswregister',  [AuthController::class, 'gapswregister']);
Route::post('/adminregisteruser',  [AuthController::class, 'Admingapswregister']);
Route::post('/loginuser',  [AuthController::class, 'logingapsw']);
Route::post('/userblog',  [createController::class, 'posts']);
Route::post('/projects',  [createController::class, 'projects']);
Route::post('/images',  [createController::class, 'images']);
Route::post('/message',  [createController::class, 'message']);

Route::post('/messages',  [createController::class, 'messages']);
Route::post('/userevents',  [createController::class, 'events']);

Route::post('/eventid',  [createController::class, 'eventid']);
Route::post('/subscriberadd',  [createController::class, 'subscriberadd']);
Route::post('/unsubscribe',  [createController::class, 'unsubscribe']);
Route::post('/createuser',  [createController::class, 'createuser']);
  //Sub view routes

  Route::post('/blogpostsbyid',  [createController::class, 'postsid']);
  Route::post('/projectsbyid',  [createController::class, 'projectsid']);

  Route::post('/medialowuser',  [createController::class, 'mediashow']);
  Route::post('/subscribe',  [createController::class, 'subscribe']);

  Route::post('/pdfs',  [createController::class, 'pdfs']);
  Route::post('/usermediaget',  [PageseditController::class, 'mediashow']);
  Route::post('/userpagesections',  [PageseditController::class, 'pagesections']);
  Route::post('/userprojects',  [createController::class, 'projects']);
  Route::post('/projectid',  [createController::class, 'projectid']);
  Route::post('/blogid',  [createController::class, 'blogid']);
  Route::post('/pprojects',  [PageseditController::class, 'pagesections']);
  Route::post('/vmediaid',  [createController::class, 'vmediaid']);
  Route::post('/Ucontact',  [PageseditController::class, 'pagesections']);
  Route::post('/usersearch',  [createController::class, 'search']);
  Route::post('/personellsv',  [createController::class, 'personells']);

  Route::post('/generaluploads',  [createController::class, 'media']);
  Route::post('/webpagedid',  [PageseditController::class, 'webpagedid']);
  Route::post('/pagewebs',  [PageseditController::class, 'pagewebs']);
