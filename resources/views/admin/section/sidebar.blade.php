

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
                @include('admin.section.partial.hrm')
                @include('admin.section.partial.farmermanagement')
                @include('admin.section.partial.inventory')
            </ul>
        </div>
    </nav>
    <!-- partial -->


