

    <!-- sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="{{route('admin.dashboard')}}" class="sidebar-brand">
               <img src="{{asset(\App\Models\Company::UPLOAD_PATH.\App\Helpers\AppHelper::getCompanyLogo())}}"  />
            </a>
            <div class="sidebar-toggler not-active">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div class="sidebar-body">
            <ul class="nav sidebar-menu">
                @include('admin.section.partial.dashboard')
                @include('admin.section.partial.company')
                @include('admin.section.partial.user')
                @include('admin.section.partial.attendance')
                @include('admin.section.partial.client')
                @include('admin.section.partial.projectManagement')
                @include('admin.section.partial.taskManagement')
                @include('admin.section.partial.holiday')
                @include('admin.section.partial.notice')
                @include('admin.section.partial.payroll')
                @include('admin.section.partial.leave')
                @include('admin.section.partial.team-meeting')
                @include('admin.section.partial.tada')
                @include('admin.section.partial.shiftManagement')
                @include('admin.section.partial.assetManagement')
                @include('admin.section.partial.staticPageContent')
                @include('admin.section.partial.ticket')
                @include('admin.section.partial.setting')
                @include('admin.section.partial.farmermanagement')
                @include('admin.section.partial.inventory')
            </ul>
        </div>
    </nav>
    <!-- partial -->


