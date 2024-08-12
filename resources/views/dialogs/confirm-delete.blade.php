<div class="modal fade" id="confirm_delete" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmMoveLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php  $confirm =  'dialogs.' . $type . '-delete' ?>
                @include($confirm)
            </div>
            <div class="modal-footer">
                <button wire:click="delete" class="btn btn-primary btn-sm">Confirm</button>
                <button wire:click="cancelDeletion" class="btn btn-primary btn-sm">Close</button>
            </div>
        </div>
    </div>
</div>
