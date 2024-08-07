<div class="row justify-content-center">
    <div id="selects_area" class="row">
        <div class="col-2">
            <select wire:model="screenshotsSelected" class="archive-select" multiple="multiple" size="{{ $numRows }}">
                @foreach($screenshots as $key => $name)
                    <option value="{{ $key  }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2 text-center">
            <div>
                <button wire:click="archive" class="btn btn-primary btn-sm archive">Archive ></button>
            </div>
            <div>
                <button wire:click="unarchive" class="btn btn-primary btn-sm archive">< Unarchive</button>
            </div>
        </div>
        <div class="col-2">
            <select wire:model="archivesSelected" class="archive-select" multiple="multiple" size="{{ $numRows }}">
                @foreach($archives as $key => $name)
                    <option value="{{ $key  }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div id="confirm_move">
        <div>
            "{{ $confirmMove }}" exists in destination
        </div>
        <button wire:click="keep" class="btn btn-primary btn-sm">Keep Both</button>
        <button wire:click="stop" class="btn btn-primary btn-sm">Stop</button>
        <button wire:click="replace" class="btn btn-primary btn-sm">Replace</button>
    </div>
</div>
<script>
    window.addEventListener('openConfirmMoveModal', event => {
        $("#confirm_move").modal('show');
    })
</script>
