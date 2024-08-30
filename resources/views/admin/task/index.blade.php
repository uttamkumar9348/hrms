@extends('layouts.master')
@section('title','Tasks')
@section('action','Lists')

@section('button')
    @can('create-task_management')
        <a href="{{ route('admin.tasks.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Create Tasks
            </button>
        </a>
    @endcan
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.task.common.breadcrumb')

        <div class="search-box p-4 pb-0 bg-white rounded mb-3 box-shadow">
            <form class="forms-sample" action="{{route('admin.tasks.index')}}" method="get">
                <h5 class="mb-3">Task Filter</h5>
                <div class="row align-items-center">

                    <div class="col-xxl col-xl-4 col-md-6 mb-4">
                        <select class="col-md-12 from-select" id="projectFilter" name="projects">
                            <option value="" {{!isset($filterParameters['projects']) ? 'selected':''}}></option>
                            @foreach($projects as $key => $value)
                                <option value="{{$value->id}}" {{ isset($filterParameters['projects']) && ($value->id == $filterParameters['projects'])  ? 'selected' : '' }}  >{{ucfirst($value->name)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mb-4">
                        <select class="form-select" id="taskName" name="project_name" >
                            <option value="" {{!isset($filterParameters['task_name']) ? 'selected':'' }}>Search by Task Name</option>
                            @foreach($allTasks as $key => $value)
                                <option value="{{$value->id}}" {{$value->id == $filterParameters['task_name'] ? 'selected':''}}>
                                    {{ ucfirst(\Illuminate\Support\Str::limit($value->name, 40, $end='...')) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mb-4">
                        <select class="form-select" id="status" name="status" >
                            <option value="">Search by Status</option>
                            @foreach(\App\Models\Task::STATUS as $value)
                                <option value="{{$value}}" {{$filterParameters['status'] == $value ? 'selected':''}}>
                                    {{(\App\Helpers\PMHelper::STATUS[$value])}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mb-4">
                        <select class="form-select" id="priority" name="priority" >
                            <option value="">Search By Priority</option>
                            @foreach(\App\Models\Task::PRIORITY as $value)
                                <option value="{{$value}}" {{$filterParameters['priority'] == $value ? 'selected':''}}> {{ucfirst($value)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mb-4">
                        <select class="col-md-12 from-select" id="filter" name="members[]" multiple="multiple">
                            @foreach($employees as $key => $value)
                                <option value="{{$value->id}}" {{ isset($filterParameters['members']) && in_array($value->id,$filterParameters['members'])  ? 'selected' : '' }}  >{{ucfirst($value->name)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-4 col-md-6 mb-4">
                        <div class="d-flex float-md-end">
                            <button type="submit" class="btn btn-block btn-secondary me-2">Filter</button>
                            <a class="btn btn-block btn-danger" href="{{route('admin.tasks.index')}}">Reset</a>
{{--                            <button type="button" class="btn btn-block btn-danger reset">Reset</button>--}}
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php
            $status = [
                'in_progress' => 'primary',
                'not_started' => 'primary',
                'on_hold' => 'info',
                'cancelled' => 'danger',
                'completed' => 'success',
            ]
        ?>
        <div class="project-card">
            <div class="row">
                @forelse($tasks as $key => $value)
                    <div class="col-lg-4 d-flex mb-4">
                        <div class="card p-4 w-100">
                            <div class="title-section d-flex align-items-center justify-content-between mb-2">
                                <div class="title-section-inner d-flex align-items-center justify-content-between">
                                    <div class="title-section-heading">
                                        <h5 class="mb-1">
                                            <a href="{{route('admin.tasks.show',$value->id)}}">
                                                {{ ucfirst(\Illuminate\Support\Str::limit($value->name, 40, $end='...')) }}
                                            </a>
                                        </h5>
                                        <p class="small">
                                            <b>Project :</b>
                                            <a href="{{route('admin.projects.show',$value->project->id)}}" class="text-muted">{{ucfirst($value->project->name)}}</a>
                                        </p>
                                    </div>

                                </div>

                                @canany(['edit-task_management','show-task_management','delete-task_management'])
                                    <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="link-icon"  data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" style="">

                                        @can('edit-task_management')
                                            <a href="{{route('admin.tasks.edit',$value->id)}}" class="d-block py-1">
                                                <i class="link-icon me-2" data-feather="edit"></i> Edit
                                            </a>
                                        @endcan

                                        @can('show-task_management')
                                            <a href="{{route('admin.tasks.show',$value->id)}}" class="d-block py-1">
                                                <i class="link-icon me-2" data-feather="eye"></i> View
                                            </a>
                                        @endcan

                                        @can('delete-task_management')
                                            <a data-href="{{route('admin.tasks.delete',$value->id)}}" class="delete d-block py-1">
                                                <i class="link-icon me-2"  data-feather="delete"></i> Delete
                                            </a>
                                        @endcan

                                    </div>
                                </div>
                                @endcanany
                            </div>
                            <div class="badge-section mb-2">
                               <span class="badge badge-soft-success text-end d-inline-block float-end">
                                   {{$value->taskRemainingDaysToComplete() > 0 ? $value->taskRemainingDaysToComplete() : 0 }} days left
                               </span>
                            </div>

                            <div class="progress mb-4">
                                <div class="progress-bar color2 rounded"
                                     role="progressbar"
                                     style="{{\App\Helpers\AppHelper::getProgressBarStyle($value->getTaskProgressInPercentage())}}"
                                     aria-valuenow="25"
                                     aria-valuemin="0"
                                     aria-valuemax="100" >
                                    <span>{{($value->getTaskProgressInPercentage())}} %</span>
                                </div>
                            </div>

                            <div class="date-section d-flex justify-content-between align-items-center">
                                <div class="date-item">
                                    <p class="text-success"><i class="link-icon"  data-feather="calendar"></i>
                                       {{\App\Helpers\AppHelper::formatDateForView($value->start_date)}} -
                                        <span class="text-danger">{{\App\Helpers\AppHelper::formatDateForView($value->end_date)}} </span></p>
                                </div>

                                <div class="member-listed w-25 float-end text-end">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.tasks.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                            </div>

                        </div>
                    </div>
                @empty

                @endforelse
            </div>
        </div>

        <div class="row">
            <div class="dataTables_paginate">
                {{$tasks->appends($_GET)->links()}}
            </div>
        </div>
    </section>


@endsection

@section('scripts')
    @include('admin.task.common.scripts')
@endsection






