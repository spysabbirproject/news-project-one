@extends('frontend.layouts.frontend_master')

@section('title', 'Tag Wise News')

@section('content')
<!-- News With Sidebar Start -->
<div class="container-fluid mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <h4 class="m-0 text-uppercase font-weight-bold">Tag: {{ $tag->tag_name }}</h4>
                        </div>
                    </div>

                    @forelse ($advertisements->where('advertisement_position', 'Center Top')->take(1) as $advertisement)
                    <div class="col-lg-12 mb-3">
                        <a href="{{ $advertisement->advertisement_link }}"><img class="img-fluid w-100" src="{{ asset('uploads/advertisement_photo') }}/{{ $advertisement->advertisement_photo }}" alt="{{ $advertisement->advertisement_title }}"></a>
                    </div>
                    @empty
                    <div class="col-lg-12">
                        <div class="alert alert-danger">
                            <span>Not Found</span>
                        </div>
                    </div>
                    @endforelse

                    @forelse ($news_id as $all_news)
                        @php
                            $news = App\Models\News::find($all_news->news_id)
                        @endphp
                        <div class="col-lg-6">
                            <div class="position-relative mb-3">
                                <img class="img-fluid w-100" src="{{ asset('uploads/news_thumbnail_photo') }}/{{ $news->news_thumbnail_photo }}" style="object-fit: cover;">
                                <div class="bg-white border border-top-0 p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <img class="rounded-circle mr-2" src="{{ asset('uploads/profile_photo') }}/{{ $news->relationtouser->profile_photo }}" width="25" height="25" alt="">
                                        <small><a href="{{ route('reporter.wise.news', $news->relationtouser->id) }}">{{ $news->relationtouser->name }}</a></small>
                                    </div>
                                    <div class="mb-2">
                                        <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2"
                                            href="{{ route('category.wise.news', $news->relationtocategory->category_slug) }}">{{ $news->relationtocategory->category_name }}</a>
                                        <a class="text-body" href="#"><small>{{ $news->created_at->format('d-M, Y') }}</small></a>
                                    </div>
                                    <a class="h4 d-block mb-3 text-secondary text-uppercase font-weight-bold" href="{{ route('news.details', $news->news_slug) }}">{{ Str::limit($news->news_headline, 25)  }}</a>
                                    <p class="m-0">{!! Str::limit($news->news_details, 50) !!}</p>
                                </div>
                                <div class="d-flex justify-content-between bg-white border border-top-0 p-4">
                                    <div class="d-flex align-items-center">
                                        <small class="ml-3"><i class="far fa-eye mr-2"></i>{{ $news->news_view }}</small>
                                        <small class="ml-3"><i class="far fa-comment mr-2"></i>{{ App\Models\Comment::where('news_id', $news->id)->count() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    <div class="col-lg-12">
                        <div class="alert alert-danger">
                            <span>Not Found</span>
                        </div>
                    </div>
                    @endforelse

                    @forelse ($advertisements->where('advertisement_position', 'Center Bottom')->take(1) as $advertisement)
                    <div class="col-lg-12 mb-3">
                        <a href="{{ $advertisement->advertisement_link }}"><img class="img-fluid w-100" src="{{ asset('uploads/advertisement_photo') }}/{{ $advertisement->advertisement_photo }}" alt="{{ $advertisement->advertisement_title }}"></a>
                    </div>
                    @empty
                    <div class="col-lg-12">
                        <div class="alert alert-danger">
                            <span>Not Found</span>
                        </div>
                    </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center">
                    {{ $news_id->links() }}
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
                                    <br>
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

                <!-- Tags Start -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 text-uppercase font-weight-bold">Tags</h4>
                    </div>
                    <div class="bg-white border border-top-0 p-3">
                        <div class="d-flex flex-wrap m-n1">
                            @forelse ($tags as $tag)
                            <a href="{{ route('tag.wise.news', $tag->tag_slug) }}" class="btn btn-sm btn-outline-secondary m-1">{{ $tag->tag_name }}</a>
                            @empty
                            <span class="btn btn-sm btn-outline-warning m-1">Not Found</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- Tags End -->
            </div>
        </div>
    </div>
</div>
<!-- News With Sidebar End -->
@endsection
