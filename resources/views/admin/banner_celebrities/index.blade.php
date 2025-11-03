@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('messages.banner_celebrities') }}</h2>
        <a href="{{ route('banner-celebrities.create') }}" class="btn btn-primary">
            {{ __('messages.add_new') }}
        </a>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.id') }}</th>
                        <th>{{ __('messages.photo') }}</th>
                        <th>{{ __('messages.celebrity') }}</th>
                        <th>{{ __('messages.created_at') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bannerCelebrities as $banner)
                        <tr>
                            <td>{{ $banner->id }}</td>
                            <td>
                                <img src="{{ asset('assets/admin/uploads/' . $banner->photo) }}" 
                                     alt="Banner" 
                                     style="width: 100px; height: 60px; object-fit: cover;">
                            </td>
                            <td>{{ $banner->celebrity->name_ar ?? __('messages.none') }}</td>
                            <td>{{ $banner->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('banner-celebrities.edit', $banner) }}" 
                                   class="btn btn-sm btn-warning">
                                    {{ __('messages.edit') }}
                                </a>
                                <form action="{{ route('banner-celebrities.destroy', $banner) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('messages.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $bannerCelebrities->links() }}
            </div>
        </div>
    </div>
</div>
@endsection