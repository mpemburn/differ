@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="row">
            <div >
                <h4>Screenshots:</h4>
                <select id="screenshots">
                    <option value="">Select Screenshot to Test</option>
                    @foreach(App\Facades\Image::getScreenshots('Clark') as $image => $when)
                            <?php $name = App\Facades\Image::getName($image) ?>
                            @if ($when === 'before')
                                <option {{ $when }}" data-when="{{ $when }}" data-name="{{ $name }}" data-url="{{  asset('storage/' . $image) }}">
                            @else
                                <option class="hidden" {{ $when }}" data-when="{{ $when }}" data-name="{{ $name }}" data-url="{{  asset('storage/' . $image) }}">
                            @endif
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="image_area" class="col-md-4">
                <div id="title_area">
                    <button id="clear_button" type="button" class="btn btn-sm">Clear</button>
                    <span id="comparing"></span>
                </div>
                <div id="before_image" class="image-zone">
                    Before Screenshot
                </div>
                <div id="after_image" class="image-zone">
                    After Screenshot
                </div>
            </div>
            <div id="diff_area" class="col-md-6">
                <div id="diff_image" class="image-zone">
                    Difference
                </div>
                <div id="diff_msg">
                    Click on the image above to open in a new tab.
                    <div id="percentage"></div>
                </div>
            </div>
        </div>
@endsection
