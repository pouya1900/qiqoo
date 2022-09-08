@extends('v1.site.layout.default')
@section('og-twitter-html-tags')
    @include('v1.site.includes.custom-og-twitter-html-meta')
    <title>اخبار | QiQoo سایت</title>
    <meta name="description"
          content="اخبار مربوط به نیازمندی ها و آگهی های جدید برای آشنایی با کسب و کارهای مختلف و پیدا کردن مناسب ترین کسب و کار مطابق با نیازمندی شما">

    <meta property="og:title" content="اخبار | QiQoo سایت"/>
    <meta property="og:url" content="{{route('blog.index')}}"/>
    <meta property="og:image" content="{{asset('assets/dashboard/img/logo.png')}}"/>
    <meta property="og:description"
          content="اخبار مربوط به نیازمندی ها و آگهی های جدید برای آشنایی با کسب و کارهای مختلف و پیدا کردن مناسب ترین کسب و کار مطابق با نیازمندی شما"/>

    <meta property=twitter:title content="اخبار | QiQoo سایت">
    <meta property=twitter:description
          content="اخبار مربوط به نیازمندی ها و آگهی های جدید برای آشنایی با کسب و کارهای مختلف و پیدا کردن مناسب ترین کسب و کار مطابق با نیازمندی شما">
    <meta property=twitter:image content="{{asset('assets/dashboard/img/logo.png')}}">
    <meta property=twitter:site content="{{route('blog.index')}}">

    <link rel="canonical" href="{{route('blog.index')}}">
@endsection
@section('content')

    <section class="header-breadcrumb bgimage overlay overlay--dark">
        @include('v1.site.includes.top-menu')
        <div class="bg_image_holder"><img src="{{ asset('assets/index/img/breadcrumb1.jpg')}}" alt=""></div>
        <div class="breadcrumb-wrapper content_above">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h1 class="page-title">اخبار</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">خانه</a></li>
                                <li class="breadcrumb-item active" aria-current="page">اخبار</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div><!-- ends: .breadcrumb-wrapper -->

    </section>

    <section class="blog-area section-padding-strict border-bottom">
        <div class="container">

            @include('v1.site.includes.blog-left-side')


        @foreach($blogs as $key=>$row)
                <div class="{{$key%2==0 ? "left_pic_blog" : ""}}">
                    <div class="row align_item_center">
                        <div class="col-6">
                            <div class="blog_logo_container">
                                <a href="{{$row->getShareLink()}}">
                                    <img src="{{$row->logo["standard"]}}">
                                </a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="blog_short_title_container">
                                <a href="{{$row->getShareLink()}}">
                                    <h3>{{$row->title}}</h3>
                                </a>
                                <p>
                                    {{$row->short_description}}
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            @endforeach


        </div>
    </section><!-- ends: .blog-area -->

@endsection
