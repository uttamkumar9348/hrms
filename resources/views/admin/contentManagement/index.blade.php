
@extends('layouts.master')

@section('title','Company Static Content')

@section('action','Lists')

@section('button')
    @can('create-content_management')
        <a href="{{ route('admin.static-page-contents.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Add Content
            </button>
        </a>
    @endcan
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.contentManagement.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>

                            @can('show-content_management')
                                <th class="text-center">Content</th>
                            @endcan

                            <th class="text-center">Status</th>

                            @canany(['edit-content_management','delete-content_management'])
                                <th class="text-center">Action</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($staticPageContents as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{removeSpecialChars($value->title)}}</td>
                                <td>{{removeSpecialChars($value->content_type)}}</td>

                                @can('show-content_management')
                                    <td class="text-center">
                                        <a href=""
                                           id="showStaticPageDescription"
                                           data-href="{{route('admin.static-page-contents.show',$value->id)}}"
                                           data-id="{{ $value->id }}" title="show page content">
                                            <i class="link-icon" data-feather="eye"></i>

                                        </a>
                                    </td>
                                @endcan

                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.static-page-contents.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                @canany(['edit-content_management','delete-content_management'])
                                <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit-content_management')
                                            <li class="me-2">
                                                <a href="{{route('admin.static-page-contents.edit',$value->id)}}" title="Edit static page content ">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete-content_management')
                                            <li>
                                                <a class="deleteStaticPageContent"
                                                   data-href="{{route('admin.static-page-contents.delete',$value->id)}}" title="Delete static page content">
                                                    <i class="link-icon"  data-feather="delete"></i>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </td>
                                @endcan


                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="text-center"><b>No records found!</b></p>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @include('admin.contentManagement.show')
@endsection

@section('scripts')

   @include('admin.contentManagement.common.scripts')
@endsection






