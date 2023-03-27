<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
Trait CategoryTrait{

 public function   getFillderRequest(Request $request){
        return [
            'title'=>$request->title,
            'description'=> $request->description
        ];
}
}
