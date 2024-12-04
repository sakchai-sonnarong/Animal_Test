@extends('layout')
@section('title','ชื่อสัตว์ทั้งหมด')
@section('content')
@if (count($animals) > 0)
<h2 class="text text-center py-2">สัตว์ทั้งหมด</h2>
<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col">ชื่อสัตว์</th>
            <th scope="col">รายละเอียด</th>
            <th scope="col">แก้ไขข้อมูล</th>
            <th scope="col">ลบข้อมูล</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($animals as $item)
        <tr>
            <td>{{$item->name}}</td>
            <td>{{$item->desc}}</td>
            <td><a href="{{route('edit',$item->id)}}" class="btn btn-warning">แก้ไข</a></td>
            <td>
                <a
                    href="{{route('delete',$item->id)}}"
                    class="btn btn-danger"
                    onclick="return confirm('คุณต้องการลบ {{$item->name}} หรือไม่ ?')">ลบ
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection