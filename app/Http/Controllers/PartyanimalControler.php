<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\AnimalType;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartyanimalControler extends Controller
{
    //
    public function getanimal()
    {
        $animals = Animal::all();
        return response()->json([
            'success' => true,
            'message' => 'Data inserted successfully!',
            'data' => $animals
        ]);

        //return $this->returnSuccess('เรียกดูข้อมูลสำเร็จ', $animals);
    }

    public function insert(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|max:50',
                'desc' => 'required',
                'quantity' => 'required',
                'animal_type_id' => 'required'
            ],
            [
                'name.required' => 'กรุณาระบุชื่อสัตว์ที่ต้องการเพิ่ม',
                'name.max' => 'ชื่อสัตว์มีควาามยาวเกิน 50 ตัวอักษร',
                'desc.required' => 'กรุณากรอกรายละเอียดของสัตว์',
                'quantity.required' => 'กรอกจำนวนของสัตว์ที่มี',
                'animal_type_id' => 'กรุณาระบุประเภทของสัตว์'
            ]
        );
        $data = [
            'name' => $request->name,
            'desc' => $request->desc,
            'quantity' => $request->quantity,
            'animal_type_id' => $request->animal_type_id
        ];
        Animal::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Data inserted successfully!',
            'data' => $data
        ]);
    }
    //get data animal by id
    public function getanimalid($id)
    {
        $getanimal = Animal::find($id);

        if ($getanimal->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูล',
                'data' => []
            ], 404); // ส่ง HTTP 404 Not Found
        }

        return response()->json([
            'success' => true,
            'message' => 'Data inserted successfully!',
            'data' => $getanimal
        ]);
    }
    //delete data by id
    public function delete($id)
    {
        try {
            Animal::find($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Delete successfully!'
            ]);
        } catch (\Throwable $e) {

            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.'
            ], 400);
        }
    }

    public function getedit() {}

    //แก้ไขข้อมูลโดยใช้ Modale and filter data
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|max:50',
                'desc' => 'required',
                'quantity' => 'required',
                'animal_type_id' => 'required'
            ],
            [
                'name.required' => 'กรุณาระบุชื่อสัตว์ที่ต้องการเพิ่ม',
                'name.max' => 'ชื่อสัตว์มีควาามยาวเกิน 50 ตัวอักษร',
                'desc.required' => 'กรุณากรอกรายละเอียดของสัตว์',
                'quantity.required' => 'กรอกจำนวนของสัตว์ที่มี',
                'animal_type_id' => 'กรุณาระบุประเภทของสัตว์'
            ]
        );
        $data = [
            'name' => $request->name,
            'desc' => $request->desc,
            'quantity' => $request->quantity
        ];
        Animal::find($id)->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Data inserted successfully!',
            'data' => $data
        ]);
    }

    //insert data array นำเข้าข้อมูลที่เป็นรูปแบบ อาเรย์
    public function insertmore(Request $request)
    {
        $request->validate(
            [
                'arr.*.name' => 'required|max:50',
                'arr.*.desc' => 'required',
                'arr.*.quantity' => 'required',
                'arr.*.animal_type_id' => 'required'
            ],
            [
                'arr.*.name.required' => 'กรุณาระบุชื่อสัตว์ที่ต้องการเพิ่ม',
                'arr.*.name.max' => 'ชื่อสัตว์มีควาามยาวเกิน 50 ตัวอักษร',
                'arr.*.desc.required' => 'กรุณากรอกรายละเอียดของสัตว์',
                'arr.*.quantity.required' => 'กรอกจำนวนของสัตว์ที่มี',
                'arr.*.animal_type_id' => 'กรุณาระบุประเภทของสัตว์'
            ]
        );

        $data = $request->input('arr');
        foreach ($data as $animal) {
            if (is_array($animal)) {
                Animal::create([
                    'name' => $animal['name'],
                    'desc' => $animal['desc'],
                    'quantity' => $animal['quantity'],
                    'animal_type_id' => $animal['animal_type_id']
                ]);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Data inserted successfully!',
            'data' => $data
        ]);
    }

    public function updatemore(Request $request)
    {
        $request->validate(
            [
                'arr.*.id' => 'required|exists:animals,id',
                'arr.*.name' => 'required|max:50',
                'arr.*.desc' => 'required',
                'arr.*.quantity' => 'required',
                'arr.*.animal_type_id' => 'required'
            ],
            [
                'arr.*.id.required' => 'ID ของสัตว์จำเป็นต้องระบุ',
                'arr.*.id.exists' => 'ID ของสัตว์ไม่พบในฐานข้อมูล',
                'arr.*.name.required' => 'กรุณาระบุชื่อสัตว์ที่ต้องการเพิ่ม',
                'arr.*.name.max' => 'ชื่อสัตว์มีควาามยาวเกิน 50 ตัวอักษร',
                'arr.*.desc.required' => 'กรุณากรอกรายละเอียดของสัตว์',
                'arr.*.quantity.required' => 'กรอกจำนวนของสัตว์ที่มี',
                'arr.*.animal_type_id.required' => 'กรอกจำนวนของสัตว์ที่มี'
            ]
        );
        $data = $request->input('arr');
        foreach ($data as $animal) {

            $exitAnimal = Animal::find($animal['id']);

            if ($exitAnimal->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลที่ต้องการแก้ไข',
                    'data' => $exitAnimal
                ], 404); // ส่ง HTTP 404 Not Found
            }

            $exitAnimal->update([
                'name' => $animal['name'],
                'desc' => $animal['desc'],
                'quantity' => $animal['quantity'],
                'animal_type_id' => $animal['animal_type_id']
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Update successfully!',
            'data' => $data
        ]);
    }

    public function searchByName(Request $request)
    {
        $name = $request->input('name');

        if (!$name) {
            return response()->json([
                'message' => 'Name is Request'
            ]);
        }

        $result = Animal::where('name', 'like', '%' . $name . '%')->get();

        if ($result->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลที่ต้องการค้นหา',
                'data' => []
            ], 404); // ส่ง HTTP 404 Not Found
        }

        return response()->json([
            'message' => 'Search Success',
            'data' => $result
        ]);
    }

    public function inserttype(Request $request)
    {
        $request->validate(
            [
                'type_name' => 'required'
            ],
            [
                'type_name.required' => 'กรูณากรอกประเภทสัตว์'
            ]
        );
        $data = ['type_name' => $request->type_name];
        AnimalType::create($data);
        return response()->json([
            'success' => true,
            'message' => 'เพิ่มประเภทสำเร็จ',
            'data' => $data
        ]);
    }

    public function searchbytype(Request $request)
    {
        $type = $request->input('id');

        if (!$type) {
            return response()->json([
                'message' => 'Id is Request'
            ]);
        }

        $animalbytype = Animal::with('animalType') // โหลดความสัมพันธ์กับ animalType
            ->where('animal_type_id', $type)
            ->get();

        if ($animalbytype->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลประเภทที่ต้องการ',
                'data' => []
            ], 404); // ส่ง HTTP 404 Not Found
        }

        return response()->json([
            'success' => true,
            'message' => 'ค้นหาสำเร็จ',
            'data' => $animalbytype->map(function ($animal) {
                return [
                    'animal_id' => $animal->id,
                    'animal_name' => $animal->name,
                    'desc' => $animal->desc,
                    'type_name' => $animal->animalType->type_name, // ดึง type_name จาก animalType
                ];
            })
        ]);
    }

    public function exportPDF($id)
    {
        $Item = Animal::where('id', $id)->first();



        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if (!$Item) {
            abort(404, 'Animal not found.');
        }


        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 16,
            'fontDir' => array_merge($fontDirs, [
                base_path() . '/custom/font/directory',
            ]),
            'fontdata' => $fontData + [ // lowercase letters only in font key
                'th-sarabun' => [
                    'R' => 'THSarabun.ttf',
                    'I' => 'THSarabun Italic.ttf',
                    'B' => 'THSarabun Bold.ttf',
                    'BI' => 'THSarabun BoldItalic.ttf',
                ]
            ],
            'default_font' => 'th-sarabun',
            // 'default_font' => 'sarabunTH',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);
        $mpdf->SetTitle('Data Animal');
        $mpdf->AddPage();
        $html = '
        <h1>ข้อมูลสัตว์</h1>
        <h3>ข้อมูลสัตว์ พร้อมรายละเอียดของสัตว์</h3>
        <table style="width:100%;" margin-top:21px;>
            <tbody>
                <tr>
                    <td>ชื่อสัตว์</td>
                    <td>ข้อมูลสัตว์</td>
                    <td>จำนวน</td>
                    <td>ประเภท</td>
                </tr>
                <tr>
                    <td>' . $Item->name . '</td>
                    <td>' . $Item->desc . '</td>
                    <td>' . $Item->quantity . '</td>
                    <td>' . $Item->animalType->type_name . '</td>
                </tr>
            </tbody>
        </table>';

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function exportPDFAll()
    {
        $Items = Animal::all();

        $groupAnimals = $Items->groupBy(function ($typeanimal) {
            return $typeanimal->animalType->type_name;
        })->map(function ($group) {
            return [
                'total_quantity' => $group->sum('quantity'),  // คำนวณผลรวมของ quantity สำหรับแต่ละประเภท
                'animals' => $group
            ];
        });

        $animalCount = $Items->sum('quantity');

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 18,
            'fontDir' => array_merge($fontDirs, [
                base_path() . '/custom/font/directory',
            ]),
            'fontdata' => $fontData + [ // lowercase letters only in font key
                'th-sarabun' => [
                    'R' => 'THSarabun.ttf',
                    'I' => 'THSarabun Italic.ttf',
                    'B' => 'THSarabun Bold.ttf',
                    'BI' => 'THSarabun BoldItalic.ttf',
                ]
            ],
            'default_font' => 'th-sarabun',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $mpdf->SetTitle('Data Animal');
        $mpdf->AddPage();
        $html = '
        <h2 style="text-align: center;">สัตว์ทั้งหมด</h2>';
        foreach ($groupAnimals as $animaltype => $data) {
            
            $html .= '<h3>ประเภทสัตว์ : ' . $animaltype . ' </h3>';
            $html .= '<h3>จำนวนของสัตว์ : ' . $data['total_quantity'] . ' ตัว </h3>';
            $html .= '
            <table style="width:100%; border: 1px solid black; border-collapse: collapse;">
            <thead style="background-color: #4CAF50; color: white; font-size: 14px; font-weight: bold; text-align: center;">
                <tr>
                    <th style="border: 1px solid black; padding: 8px;">ชื่อสัตว์</th>
                    <th style="border: 1px solid black; padding: 8px;">ข้อมูลสัตว์</th>
                    <th style="border: 1px solid black; padding: 8px;">จำนวน</th>
                </tr>
            </thead>
            <tbody>';

            foreach ($data['animals'] as $animal) {
                $html .= '
                <tr style="text-align: center;">
                    <td style="border: 1px solid black; padding: 8px; text-align: center;">' . $animal->name . '</td>
                    <td style="border: 1px solid black; padding: 8px; text-align: center;">' . $animal->desc . '</td>
                    <td style="border: 1px solid black; padding: 8px; text-align: center;">' . $animal->quantity . '</td>
                </tr>';
            }
            $html .= '
            </tbody>
            </table>';
        }

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
