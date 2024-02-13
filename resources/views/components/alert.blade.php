<div class="alert {{ session('success') ? 'bg-light-success alert-success' : 'bg-light-danger alert-danger'}} alert-dismissible" role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <div class="alert-message">
        <span class="{{ session('success') ? 'text-success' : 'text-danger'}}">{{ session('success') ?? session('error') }}</span>
    </div>
</div>
