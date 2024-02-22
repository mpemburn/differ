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
                <div id="before-image" class="small-drop-zone">
                    Before Screenshot
                </div>
                <div id="after-image" class="small-drop-zone">
                    After Screenshot
                </div>
            </div>
            <div class="span6 col-6">
                <div id="image-diff" class="small-drop-zone">
                    Difference.
                </div>
                <br>
            </div>
        </div>
@endsection
