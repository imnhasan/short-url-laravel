@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 text-md-end">{{ __('Api Key') }}</label>

                            <div class="col-md-6">
                                <strong>{{ $apiKey }}</strong>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <a href="{{ url('api/generate-key') }}" type="submit" class="btn btn-primary">
                                    {{ __('Generate') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
