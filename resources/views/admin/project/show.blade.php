@extends('layouts.master')
@section('title', 'Show Project Detail')
@section('action', 'Detail')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
@endsection

@section('button')
    <div class="float-md-end d-md-flex align-items-center mb-4">

        @can('edit-project_management')
            <a href="{{ route('admin.projects.edit', $projectDetail->id) }}">
                <button class="btn btn-sm btn-success me-md-2 d-md-flex align-items-center"><i class="link-icon"
                        data-feather="edit"></i> Edit project</button>
            </a>
        @endcan

        @can('create-task_management')
            <a href="{{ route('admin.project-task.create', $projectDetail->id) }}">
                <button class="btn btn-sm btn-secondary me-md-2 d-md-flex align-items-center"><i class="link-icon"
                        data-feather="plus"></i> Create Task</button>
            </a>
        @endcan
        <a href="{{ route('admin.project-attachment.create', $projectDetail->id) }}">
            <button class="btn btn-sm btn-primary d-md-flex align-items-center"><i class="link-icon"
                    data-feather="clipboard"></i> Upload Attachments</button>
        </a>
    </div>
@endsection

@section('main-content')

    <section class="content pb-0">

        @include('admin.section.flash_message')

        @include('admin.project.common.breadcrumb')

        <div class="row position-relative">
            <?php
            $ProjectStatus = [
                'in_progress' => 'primary',
                'not_started' => 'primary',
                'on_hold' => 'info',
                'cancelled' => 'danger',
                'completed' => 'success',
            ];
            ?>
            <div class="col-lg-8 mb-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-2">{{ ucfirst($projectDetail->name) }}</h3>
                        <ul class="list-unstyled d-flex mb-0">
                            <li class="text-muted me-2">Total Tasks : {{ $projectDetail->tasks->count() }}</li>
                            <li class="text-muted">Completed Tasks: {{ $projectDetail->completedTask?->count() }}</li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="progress mb-3">
                            <div class="progress-bar color2" role="progressbar"
                                style="{{ \App\Helpers\AppHelper::getProgressBarStyle($projectDetail->getProjectProgressInPercentage()) }}"
                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                <span>{{ $projectDetail->getProjectProgressInPercentage() }} %</span>
                            </div>
                        </div>
                        {!! $projectDetail->description !!}
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-2">Uploaded image files</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            @forelse($images as $key => $imageData)
                                <div class="col-lg-3 mb-4">
                                    <div class="uploaded-image">
                                        <a href="{{ asset(\App\Models\Attachment::UPLOAD_PATH . $imageData->attachment) }}"
                                            data-lightbox="image-1" data-title="{{ $imageData->attachment }}">
                                            <img class="w-100" style=""
                                                src="{{ asset(\App\Models\Attachment::UPLOAD_PATH . $imageData->attachment) }}"
                                                alt="document images">
                                        </a>
                                        <p>{{ $imageData->attachment }}</p>
                                            <a class="documentDelete"
                                                data-href="{{ route('admin.attachment.delete', $imageData->id) }}">
                                                <i class="link-icon remove-image" data-feather="x"></i>
                                            </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No Project Image found</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-2">Uploaded files</h3>
                    </div>
                    <div class="card-body">
                        @forelse($files as $key => $fileData)
                            <div class="uploaded-files">
                                <div class="row align-items-center">
                                    <div class="col-lg-1">
                                        <div class="file-icon">
                                            <i class="link-icon" data-feather="file-text"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-10">
                                        <a target="_blank"
                                            href="{{ asset(\App\Models\Attachment::UPLOAD_PATH . $fileData->attachment) }}">
                                            {{ asset(\App\Models\Attachment::UPLOAD_PATH . $fileData->attachment) }}
                                        </a>
                                        <p>{{ date_format($fileData->created_at, 'M d Y') }}</p>
                                    </div>
                                        <div class="col-lg-1">
                                            <a class="documentDelete"
                                                data-href="{{ route('admin.attachment.delete', $fileData->id) }}">
                                                <i class="link-icon remove-files" data-feather="x"></i>
                                            </a>
                                        </div>
                                </div>

                            </div>
                        @empty

                            <p class="text-muted">No Project Files found</p>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Project Tasks Lists: </h5>
                    </div>
                    <div class="card-body pb-1">
                        @forelse($projectDetail->tasks as $key => $value)
                            <div class="row title-section align-items-center mb-3">
                                <div class="col-lg-9 col-md-9">
                                    <span class="pe-2">{{ ++$key }}.</span><a
                                        href="{{ route('admin.tasks.show', $value->id) }}">{{ ucfirst($value->name) }}</a>
                                </div>
                                <div class="col-lg-2 col-md-2 ps-0 d-flex">
                                    @forelse($value->assignedMembers as $key => $memberDetail)
                                        <img class="rounded-circle checklist-image" style="object-fit: cover"
                                            src="{{ isset($memberDetail->user->avatar)
                                                ? asset(\App\Models\User::AVATAR_UPLOAD_PATH . $memberDetail->user->avatar)
                                                : asset('assets/images/img.png') }}"
                                            alt="profile">
                                    @empty
                                    @endforelse
                                </div>
                                @canany(['edit-task_management', 'show-task_management', 'delete-task_management'])
                                    <div class="col-lg-1 col-md-1 ps-0">
                                        <div class="btn-group card-option float-end">
                                            <button type="button" class="btn dropdown-toggle p-0" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="link-icon" data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" style="">

                                                @can('edit-task_management')
                                                    <a href="{{ route('admin.tasks.edit', $value->id) }}" class="d-block py-1">
                                                        <i class="link-icon me-2" data-feather="edit"></i> Edit
                                                    </a>
                                                @endcan

                                                @can('show-task_management')
                                                    <a href="{{ route('admin.tasks.show', $value->id) }}" class="d-block py-1">
                                                        <i class="link-icon me-2" data-feather="eye"></i> View
                                                    </a>
                                                @endcan

                                                @can('delete-task_management')
                                                    <a data-href="{{ route('admin.tasks.delete', $value->id) }}"
                                                        class="delete d-block py-1">
                                                        <i class="link-icon me-2" data-feather="delete"></i> Delete
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endcanany
                            </div>
                        @empty
                        @endforelse

                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4 sidebar-list position-relative">
                <div class="position-sticky top-0">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Project Summary</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-border">
                                <tbody>
                                    <tr>
                                        <td>Cost:</td>
                                        <td class="text-end">{{ $projectDetail->cost }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Hours:</td>
                                        <td class="text-end">{{ $projectDetail->estimated_hours }}</td>
                                    </tr>
                                    <tr>
                                        <td>Created:</td>
                                        <td class="text-end text-success">
                                            {{ \App\Helpers\AppHelper::formatDateForView($projectDetail->start_date) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Deadline:</td>
                                        <td class="text-end text-danger">
                                            {{ \App\Helpers\AppHelper::formatDateForView($projectDetail->deadline) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Priority:</td>
                                        <td class="text-end">
                                            <span class="btn btn-secondary btn-xs cursor-default">
                                                {{ ucfirst($projectDetail->priority) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Remaining Days:</td>
                                        <td class="text-end"><span
                                                class="badge badge-soft-success text-end d-inline-block float-end">
                                                {{ $projectDetail->projectRemainingDaysToComplete() }} days left
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="border-0">
                                        <td>Status:</td>
                                        <td class="text-end">
                                            <span
                                                class="btn btn-{{ $ProjectStatus[$projectDetail->status] }} cursor-default btn-xs">
                                                {{ \App\Helpers\PMHelper::STATUS[$projectDetail->status] }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex w-100 align-items-center justify-content-between">
                            <h5>Project Leaders</h5>
                            <a class="btn btn-primary btn-sm"
                                href="{{ route('admin.projects.add-employee', ['leader', $projectDetail->id]) }}">
                                Update Leader
                            </a>
                        </div>
                        <div class="card-body pb-1">
                            @foreach ($projectLeader as $key => $value)
                                <div class="member-section-inner d-flex align-items-center mb-3">

                                    <div class="member-section-image me-2">
                                        <img class="rounded-circle" style="object-fit: cover"
                                            src="{{ isset($value->user->avatar)
                                                ? asset(\App\Models\User::AVATAR_UPLOAD_PATH . $value->user->avatar)
                                                : asset('assets/images/img.png') }}"
                                            alt="profile">
                                    </div>
                                    <div class="member-section-heading">
                                        <h5>{{ $value->user->name }}</h5>
                                        <p class="small text-muted">
                                            {{ $value->user->post?->post_name ?? 'N/A' }}
                                        </p>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex w-100 align-items-center justify-content-between">
                            <h5>Project Members</h5>
                            <a class="btn btn-primary btn-sm"
                                href="{{ route('admin.projects.add-employee', ['member', $projectDetail->id]) }}">
                                Update Member
                            </a>
                        </div>
                        <div class="card-body pb-1">
                            @foreach ($assignedMember as $key => $value)
                                <div class="member-section-inner d-flex align-items-center mb-3">
                                    <div class="member-section-image me-2">
                                        <img class="rounded-circle" style="object-fit: cover"
                                            src="{{ isset($value->user->avatar)
                                                ? asset(\App\Models\User::AVATAR_UPLOAD_PATH . $value->user->avatar)
                                                : asset('assets/images/img.png') }}"
                                            alt="profile">
                                    </div>
                                    <div class="member-section-heading">
                                        <h5>{{ $value->user->name }}</h5>
                                        <p class="small text-muted">
                                            {{ $value->user->post?->post_name ?? 'N/A' }}
                                        </p>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Project Client Detail</h5>
                        </div>
                        <div class="card-body">
                            <div class="member-section-inner d-flex align-items-center mb-3">

                                <div class="member-section-image me-2">
                                    <a
                                        href="{{ $projectDetail->client && $projectDetail->client->id ? route('admin.clients.show', $projectDetail->client->id) : '#' }}">
                                        <img class="rounded-circle" style="object-fit: cover"
                                            src="{{ asset(\App\Models\Client::UPLOAD_PATH . $projectDetail->client?->avatar) }}"
                                            width="100" style="object-fit: contain" height="100" alt="">
                                    </a>
                                </div>
                                <div class="member-section-heading">
                                    <h5>{{ $projectDetail->client?->name }}</h5>
                                    <small>{{ $projectDetail->client?->email }}</small>
                                </div>

                            </div>
                            <table class="table table-striped table-border">
                                <tbody>
                                    <tr>
                                        <td>Phone No.:</td>
                                        <td class="text-end">{{ $projectDetail->client?->contact_no }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address:</td>
                                        <td class="text-end">{{ $projectDetail->client->address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Country:</td>
                                        <td class="text-end">
                                            {{ $projectDetail->client?->country ?? 'N/A' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
@endsection

@section('scripts')
    @include('admin.project.common.scripts')
@endsection
