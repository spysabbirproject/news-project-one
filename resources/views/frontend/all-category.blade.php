@extends('frontend.layouts.frontend_master')

@section('title', 'All Category')

@section('content')
<!-- Breaking News Start -->
<div class="container-fluid mt-5 mb-3 pt-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <div class="section-title border-right-0 mb-0" style="width: 180px;">
                        <h4 class="m-0 text-uppercase font-weight-bold">Breaking</h4>
                    </div>
                    <div class="owl-carousel tranding-carousel position-relative d-inline-flex align-items-center bg-white border border-left-0"
                        style="width: calc(100% - 180px); padding-right: 100px;">
                        @forelse ($all_news->where('breaking_news', 'Yes') as $news)
                        <div class="text-truncate"><a class="text-secondary text-uppercase font-weight-semi-bold" href="{{ route('news.details', $news->news_slug) }}">{{ $news->news_headline }}</a></div>
                        @empty
                        <div class="text-truncate"><a class="text-danger text-uppercase font-weight-semi-bold" href="#">Not Found</a></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breaking News End -->

<!-- News With Sidebar Start -->
<div class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h5 class="my-3 text-dark text-uppercase font-weight-bold">All Categories</h5>
                <div class="row mt-3">
                    @forelse ($categories as $category)
                    <div class="col-lg-3 mb-3">
                        <a href="{{ route('category.wise.news', $category->category_slug) }}" class="btn btn-sm btn-secondary m-1">
                            <img width="100%" height="100&" src="{{ asset('uploads/category_photo') }}/{{ $category->category_photo }}" alt="">
                            <span class="mt-2">{{ $category->category_name }}</span>
                        </a>
                    </div>
                    @empty
                    <span class="text-danger">Not Found</span>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Social Follow Start -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">Follow Us</h4>
                    </div>
                    <div class="bg-white border border-top-0 p-3">
                        <a target="_blank" href="{{ $default_setting->facebook_link }}" class="d-block w-100 text-white text-decoration-none mb-3" style="background: #39569E;">
                            <i class="fab fa-facebook-f text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Fans</span>
                        </a>
                        <a target="_blank" href="{{ $default_setting->twitter_link }}" class="d-block w-100 text-white text-decoration-none mb-3" style="background: #52AAF4;">
                            <i class="fab fa-twitter text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Followers</span>
                        </a>
                        <a target="_blank" href="{{ $default_setting->instagram_link }}" class="d-block w-100 text-white text-decoration-none mb-3" style="background: #C8359D;">
                            <i class="fab fa-instagram text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Followers</span>
                        </a>
                        <a target="_blank" href="{{ $default_setting->linkedin_link }}" class="d-block w-100 text-white text-decoration-none mb-3" style="background: #0185AE;">
                            <i class="fab fa-linkedin-in text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Connects</span>
                        </a>
                        <a target="_blank" href="{{ $default_setting->youtube_link }}" class="d-block w-100 text-white text-decoration-none mb-3" style="background: #DC472E;">
                            <i class="fab fa-youtube text-center py-4 mr-3" style="width: 65px; background: rgba(0, 0, 0, .2);"></i>
                            <span class="font-weight-medium">12,345 Subscribers</span>
                        </a>
                    </div>
                </div>
                <!-- Social Follow End -->

                <!-- Ads Start -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">Advertisement</h4>
                    </div>
                    @forelse ($advertisements->where('advertisement_position', 'Center Right')->take(1) as $advertisement)
                    <div class="bg-white text-center border border-top-0 p-3">
                        <a target="_blank" href="{{ $advertisement->advertisement_link }}"><img class="img-fluid" src="{{ asset('uploads/advertisement_photo') }}/{{ $advertisement->advertisement_photo }}" alt="{{ $advertisement->advertisement_title }}"></a>
                    </div>
                    @empty
                    <span class="text-danger">Not Found</span>
                    @endforelse
                </div>
                <!-- Ads End -->

                <!-- Popular News Start -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">Tranding News</h4>
                    </div>
                    <div class="bg-white border border-top-0 p-3">
                        @forelse ($tranding_news->take(5) as $news)
                        <div class="d-flex align-items-center bg-white mb-3" style="height: 110px;">
                            <img width="100" height="100" src="{{ asset('uploads/news_thumbnail_photo') }}/{{ $news->news_thumbnail_photo }}" alt="">
                            <div class="w-100 h-100 px-3 d-flex flex-column justify-content-center border border-left-0">
                                <div class="mb-2">
                                    <a class="badge badge-primary text-uppercase font-weight-semi-bold p-1 mr-2" href="{{ route('category.wise.news', $news->relationtocategory->category_slug) }}">{{ $news->relationtocategory->category_name }}</a>
                                    <a class="text-body" href="#"><small>{{ $news->created_at->format('d-M, Y') }}</small></a>
                                </div>
                                <a class="h6 m-0 text-secondary text-uppercase font-weight-bold" href="{{ route('news.details', $news->news_slug) }}">{{ Str::limit($news->news_headline, 25) }}</a>
                            </div>
                        </div>
                        @empty
                        <div class="alert alert-warning" role="alert">
                          <span>Not Found</span>
                        </div>
                        @endforelse
                    </div>
                </div>
                <!-- Popular News End -->

                <!-- Archive Start -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">Archive</h4>
                    </div>
                    <div class="bg-white text-center border border-top-0 p-3">
                        <p>If you need any old news please select this date then find.</p>
                        <form action="{{ route('archive.news.result') }}" method="GET">
                            <div class="input-group mb-2" style="width: 100%;">
                                <input type="date" name="archive_date" class="form-control form-control-lg">
                                <div class="input-group-append">
                                    <button class="btn btn-primary font-weight-bold px-3" type="submit">Find</button>
                                </div>
                            </div>
                            @error('archive_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </form>
                    </div>
                </div>
                <!-- Archive End -->

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
<!-- News With Sidebar End -->
@endsection

@section('script')
<script>

</script>
@endsection

