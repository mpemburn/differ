@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="selects_area" class="row">
                <div id="select_source" class="col-3">
                    <h4>Sources:</h4>
                    <select id="sources">
                        <option value="">Select Source</option>
                        @foreach($sources as $source => $date)
                            <option value="{{ $source  }}">{{ $source . ' (' . $date . ')'}}</option>
                        @endforeach
                    </select>
                </div>
                <div id="select_screenshot" class="col-3">
                    @if($screenshots)
                        <h4>Screenshots:</h4>
                        <select id="screenshots">
                            <option value="">Select Screenshot to Test</option>
                            @foreach($screenshots as $image => $data)
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
                             src="https://cdnjs.cloudflare.com/ajax/libs/galleriffic/2.0.1/css/loader.gif" alt=""
                             width="24"
                             height="24">
                    @endif
                </div>
            </div>
            <div id="button_area" class="row">
                <div class="col-12">
                    @if($hasSource)
                        <button id="automate_button" class="btn btn-primary btn-sm">Automate</button>
                    @endif
                    @if($hasResults)
                        <button id="results_button" class="btn btn-primary btn-sm">Show Results</button>
                    @endif
                </div>
            </div>
            <div class="row">
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
                        <div id="height_diff"></div>
                    </div>
                </div>
            </div>
    @include('results')
@endsection
