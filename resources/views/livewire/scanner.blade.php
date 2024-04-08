<div>
    <select id="files" wire:model="file">
        <option value="">Select Source File</option>
        @foreach($files as $file)
            <option value="{{ $file }}">{{ $file }}</option>
        @endforeach
    </select>
    <button class="term-btn float-right" wire:click="scan">Scan</button>

    <div wire:poll="refreshResults">
        {!! $results !!}
    </div>
</div>
