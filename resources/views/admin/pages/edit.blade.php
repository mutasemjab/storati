@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.Pages') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('pages.index') }}"> {{ __('messages.Pages') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.Pages') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('pages.update', $data['id']) }}" method="POST" enctype='multipart/form-data'>
                <div class="row">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Title') }}</label>
                            <input name="title" id="title" class="form-control"
                                value="{{ old('title', $data['title']) }}">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                  <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Content') }}</label>
                            <textarea name="content" id="content" class="form-control" rows="12">{{ old('content', $data['content']) }}</textarea>
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>







                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">
                                {{ __('messages.Update') }}</button>
                            <a href="{{ route('pages.index') }}"
                                class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.tiny.cloud/1/ffwdbcjhyfw4al7yr7y1e8shivh4g9nuipefj3gwz8y9s8h8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 400,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | ' +
                 'alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist outdent indent | removeformat | help'
    });
</script>
@endsection
