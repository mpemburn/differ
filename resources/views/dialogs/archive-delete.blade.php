Delete the following from {{ $deletionSource }}?
<ul>
    @foreach($deletionsSelected as $selected)
        <li>
            {{ \App\Services\ArchiveService::directoryFromPath($selected) }}
        </li>
    @endforeach
</ul>
