@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div id="success" class="alert bg-light-success alert-success alert-dismissible d-none" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">
                        <p id="message" class="text-success"></p>
                        <p id="original-url" class="text-success"></p>
                        <p id="short-url" class="text-success"></p>
                    </div>
                </div>

                <div id="error" class="alert bg-light-danger alert-danger' alert-dismissible d-none" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">
                        <span id="error-message" class="text-danger"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Short Url') }}</div>

                    <div class="card-body">
                        <p>In real life application user pass the api key in the ajax header.
                            Here i just give the api input field as a example.</p>
                        <form id="apiCallAjax">

                            <div class="row mb-3">
                                <label for="api_key" class="col-md-2 col-form-label text-md-end">{{ __('Api Key') }}</label>

                                <div class="col-md-10">
                                    <input id="api_key" type="text" class="form-control @error('original_url') is-invalid @enderror" name="api_key" value="" required autocomplete="new-password" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="original_url" class="col-md-2 col-form-label text-md-end">{{ __('URL') }}</label>

                                <div class="col-md-10">
                                    <input id="original_url" type="text" class="form-control" name="original_url" value="" required autocomplete="new-password" autofocus>
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


@endsection

@push('script')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#apiCallAjax').submit(function(e) {
                e.preventDefault();
                let apiKey = $("#api_key").val();
                let originalUrl = $("#original_url").val();
                let successElement = $("#success");
                let messageElement = $("#message");
                let originalUrlElement = $("#original-url");
                let shortUrlElement = $("#short-url");
                let errorElement = $("#error");
                let errorMessageElement = $("#error-message");
                console.log(apiKey, originalUrl);
                // Send an AJAX request
                let data = {'api_key' : apiKey, 'original_url': originalUrl}
                $.ajax({
                    type: 'get',
                    url: '/api/make-short-url',
                    headers: {'api-key': apiKey},
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        // Handle the response message
                        console.log(response[1].message)
                        successElement.removeClass("d-none")
                        successElement.addClass("d-block")
                        messageElement.html(response[1].message);
                        originalUrlElement.html(response[1].original_url);
                        shortUrlElement.html('{{ env('APP_URL')}}' + '/' + response[1].short_url);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if needed
                        // console.error(xhr.responseText);
                        errorElement.removeClass("d-block");
                        errorElement.addClass("d-block");
                        errorMessageElement.html(xhr.responseText.message)
                    }
                });
            });
        });
    </script>
@endpush
