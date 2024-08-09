<div class="row justify-content-center">
    <div id="selects_area" class="row">
        <div class="col-2 col-sm-auto">
            <h4>Screenshots</h4>
            <select
                wire:model="screenshotsSelected"
                wire:change="screenshotsChanged"
                class="archive-select"
                multiple="multiple"
                size="{{ $numRows }}"
            >
                @foreach($screenshots as $key => $name)
                    <option value="{{ $key  }}">{{ $name }}</option>
                @endforeach
            </select>
            <button wire:click="clearScreenshots" class="btn btn-primary btn-sm">Clear</button>
        </div>
        <div class="col-2 text-center archive-controls">
            <div>
                <button wire:click="archive" class="btn btn-primary btn-sm archive">Archive ></button>
            </div>
            <div>
                <button wire:click="unarchive" class="btn btn-primary btn-sm archive">< Unarchive</button>
            </div>
            <div>
                <button wire:click="confirmDelete" class="btn btn-primary btn-sm delete">Delete</button>
            </div>
        </div>
        <div class="col-2 col-sm-auto">
            <h4>Archives</h4>
            <select
                wire:model="archivesSelected"
                wire:change="archivesChanged"
                class="archive-select"
                multiple="multiple"
                size="{{ $numRows }}"
            >
                @foreach($archives as $key => $name)
                    <option value="{{ $key  }}">{{ $name }}</option>
                @endforeach
            </select>
            <button wire:click="clearArchives" class="btn btn-primary btn-sm">Clear</button>
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
    <div class="modal fade" id="confirm_delete" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmMoveLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Delete the following from {{ $deletionSource }}?
                    <ul>
                        @foreach($deletionsSelected as $selected)
                            <li>
                                {{ \App\Services\ArchiveService::directoryFromPath($selected) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button wire:click="delete" class="btn btn-primary btn-sm">Confirm</button>
                    <button wire:click="cancelDeletion" class="btn btn-primary btn-sm">Close</button>
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
    window.addEventListener('openConfirmDeleteModal', event => {
        setTimeout(function () {
            $('#confirm_delete').modal('show');
        },100);
    })
    window.addEventListener('closeConfirmDeleteModal', event => {
         $('#confirm_delete').modal('hide');
    })
</script>
