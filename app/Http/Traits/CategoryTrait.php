<?php
namespace App\Http\Traits;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
Trait CategoryTrait{

 public function   getFillderRequest(CategoryRequest $request){
        return [
            'title'=>$request->title,
            'description'=> $request->description
        ];
}
}
