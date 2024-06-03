<div>
    <select id="files" wire:model="file" wire:change="setFile">
        <option value="">Select Source File</option>
        @foreach($files as $file)
            <option value="{{ $file }}">{{ $file }}</option>
        @endforeach
    </select>
    <input type="text" wire:model="name" id="test_name" placeholder="File name"/>
    <label><input type="radio" wire:model="when" name="when" value="before">Before</label>
    <label><input type="radio" wire:model="when" name="when" value="after">After</label>
    <button class="btn btn-primary btn-sm float-right" wire:click="generate">Generate Command</button>
    @if($command)
        <button class="btn btn-primary btn-sm float-right" wire:click="run">Run Command</button>
    @endif

    <div>
        @if($command)
        <h5>
            <span class="btn_copy" wire:click="copy">⎘</span>
            Command: <span id="command">{{ $command }}</span>
        </h5>
        <span id="copied"></span>
        @endif
    </div>
    <br>
    <div id="command_results">
        <h4>Results: <span id="loading"></span></h4>
        <pre id="results"></pre>
    </div>



</div>
<script>
    $(document).ready(function ($) {
        @this.on('copyToClipboard', () => {
            let contents = $('#command').html();
            let message = $('#copied');
            navigator.clipboard.writeText(contents).then(() => {
                message.html('Copied to clipboard');
                setTimeout(function () {
                    message.fadeOut(1000);
                }, 2000);
            }, () => {
                message.html('Failed to copy');
            });
        })

        @this.on('runCommand', () => {
            let fileSelect = $('#files :selected');
            let filename = fileSelect.text();
            let when = $('input[name="when"]:checked').val();
            let testName = $('#test_name').val();
            let results = $('#results');

            if (fileSelect.val() === '') {
                return;
            }

            window.Commands.run(filename, testName, when, results);
        })
    });
</script>
