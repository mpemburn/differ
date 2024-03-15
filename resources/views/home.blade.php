@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="row">
                <div id="select_screenshot">
                    <h4>Screenshots:</h4>
                    <select id="screenshots">
                        <option value="">Select Screenshot to Test</option>
                        @foreach(App\Facades\Image::getScreenshots($dir) as $image => $data)
                            <?php $name = App\Facades\Image::getName($image) ?>
                            @if ($data['when'] === 'before')
                                <option value="{{ $data['index'] }}"
                                        data-when="{{ $data['when'] }}"
                                        data-name="{{ $name }}"
                                        data-url="{{  asset('storage/' . $image) }}">
                            @else
                                <option class="hidden"
                                        data-when="{{ $data['when'] }}"
                                        data-name="{{ $name }}"
                                        data-url="{{  asset('storage/' . $image) }}">
                                    @endif
                                    {{ $name }}
                                </option>
                                @endforeach
                    </select>
                    <img id="loading"
                         src="https://cdnjs.cloudflare.com/ajax/libs/galleriffic/2.0.1/css/loader.gif" alt="" width="24"
                         height="24">
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
