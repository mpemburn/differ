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
    <div class="modal fade" id="confirm_move" tabindex="-1" aria-labelledby="confirmMoveLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmMoveLabel">Confirm Move</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    "{{ $confirmMove }}" exists in destination
                </div>
                <div class="modal-footer">
                    <button wire:click="keep" class="btn btn-primary btn-sm">Keep Both</button>
                    <button wire:click="stop" class="btn btn-primary btn-sm">Stop</button>
                    <button wire:click="replace" class="btn btn-primary btn-sm">Replace</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('openConfirmMoveModal', event => {
        setTimeout(function () {
            $('#confirm_move').modal('show');
        },100);
    })
    window.addEventListener('closeConfirmMoveModal', event => {
         $('#confirm_move').modal('hide');
    })
</script>
