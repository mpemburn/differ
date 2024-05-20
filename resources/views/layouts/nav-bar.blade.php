<li class="nav-item">
    <a class="nav-link @if(Route::currentRouteName() == 'shell') active @endif" href="{{ route('home') }}">Differ</a>
</li>
<li class="nav-item">
    <a class="nav-link @if(Route::currentRouteName() == 'command') active @endif" href="{{ route('command') }}">Commands</a>
</li>
