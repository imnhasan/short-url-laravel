@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Short Url') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('short_url.store') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="original_url" class="col-md-2 col-form-label text-md-end">{{ __('URL') }}</label>

                                <div class="col-md-10">
                                    <input id="original_url" type="text" class="form-control @error('original_url') is-invalid @enderror" name="original_url" value="" required autocomplete="new-password" autofocus>

                                    @error('original_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Create') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if(session('success'))
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Your shor Url') }}</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <strong class="col-md-2 text-md-end">{{ __('Original Url') }} ➡️</strong>
                            <div class="col-md-10">
                                <p>{{ \Crypt::decrypt(session('success')->original_url) }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <strong for="original_url" class="col-md-2 text-md-end">{{ __('Short Url') }} ➡️</strong>
                            <div class="col-md-10">
                                <p><a href="{{ env('APP_URL').'/'.session('success')->short_url }}">{{ env('APP_URL').'/'.session('success')->short_url }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection
