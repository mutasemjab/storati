@extends('layouts.admin')

@section('title', __('messages.Note_Vouchers'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Note_Vouchers') }}</h3>
                    @can('noteVoucher-add')
                        <a href="{{ route('note-vouchers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.Add_New') }}
                        </a>
                    @endcan
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('messages.Number') }}</th>
                                    <th>{{ __('messages.Type') }}</th>
                                    <th>{{ __('messages.Date') }}</th>
                                    <th>{{ __('messages.Warehouse') }}</th>
                                    <th>{{ __('messages.Order') }}</th>
                                    <th>{{ __('messages.Products_Count') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($noteVouchers as $voucher)
                                    <tr>
                                        <td>{{ $voucher->number }}</td>
                                        <td>
                                            <span class="badge {{ $voucher->type_class }}">
                                                {{ $voucher->type_text }}
                                            </span>
                                        </td>
                                        <td>{{ $voucher->date_note_voucher->format('Y-m-d') }}</td>
                                        <td>{{ $voucher->warehouse->name ?? __('messages.No_Warehouse') }}</td>
                                        <td>{{ $voucher->order->number ?? __('messages.No_Order') }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $voucher->voucherProducts->count() }} {{ __('messages.Products') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('note-vouchers.show', $voucher) }}" 
                                                   class="btn btn-sm btn-info" title="{{ __('messages.View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @can('noteVoucher-edit')
                                                    <a href="{{ route('note-vouchers.edit', $voucher) }}" 
                                                       class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('noteVoucher-delete')
                                                    <form action="{{ route('note-vouchers.destroy', $voucher) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('{{ __('messages.Are_You_Sure') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="{{ __('messages.Delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            {{ __('messages.No_Data_Found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $noteVouchers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection