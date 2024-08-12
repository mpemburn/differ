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
    @include('dialogs.confirm-move')
    @include('dialogs.confirm-delete', ['type' => 'archive'])
</div>
<script>
    $(document).ready(function ($) {
        @this.on('openConfirmMoveModal', event => {
            setTimeout(function () {
                $('#confirm_move').modal('show');
            }, 100);
        });

        @this.on('closeConfirmMoveModal', event => {
            $('#confirm_move').modal('hide');
        });

        @this.on('openConfirmDeleteModal', event => {
            setTimeout(function () {
                $('#confirm_delete').modal('show');
            }, 100);
        });

        @this.on('closeConfirmDeleteModal', event => {
            $('#confirm_delete').modal('hide');
        });
    });

</script>
