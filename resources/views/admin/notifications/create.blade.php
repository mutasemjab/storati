@extends('layouts.admin')
@section('title')
    notifications
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> Add New notifications </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('notifications.send') }}" method="POST">
                                @csrf

                                <div class="form-group mt-0">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>

                                <div class="form-group">
                                    <label for="body">Body</label>
                                    <textarea name="body" id="body" class="form-control" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="type">Notification Type</label>
                                    <select name="type" id="type" class="form-control" onchange="toggleUserField()">
                                        <option value="0">All Users</option>
                                        <option value="1">Specific User</option>
                                    </select>
                                </div>

                                <div class="form-group" id="userField" style="display:none;">
                                    <label for="user_id">Select User</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                ({{ $user->email ?? $user->phone }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary">Send Notification</button>
                                </div>
                            </form>

                            <script>
                                function toggleUserField() {
                                    const type = document.getElementById('type').value;
                                    const userField = document.getElementById('userField');
                                    userField.style.display = type == '1' ? 'block' : 'none';
                                }
                            </script>

                        </div>
                    </div>
                </div>
            </div>




        </div>




    </div>
    </div>
@endsection
