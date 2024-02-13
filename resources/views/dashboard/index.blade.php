@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session('success') || session('error'))
                    @include('components.alert')
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        @include('$components.error')
                    @endforeach
                @endif
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Your Urls') }}</div>

                    <div class="card-body" style="overflow-x: scroll">
                        @if(($shortUrls->count() > 0))
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Original Url</th>
                                <th scope="col">Short Url</th>
                                <th scope="col">Click</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shortUrls as $shortUrl)
                                <tr>
                                    <th scope="row">{{ $shortUrl->id }}</th>
                                    {{--<td>{{ '$shortUrl->original_url' }}</td>--}}
                                    <td>{{ \Crypt::decrypt($shortUrl->original_url) }}</td>
                                    <td><a target="_blank" href="{{ env('APP_URL').'/'.$shortUrl->short_url }}">{{ $shortUrl->short_url }}</a></td>
                                    <td>{{ $shortUrl->click_count }}</td>
                                    <td class="btn-align">
                                        <!-- Button trigger modal -->
                                        <a type="button" onclick="edit({{ $shortUrl }}, '{{ \Crypt::decrypt($shortUrl->original_url) }}')" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Edit
                                        </a>
                                        <!-- Button trigger modal -->
                                        <a type="button" onclick="destroy({{ $shortUrl }})" class="btn btn-danger">
                                            Delete
                                        </a>
                                        <form id="form_delete_{{ $shortUrl->id }}" action="{{ route('short_url.destroy', $shortUrl) }}"
                                              method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                            <p>Sorry you don't have any links.</p>
                        @endif
                    </div>
                    @if($shortUrls->hasPages())
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-secondary">Showing <span>{{ $shortUrls->firstItem() }}</span> to <span>{{ $shortUrls->lastItem() }}</span> of
                                <span>{{ $shortUrls->total() }}</span> entries</p>
                            <ul class="pagination m-0 ms-auto">
                                {{ $shortUrls->withQueryString()->links() }}
                            </ul>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('short_url.update', $user) }}">
                    <div class="modal-body">
                        @csrf
                        @method('put')
                        <input type="hidden" id="id" name="id">
                        <div class="row mb-3">
                            <label for="original_url" class="col-md-4 col-form-label text-md-end">{{ __('Original Url') }}</label>

                            <div class="col-md-8">
                                <input id="original_url" type="text" class="form-control" name="original_url" value="" required autocomplete="new-password" autofocus>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="short_url" class="col-md-4 col-form-label text-md-end">{{ __('Short Url') }}</label>

                            <div class="col-md-8">
                                <input id="short_url" type="text" class="form-control" name="short_url" value="" required autocomplete="new-password" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        function edit(shortUrlData, originalUrlData) {
            console.log(shortUrlData, originalUrlData);
            let id = document.getElementById('id');
            let originalUrl = document.getElementById('original_url');
            let shortUrl = document.getElementById('short_url');
            id.value = shortUrlData.id;
            originalUrl.value = originalUrlData
            shortUrl.value = shortUrlData.short_url;
        }
        function destroy(color) {
            swal({
                title: "Are you sure?",
                text: "You want to delete.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((result) => {
                if (result) {
                    document.querySelector("#form_delete_" + color.id).submit();
                }
            });
        }
    </script>
@endpush
