<div>
    <select id="files" wire:model="file">
        <option value="">Select Source File</option>
        @foreach($files as $file)
            <option value="{{ $file }}">{{ $file }}</option>
        @endforeach
    </select>
</div>
