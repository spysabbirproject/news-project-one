@extends('frontend.layouts.frontend_master')

@section('title', 'All Video')

@section('content')
<!-- Contact Start -->
<div class="container-fluid mt-5 pt-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">All Video</h4>
                    </div>
                    <div class="bg-white border border-top-0 p-4 mb-3">
                        <div class="all_video_gallery">
                            @foreach ($video_galleries as $video_gallery)
                                {!! $video_gallery->gallery_video_link !!}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- All Category Start -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">All Category</h4>
                    </div>
                    <div class="bg-white border border-top-0 p-3">
                        @forelse ($categories as $category)
                        <a href="" class="btn btn-sm btn-secondary m-1">{{ $category->category_name }}</a>
                        @empty
                        <span class="text-danger">Not Found</span>
                        @endforelse
                    </div>
                </div>
                <!-- All Category End -->

                <!-- Newsletter Start -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">Newsletter</h4>
                    </div>
                    <div class="bg-white text-center border border-top-0 p-3">
                        <p>If you need important news in your email please subscribe for newsletter.</p>
                        <form action="{{ route('subscriber.store') }}" method="POST" id="subscriber_form">
                            @csrf
                            <div class="input-group mb-2" style="width: 100%;">
                                <input type="text" name="subscriber_email" class="form-control form-control-lg" placeholder="Enter Your Email">
                                <div class="input-group-append">
                                    <button id="subscriber_btn" class="btn btn-primary font-weight-bold px-3" type="submit">Subscribe</button>
                                </div>
                            </div>
                            <span class="text-danger error-text subscriber_email_error"></span>
                        </form>
                        <small>Please subscribe a valid email.</small>
                    </div>
                </div>
                <!-- Newsletter End -->
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->
@endsection

@section('script')

@endsection
