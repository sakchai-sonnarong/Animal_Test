<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Animal;

class AnimalController extends Controller
{
    function index() {
        $animals = Animal::all();
        return view('show', compact('animals'));
    }
    // function show(){->paginate(5)
    //     return view('show');
    // }
    function create(){
        return view('insertanimal');
    }

    function insert(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|max:50',
                'desc' => 'required',
                'quantity' => 'required'
            ],
            [
                'name.required' => 'กรุณาระบุชื่อสัตว์ที่ต้องการเพิ่ม',
                'name.max' => 'ชื่อสัตว์มีควาามยาวเกิน 50 ตัวอักษร',
                'desc.required' => 'กรุณากรอกรายละเียดของสัตว์',
                'quantity.required' => 'กรอกจำนวนของสัตว์ที่มี'
            ]
        );
        $data = [
            'name' => $request->name,
            'desc' => $request->desc,
            'quantity' =>$request->quantity
        ];
        // dd($data);
        Animal::create($data);
        return redirect()->route('show')->with('success', 'Data inserted successfully!');
        //return redirect('/animal');
    }

    function delete($id) {
        Animal::find($id)->delete();
        return redirect('/show');
    }

    function edit($id) {
        $animals=Animal::find($id);
        return view('edit', compact('animals'));
    }

    function update(Request $request,$id) {
        $request->validate(
            [
                'name' => 'required|max:50',
                'desc' => 'required',
                'quantity' => 'required'
            ],
            [
                'name.required' => 'กรุณาระบุชื่อสัตว์ที่ต้องการเพิ่ม',
                'name.max' => 'ชื่อสัตว์มีควาามยาวเกิน 50 ตัวอักษร',
                'desc.required' => 'กรุณากรอกรายละเียดของสัตว์',
                'quantity.required' => 'กรอกจำนวนของสัตว์ที่มี'
            ]
        );
        $data = [
            'name' => $request->name,
            'desc' => $request->desc,
            'quantity' =>$request->quantity
        ];
        // dd($data);
        Animal::find($id)->update($data);
        return redirect('/show');
    }
}
