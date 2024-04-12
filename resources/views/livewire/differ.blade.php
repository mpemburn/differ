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
</div>
