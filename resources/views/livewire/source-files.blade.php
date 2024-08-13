<div>
    <div id="controls">
        <select class="edit-select" wire:model="sourceName" wire:change="editSource">
            <option value="0">Select a Source</option>
            @foreach($sources as $source)
                <option value="{{ $source }}">{{ $source }}</option>
            @endforeach
        </select>
        <input
            type="text"
            wire:model="sourceName"
            wire:keydown="testName"
            data-name="sourceName"
            laceholder="Source name"
        />
        <button id="save_button" class="btn btn-primary btn-sm" wire:click="save" {{ $canSave ? '' : 'disabled' }}>Save</button>
        <button class="btn btn-primary btn-sm float-right" wire:click="clear">Clear</button>
        @if($showNewButton)
            <button class="btn btn-primary btn-sm" wire:click="newSource" wire:click="focusTitle">New</button>
        @endif
        @if($showDeleteButton)
            <button class="btn btn-primary btn-sm float-right btn-danger"
                    type="button"
                    wire:click="confirmDelete"
            >Delete
            </button>
        @endif
        <div wire:loading>
            <img src="https://cdnjs.cloudflare.com/ajax/libs/galleriffic/2.0.1/css/loader.gif" alt="" width="24"
                 height="24">
        </div>
    </div>
    @if($hasMessage)
        <div id="message" class="text-danger">"{{ $sourceName }}" exists. Please choose a different name.</div>
    @endif
    <div id="editor">
        <textarea
            id="source_editor"
            wire:model="editor"
            wire:keydown="hasChanged"
            placeholder="Enter a list of URLs"
        >
        </textarea>
    </div>
    @include('dialogs.confirm-delete', ['type' => 'sources'])
</div>
<script>
    $(document).ready(function ($) {
        @this.on('openConfirmDeleteModal', event => {
            setTimeout(function () {
                $('#confirm_delete').modal('show');
            }, 100);
        });

        @this.on('closeConfirmDeleteModal', event => {
            $('#confirm_delete').modal('hide');
        });

        @this.on('focusTitle', () => {
            $('[data-name="sourceName"]').focus();
        });

        $('#source_editor').on('cut paste', function () {
            Livewire.dispatch('contents-changed');
        })
    });

</script>
