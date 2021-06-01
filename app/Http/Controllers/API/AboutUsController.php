<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Models\AboutUs;
use Validator;
use App\Http\Resources\AboutUs as AboutUsResource;
use App\Http\Controllers\API\BaseController as BaseController;


class AboutUsController extends BaseController
{
    //
    public function index()
    {
        $us = AboutUs::all();
        return $this->sendResponse(AboutUsResource::collection($us),
          'informetain  sent');
    }

    public function store(Request $request)
    {
       $input = $request->all();
       $validator = Validator::make($input , [
       	'name'=> 'required',
         'phone'=> 'required',
          'whatsapp'=> 'required',
            ]  );

       if ($validator->fails()) {
        return $this->sendError('Please validate error' ,$validator->errors() );
          }
    $us = AboutUs::create($input);
    return $this->sendResponse(new AboutUsResource($us) ,'informetain created successfully' );

    }


    public function show($id)
    {
        $us = AboutUs::find($id);
        if ( is_null($us) ) {
            return $this->sendError('informetain not found'  );
              }
              return $this->sendResponse(new AboutUsResource($us) ,'informetain found successfully' );

    }

    public function update(Request $request, AboutUs $us)
    {
        $input = $request->all();
        $validator = Validator::make($input , [
         'name'=> 'required',
         'facebook'=> 'required',
         'phone'=> 'required',
         'whatsapp'=> 'required',
        'telegram'=> 'required',
        ]  );

        if ($validator->fails()) {
         return $this->sendError('Please validate error' ,$validator->errors() );
           }
     $us->name = $input['name'];
     $us->facebook = $input['facebook'];
     $us->phone = $input['phone'];
     $us->whatsapp = $input['whatsapp'];
     $us->telegram = $input['telegram'];

     $us->save();
     return $this->sendResponse(new AboutUsResource($u) ,'informetain updated successfully' );

    }


    public function destroy($us)
    {
        $us->delete();
        return $this->sendResponse(new AboutUsResource($us) ,'informetain deleted successfully' );

    }
}
