@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('messages.edit_banner_celebrity') }}</div>

                <div class="card-body">
                    <form action="{{ route('banner-celebrities.update', $bannerCelebrity) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="photo" class="form-label">{{ __('messages.photo') }}</label>
                            
                            @if($bannerCelebrity->photo)
                                <div class="mb-2">
                                    <img src="{{ asset('assets/admin/uploads/' . $bannerCelebrity->photo) }}" 
                                         alt="Current Banner" 
                                         style="width: 200px; height: 120px; object-fit: cover;">
                                </div>
                            @endif
                            
                            <input type="file" 
                                   class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*">
                            <small class="text-muted">{{ __('messages.leave_empty_keep_current') }}</small>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="celebrity_id" class="form-label">{{ __('messages.celebrity') }}</label>
                            <select class="form-control @error('celebrity_id') is-invalid @enderror" 
                                    id="celebrity_id" 
                                    name="celebrity_id" required>
                                @foreach($celebrities as $celebrity)
                                    <option value="{{ $celebrity->id }}" 
                                            {{ (old('celebrity_id', $bannerCelebrity->celebrity_id) == $celebrity->id) ? 'selected' : '' }}>
                                        {{ $celebrity->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('celebrity_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('banner-celebrities.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('messages.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection