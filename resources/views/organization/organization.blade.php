@extends('layouts.app')

@section('content')
    @include('layouts.partials.organization-nav')
    <div class="row" style="margin-top: 20px;">
        @if(ViewHelper::userHasOrganization($organization->slug))
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="user-profile-pic">
                    <img src="/images/no_organization_avatar.png" class="img-responsive img-rounded" alt="Image">
                </div>
                <p style="margin-top: 5px; margin-bottom: 0; font-size: 24px; font-weight: 600;">{{'@' . $organization->name }}</p>
                <p style="margin-top: 5px;"><i class="fa fa-clock-o"></i> Joined on {{ $organization->created_at->toFormattedDateString()  }}</p>
            </div>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <h3 style="margin: 0; font-size: 19px;">All Wikis</h3>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <form class="project-filter-form" id="project-filter-form" action="/lundskommun" accept-charset="UTF-8" method="get">
                            <div class="row">
                            	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 pull-right">
                                    <input type="search" name="filter_wikis" id="filter_wikis" placeholder="Filter by name" class="wikis-list-filter form-control">
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        @if($organization->wikis->count() > 0)
                            @foreach($organization->wikis as $wiki)
                                <div class="row">
                                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                        <h3 style="margin: 0 0 5px; font-size: 15px; font-weight: 600; color: #4078c0;"><a href="#">{{ $wiki->name }}</a></h3>
                                        <p style="font-size: 15px; color: #767676">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit hic inventore iste nulla quaerat, quis sapiente temporibus. Esse expedita illum ipsa necessitatibus nemo quas repudiandae tenetur vel! Eligendi modi, placeat.</p>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
                                        <div class="stats" style="padding-top: 8px;">
                                            <span style="color: #767676">
                                                <i class="fa fa-heart"></i> 2
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <h3 style="font-size: 17px; font-weight: 600; color: #777777; box-shadow: inset 0 0 10px rgba(0,0,0,0.05); background-color: #ffffff; text-align: center;">Nothing found</h3>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="user-profile-pic">
                    <img src="/images/default.png" class="img-responsive img-rounded" alt="Image">
                </div>
                <p style="margin-top: 5px; margin-bottom: 0; font-size: 24px; text-transform: capitalize;">{{ $organization->name }}</p>
                <p style="margin-top: 5px;"><i class="fa fa-clock-o"></i> Joined on Oct 9, 2016</p>
            </div>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <div class="jumbotron" style="border: 1px solid #e5e5e5; border-radius: 3px; box-shadow: inset 0 0 10px rgba(0,0,0,0.05); background-color: #fafafa;">
                    <h3 class="text-center" style="margin: 5px;"><i class="fa fa-lock fa-lg"></i> You are not member of this organization.</h3>
                </div>
            </div>
        @endif
    </div>
@endsection
