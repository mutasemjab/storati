@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Celebrity Stories') }}</h3>
                    <a href="{{ route('celebrity-stories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.Add New Story') }}
                    </a>
                </div>

                <div class="card-body">
                 
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.Celebrity') }}</th>
                                    <th>{{ __('messages.Media') }}</th>
                                    <th>{{ __('messages.Type') }}</th>
                                    <th>{{ __('messages.Caption') }}</th>
                                    <th>{{ __('messages.Views') }}</th>
                                    <th>{{ __('messages.Status') }}</th>
                                    <th>{{ __('messages.Created_At') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @forelse($celebrities as $celebrity)
                                    @foreach($celebrity->activeStories as $story)
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('assets/admin/uploads/' . $celebrity->photo) }}" 
                                                         alt="{{ $celebrity->name_ar }}"
                                                         class="img-thumbnail me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                    <strong>{{ $celebrity->name_ar }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                @if($story->type === 'photo')
                                                    <img src="{{ $story->media_url }}" 
                                                         alt="Story" 
                                                         class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;"
                                                         onclick="viewStory({{ $celebrity->id }}, {{ $story->id }})" style="cursor: pointer;">
                                                @else
                                                    <div class="bg-dark d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px; cursor: pointer;"
                                                         onclick="viewStory({{ $celebrity->id }}, {{ $story->id }})">
                                                        <i class="fas fa-play text-white"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($story->type === 'photo')
                                                    <span class="badge bg-success">{{ __('messages.Photo') }}</span>
                                                @else
                                                    <span class="badge bg-primary">{{ __('messages.Video') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($story->caption)
                                                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $story->caption }}">
                                                        {{ $story->caption }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">{{ __('messages.No Caption') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ number_format($story->views_count) }}</span>
                                            </td>
                                            <td>
                                                @if($story->isExpired())
                                                    <span class="badge bg-danger">{{ __('messages.Expired') }}</span>
                                                @elseif($story->is_active)
                                                    <span class="badge bg-success">{{ __('messages.Active') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('messages.Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $story->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('celebrity-stories.show', [$celebrity, $story]) }}" 
                                                       class="btn btn-sm btn-info" title="{{ __('messages.View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm {{ $story->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                            onclick="toggleStoryStatus({{ $story->id }})"
                                                            title="{{ $story->is_active ? __('messages.Deactivate') : __('messages.Activate') }}">
                                                        <i class="fas {{ $story->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                    </button>
                                                    
                                                    <form action="{{ route('celebrity-stories.destroy', $story) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('{{ __('messages.Are you sure?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('messages.Delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">{{ __('messages.No Stories Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function viewStory(celebrityId, storyId) {
    window.location.href = `/celebrities/${celebrityId}/stories/${storyId}`;
}

function toggleStoryStatus(storyId) {
    $.ajax({
        url: `/celebrity-stories/${storyId}/toggle-active`,
        type: 'PATCH',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            location.reload();
        },
        error: function(xhr) {
            alert('{{ __("messages.Error occurred") }}');
        }
    });
}
</script>
@endsection