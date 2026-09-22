@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.dashboard.slides.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Banner Image (800x350px)</label>
                                    <input type="file" name="image" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Redirect URL (Optional)</label>
                                    <input type="text" name="url" class="form-control" placeholder="https://example.com/game">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn--primary btn-block">Add Slide</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>Existing Slides</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>URL</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($slides as $slide)
                                <tr>
                                    <td><img src="{{ asset('assets/images/slides/'.$slide->image) }}" style="width: 100px;"></td>
                                    <td>{{ $slide->url }}</td>
                                    <td>
                                        <form action="{{ route('admin.dashboard.slides.delete', $slide->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection