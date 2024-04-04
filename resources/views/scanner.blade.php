@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="container">
                <div id="controls" class="col-md-10">
                    <div class="card">
                        <div class="card-header">{{ __('Create Screenshot Collections') }}</div>
                        <div class="card-body no-margin">
                            <livewire:scanner/>
                        </div>
                    </div>
                </div>
                <div id="progress" class="col-md-10">
                    <div class="card">
                        <div class="card-header">{{ __('Progress') }}</div>
                        <div class="card-body no-margin">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
