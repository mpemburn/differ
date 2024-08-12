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
