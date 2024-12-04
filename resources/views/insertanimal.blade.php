@extends('layout')
@section('title', 'สัตว์ทั้งหมด')
@section('content')
    <h2 class="text text-center py-2">เพิ่มข้อมูลสัตว์</h2>
    <form method="POST" action="/insert">
        @csrf
        <div class="form-group">
            <label for="name">ชื่อสัตว์</label>
            <input type="text" name="name" class="form-control">
        </div>
        @error('name')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
        <div class="form-group">
            <label for="desc">รายละเอียดสัตว์</label>
            <textarea name="desc" cols="30" rows="5" class="form-control" id="desc"></textarea>
        </div>
        @error('desc')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
        <div class="form-group">
            <label for="quantity">จำนวน</label>
            <input type="number" name="quantity" class="form-control">
        </div>
        @error('quantity')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
        <input type="submit" value="บันทึก" class="btn btn-primary my-3">
        
    </form>
    <script>
        ClassicEditor
            .create( document.querySelector('#content'))
            .catch( error => {
                console.error( error );
            });
    </script>    
@endsection
