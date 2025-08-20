@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Add New Story') }}</h3>
                    <a href="{{ route('celebrity-stories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back') }}
                    </a>
                </div>

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

                    <form action="{{ route('celebrity-stories.store') }}" method="POST" enctype="multipart/form-data" id="storyForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Celebrity Selection -->
                                <div class="form-group">
                                    <label for="celebrity_id">{{ __('messages.Celebrity') }}</label>
                                    <select name="celebrity_id" id="celebrity_id" class="form-control" required>
                                        <option value="">{{ __('messages.Select Celebrity') }}</option>
                                        @foreach($celebrities as $celebrity)
                                            <option value="{{ $celebrity->id }}" {{ old('celebrity_id') == $celebrity->id ? 'selected' : '' }}>
                                                {{ $celebrity->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Media Type Selection -->
                                <div class="form-group">
                                    <label>{{ __('messages.Story Type') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_photo" value="photo" {{ old('type', 'photo') == 'photo' ? 'checked' : '' }} onchange="updateMediaInput()">
                                        <label class="form-check-label" for="type_photo">
                                            <i class="fas fa-image"></i> {{ __('messages.Photo') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_video" value="video" {{ old('type') == 'video' ? 'checked' : '' }} onchange="updateMediaInput()">
                                        <label class="form-check-label" for="type_video">
                                            <i class="fas fa-video"></i> {{ __('messages.Video') }}
                                        </label>
                                    </div>
                                </div>

                                <!-- Caption -->
                                <div class="form-group">
                                    <label for="caption">{{ __('messages.Caption') }} <small class="text-muted">({{ __('messages.Optional') }})</small></label>
                                    <textarea name="caption" id="caption" class="form-control" rows="3" maxlength="500" placeholder="{{ __('messages.Write a caption for your story...') }}">{{ old('caption') }}</textarea>
                                    <small class="text-muted">
                                        <span id="captionCount">0</span>/500 {{ __('messages.characters') }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Media Upload -->
                                <div class="form-group">
                                    <label for="media">
                                        <span id="mediaLabel">{{ __('messages.Photo') }}</span>
                                        <small class="text-muted" id="mediaHint">({{ __('messages.Max 20MB, JPG, PNG, GIF') }})</small>
                                    </label>
                                    
                                    <div class="custom-file">
                                        <input type="file" name="media" id="media" class="custom-file-input" accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi" required onchange="handleFileSelect(this)">
                                        <label class="custom-file-label" for="media">{{ __('messages.Choose file') }}</label>
                                    </div>

                                    <!-- Preview Area -->
                                    <div id="filePreview" class="mt-3" style="display: none;">
                                        <div id="imagePreview" style="display: none;">
                                            <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                                        </div>
                                        <div id="videoPreview" style="display: none;">
                                            <video id="previewVideo" controls class="img-fluid" style="max-height: 200px;">
                                                <source src="" type="video/mp4">
                                            </video>
                                        </div>
                                        <p id="fileName" class="text-muted mt-2"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> {{ __('messages.Add Story') }}
                            </button>
                            <a href="{{ route('celebrity-stories.index') }}" class="btn btn-secondary">
                                {{ __('messages.Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function updateMediaInput() {
    const photoRadio = document.getElementById('type_photo');
    const videoRadio = document.getElementById('type_video');
    const mediaLabel = document.getElementById('mediaLabel');
    const mediaHint = document.getElementById('mediaHint');
    const mediaInput = document.getElementById('media');

    if (photoRadio.checked) {
        mediaLabel.textContent = '{{ __("messages.Photo") }}';
        mediaHint.textContent = '({{ __("messages.Max 20MB, JPG, PNG, GIF") }})';
        mediaInput.accept = '.jpg,.jpeg,.png,.gif';
    } else if (videoRadio.checked) {
        mediaLabel.textContent = '{{ __("messages.Video") }}';
        mediaHint.textContent = '({{ __("messages.Max 20MB, MP4, MOV, AVI") }})';
        mediaInput.accept = '.mp4,.mov,.avi';
    }
    
    resetPreview();
}

function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;

    const filePreview = document.getElementById('filePreview');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');
    const fileName = document.getElementById('fileName');
    const previewImg = document.getElementById('previewImg');
    const previewVideo = document.getElementById('previewVideo');
    const customLabel = document.querySelector('.custom-file-label');

    customLabel.textContent = file.name;
    filePreview.style.display = 'block';
    fileName.textContent = file.name;

    if (file.type.startsWith('image/')) {
        imagePreview.style.display = 'block';
        videoPreview.style.display = 'none';
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else if (file.type.startsWith('video/')) {
        videoPreview.style.display = 'block';
        imagePreview.style.display = 'none';
        
        const url = URL.createObjectURL(file);
        previewVideo.src = url;
    }
}

function resetPreview() {
    const filePreview = document.getElementById('filePreview');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');
    const customLabel = document.querySelector('.custom-file-label');
    
    filePreview.style.display = 'none';
    imagePreview.style.display = 'none';
    videoPreview.style.display = 'none';
    customLabel.textContent = '{{ __("messages.Choose file") }}';
    
    document.getElementById('media').value = '';
}

// Caption character counter
document.getElementById('caption').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('captionCount').textContent = count;
});

// Form validation
document.getElementById('storyForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("messages.Uploading...") }}';
});
</script>
@endsection