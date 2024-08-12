<li class="nav-item">
    <a class="nav-link @if(Route::currentRouteName() == 'shell') active @endif" href="{{ route('home') }}">Compare</a>
</li>
<li class="nav-item">
    <a class="nav-link @if(Route::currentRouteName() == 'command') active @endif" href="{{ route('command') }}">Commands</a>
</li>
<li class="nav-item">
    <a class="nav-link @if(Route::currentRouteName() == 'sources') active @endif" href="{{ route('sources') }}">Sources</a>
</li>
<li class="nav-item">
    <a class="nav-link @if(Route::currentRouteName() == 'archive') active @endif" href="{{ route('archive') }}">Archive</a>
</li>
