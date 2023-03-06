@extends('admin.layouts.admin_master')

@section('title', 'About Us')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">About Us</h4>
                <p class="card-text">Update</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.about.us.update', $about_us->id) }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="short_description<">Short Description</label>
                            <textarea class="form-control" name="short_description">{{ $about_us->short_description }}</textarea>
                            @error('short_description')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label" for="long_description">Long Description</label>
                            <textarea class="form-control" name="long_description">{{ $about_us->long_description }}</textarea>
                            @error('long_description')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label" for="our_vision">Our Vision</label>
                            <textarea class="form-control" name="our_vision">{{ $about_us->our_vision }}</textarea>
                            @error('our_vision')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label" for="our_mission">Our Mission</label>
                            <textarea class="form-control" name="our_mission">{{ $about_us->our_mission }}</textarea>
                            @error('our_mission')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

</script>
@endsection
