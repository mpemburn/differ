@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="container">
                <div id="controls" class="col-md-10">
                    <div class="card">
                        <div class="card-header">{{ __('Generate Screenshot Command') }}</div>
                        <div class="card-body no-margin">
                            <livewire:commands/>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
