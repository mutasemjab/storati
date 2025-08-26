@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Edit_Celebrity') }}</h3>
                    <a href="{{ route('celebrities.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back') }}
                    </a>
                </div>

                <form action="{{ route('celebrities.update', $celebrity) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('messages.Celebrity_Information') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Name_English') }} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                                                           name="name_en" value="{{ old('name_en', $celebrity->name_en) }}" required>
                                                    @error('name_en')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('messages.Name_Arabic') }} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                                           name="name_ar" value="{{ old('name_ar', $celebrity->name_ar) }}" required>
                                                    @error('name_ar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- In the gender select field for edit form -->
                                            <select class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                                <option value="">{{ __('messages.Select_Gender') }}</option>
                                                <option value="man" {{ old('gender', $celebrity->gender) == 'man' ? 'selected' : '' }}>{{ __('messages.Man') }}</option>
                                                <option value="woman" {{ old('gender', $celebrity->gender) == 'woman' ? 'selected' : '' }}>{{ __('messages.Woman') }}</option>
                                                <option value="both" {{ old('gender', $celebrity->gender) == 'both' ? 'selected' : '' }}>{{ __('messages.Both') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('messages.Celebrity_Photo') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Current Photo -->
                                        @if($celebrity->photo)
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('messages.Current_Photo') }}:</label>
                                                <div class="text-center">
                                                    <img src="{{ asset('assets/admin/uploads/'.$celebrity->photo) }}" 
                                                         alt="{{ $celebrity->name_en }}" 
                                                         class="img-fluid rounded border" 
                                                         style="width: 100%; height: 200px; object-fit: cover;">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">
                                                {{ $celebrity->photo ? __('messages.Change_Photo') : __('messages.Photo') }}
                                                @if(!$celebrity->photo)<span class="text-danger">*</span>@endif
                                            </label>
                                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                                   name="photo" accept="image/*" {{ !$celebrity->photo ? 'required' : '' }}>
                                            <small class="form-text text-muted">
                                                {{ __('messages.Allowed_Formats') }}: JPG, JPEG, PNG, GIF
                                            </small>
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- New Image Preview -->
                                        <div id="imagePreview" class="mt-3" style="display: none;">
                                            <label class="form-label">{{ __('messages.New_Preview') }}:</label>
                                            <img id="previewImg" src="" alt="Preview" 
                                                 class="img-fluid rounded border" 
                                                 style="width: 100%; height: 200px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.Update_Celebrity') }}
                        </button>
                        <a href="{{ route('celebrities.index') }}" class="btn btn-secondary">
                            {{ __('messages.Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.querySelector('input[name="photo"]');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });
});
</script>
@endpush