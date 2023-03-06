@extends('admin.layouts.admin_master')

@section('title', 'News Details')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="text">
                    <h4 class="card-title">News</h4>
                    <p class="card-text">Details</p>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-secondary">
                        <thead>
                            <tr>
                                <th>Headline: </th>
                                <th>{{ $news->news_headline }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>News Location</td>
                                <td>
                                    <span class="badge bg-info mr-2">
                                        @isset($news->relationtocountry->country_name)
                                        {{ $news->relationtocountry->country_name }}
                                        @endisset
                                    </span>
                                    <span class="badge bg-info mr-2">
                                        @isset($news->relationtodivision->name)
                                        {{ $news->relationtodivision->name }}
                                        @endisset
                                    </span>
                                    <span class="badge bg-info mr-2">
                                        @isset($news->relationtodistrict->name)
                                        {{ $news->relationtodistrict->name }}
                                        @endisset
                                    </span>
                                    <span class="badge bg-info mr-2">
                                        @isset($news->relationtoupazila->name)
                                        {{ $news->relationtoupazila->name }}
                                        @endisset
                                    </span>
                                    <span class="badge bg-info mr-2">
                                        @isset($news->relationtounion->name)
                                        {{ $news->relationtounion->name }}
                                        @endisset
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Breaking News: </td>
                                <td>
                                    @if ($news->breaking_news == "Yes")
                                    <span class="badge bg-success">{{ $news->breaking_news }}</span>
                                    @else
                                    <span class="badge bg-warning">{{ $news->breaking_news }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>News Position: </td>
                                <td>{{ $news->news_position }}</td>
                            </tr>
                            <tr>
                                <td>Category: </td>
                                <td>{{ $news->relationtocategory->category_name }}</td>
                            </tr>
                            <tr>
                                <td>Tags: </td>
                                <td>
                                    @foreach ($news->tags as $tag)
                                        <span class="badge bg-info">{{ $tag->tag_name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>News Quote: </td>
                                <td>{{ $news->news_quote }}</td>
                            </tr>
                            <tr>
                                <td>News Thumbnail Photo: </td>
                                <td><img width="100" height="100" src="{{ asset('uploads/news_thumbnail_photo') }}/{{ $news->news_thumbnail_photo }}" alt=""></td>
                            </tr>
                            <tr>
                                <td>News Cover Photo: </td>
                                <td><img width="100" height="100" src="{{ asset('uploads/news_cover_photo') }}/{{ $news->news_cover_photo }}" alt=""></td>
                            </tr>
                            <tr>
                                <td>News Details: </td>
                                <td>{!! $news->news_details !!}</td>
                            </tr>
                            <tr>
                                <td>News Video: </td>
                                <td>{!! $news->news_video_link !!}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="100">
                                    <a class="btn btn-info" href="{{ route('admin.news.index') }}">Back</a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
    });
</script>
@endsection
