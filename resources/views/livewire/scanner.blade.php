<div>
    <select id="files" wire:model="file" wire:change="setFile">
        <option value="">Select Source File</option>
        @foreach($files as $file)
            <option value="{{ $file }}">{{ $file }}</option>
        @endforeach
    </select>
    <input type="text" wire:model="name" placeholder="File name"/>
    <label><input type="radio" wire:model="when" value="before">Before</label>
    <label><input type="radio" wire:model="when" value="after">After</label>
    <button class="btn btn-primary btn-sm float-right" wire:click="generate">Generate Command</button>

    <div>
        <h5>
            <span class="btn_copy" wire:click="copy">⎘</span>
            Command: <span id="command">{{ $command }}</span>
        </h5>
        <span id="copied"></span>
    </div>
</div>
<script>
    $(document).ready(function ($) {
        @this.on('copyToClipboard', () => {
            let contents = $('#command').html();
            let message = $('#copied');
            navigator.clipboard.writeText(contents).then(() => {
                message.html('Copied to clipboard');
            }, () => {
                message.html('Failed to copy');
            });

        });
    });
</script>
