@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        @foreach(App\Facades\Image::getScreenshots('Clark') as $image => $when)
            <?php $name = App\Facades\Image::getName($image) ?>
            <div class="test-image {{ $when }}" data-when="{{ $when }}" data-name="{{ $name }}" data-url="{{  asset('storage/' . $image) }}">
                {{ $name }}
            </div>
        @endforeach

        <div class="row">
            <div class="span6 col-6">
                <div id="dropzone1" class="small-drop-zone">
                    Drop first image
                </div>
                <div id="dropzone2" class="small-drop-zone">
                    Drop second image
                </div>
            </div>
            <div class="span6 col-6">
                <div id="image-diff" class="small-drop-zone">
                    Diff will appear here.
                </div>
                <br>
            </div>
        </div>
@endsection
