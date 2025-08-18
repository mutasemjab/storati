@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Celebrities') }}</h3>
                    @can('celebrity-add')
                        <a href="{{ route('celebrities.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.Add_Celebrity') }}
                        </a>
                    @endcan
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.Photo') }}</th>
                                    <th>{{ __('messages.Name_English') }}</th>
                                    <th>{{ __('messages.Name_Arabic') }}</th>
                                    <th>{{ __('messages.Products_Count') }}</th>
                                    <th>{{ __('messages.Created_At') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($celebrities as $celebrity)
                                    <tr>
                                        <td>{{ $loop->iteration + ($celebrities->currentPage() - 1) * $celebrities->perPage() }}</td>
                                        <td>
                                            @if($celebrity->photo)
                                                <img src="{{ asset($celebrity->photo) }}" 
                                                     alt="{{ $celebrity->name_en }}" 
                                                     class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $celebrity->name_en }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $celebrity->name_ar }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $celebrity->products_count ?? 0 }} {{ __('messages.Products') }}
                                            </span>
                                        </td>
                                        <td>{{ $celebrity->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                              
                                                @can('celebrity-edit')
                                                    <a href="{{ route('celebrities.edit', $celebrity) }}" 
                                                       class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                            
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('messages.No_Celebrities_Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $celebrities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

