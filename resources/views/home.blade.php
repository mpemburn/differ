@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <img src="https://screenie.test/storage/Clark/before/screenshots/All-campus-events.png" style="width: 30%;"/>
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
                <div class="btn-group buttons" style="display:none">
                    <button class="btn" id="raw">Ignore nothing</button>
                    <button class="btn active" id="less">Ignore less</button>
                    <button class="btn" id="colors">Ignore colors</button>
                    <button class="btn" id="antialiasing">Ignore antialiasing</button>
                    <button class="btn" id="alpha">Ignore alpha</button>
                </div>

                <br>
                <br>
                <div class="btn-group buttons" style="display:none">
                    <button class="btn active" id="original-size">Use original size</button>
                    <button class="btn" id="same-size">Scale to same size</button>
                </div>

                <div class="btn-group buttons" style="display:none">
                    <button class="btn active" id="pink">Pink</button>
                    <button class="btn" id="yellow">Yellow</button>
                </div>
                <br>
                <br>

                <div class="btn-group buttons" style="display:none">
                    <button class="btn active" id="flat">Flat</button>
                    <button class="btn" id="movement">Movement</button>
                    <button class="btn" id="flatDifferenceIntensity">Flat with diff intensity</button>
                    <button class="btn" id="movementDifferenceIntensity">Movement with diff intensity</button>
                    <button class="btn" id="diffOnly">Diff portion from the input</button>
                </div>
                <br>
                <br>

                <div class="btn-group buttons" style="display:none">
                    <button class="btn active" id="opaque">Opaque</button>
                    <button class="btn" id="transparent">Transparent</button>
                </div>
                <br>
                <br>

                <div class="btn-group buttons" style="display:none">
                    <div class="row">
                        <div class="span1">
                            <label>Left</label>
                            <input type="number" class="input-mini" id="bounding-box-x1" value="100">
                        </div>
                        <div class="span1">
                            <label>Top</label>
                            <input type="number" class="input-mini" id="bounding-box-y1" value="100">
                        </div>
                        <div class="span1">
                            <label>Right</label>
                            <input type="number" class="input-mini" id="bounding-box-x2" value="400">
                        </div>
                        <div class="span1">
                            <label>Bottom</label>
                            <input type="number" class="input-mini" id="bounding-box-y2" value="300">
                        </div>
                        <div class="span2">
                            <label>&nbsp;</label>
                            <button class="btn" id="boundingBox">Set bounding box</button>
                        </div>
                    </div>
                </div>

                <br>
                <br>

                <div class="btn-group buttons" style="display:none">
                    <div class="row">
                        <div class="span1">
                            <label>Left</label>
                            <input type="number" class="input-mini" id="ignored-box-x1" value="120">
                        </div>
                        <div class="span1">
                            <label>Top</label>
                            <input type="number" class="input-mini" id="ignored-box-y1" value="200">
                        </div>
                        <div class="span1">
                            <label>Right</label>
                            <input type="number" class="input-mini" id="ignored-box-x2" value="400">
                        </div>
                        <div class="span1">
                            <label>Bottom</label>
                            <input type="number" class="input-mini" id="ignored-box-y2" value="250">
                        </div>
                        <div class="span2">
                            <label>&nbsp;</label>
                            <button class="btn" id="ignoredBox">Set ignored box</button>
                        </div>
                    </div>
                </div>

                <br>
                <br>

                <div class="btn-group buttons" style="display:none">
                    <div class="row">
                        <div class="span1">
                            <label>Red</label>
                            <input type="number" class="input-mini" id="ignored-color-r" min="0" max="255" value="255">
                        </div>
                        <div class="span1">
                            <label>Green</label>
                            <input type="number" class="input-mini" id="ignored-color-g" min="0" max="255" value="0">
                        </div>
                        <div class="span1">
                            <label>Blue</label>
                            <input type="number" class="input-mini" id="ignored-color-b" min="0" max="255" value="0">
                        </div>
                        <div class="span1">
                            <label>Alpha</label>
                            <input type="number" class="input-mini" id="ignored-color-a" min="0" max="255" value="255">
                        </div>
                        <div class="span2">
                            <label>&nbsp;</label>
                            <button class="btn" id="ignoredColor">Set ignored color</button>
                        </div>
                    </div>
                </div>

                <br>
                <br>
                <div id="diff-results" style="display:none;">
                    <p>
                        <strong>The second image is <span id="mismatch"></span>% different compared to the first.
                            <span id="differentdimensions" style="display:none;">And they have different dimensions.</span></strong>
                    </p>
                    <p>
                        Use the buttons above to change the comparison algorithm.  Perhaps you don't care about color? Annoying antialiasing causing too much noise?  Resemble.js offers multiple comparison options.
                    </p>
                </div>

                <p id="thesame" style="display:none;">
                    <strong>These images are the same!</strong>
                </p>
            </div>
        </div>                </div>

    <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

            </div>
        </div>
    </div>
</div>
@endsection
