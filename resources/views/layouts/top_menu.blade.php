   <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu flex-grow-0">
       <div class="container-fluid d-flex h-100">
           <ul class="menu-inner py-1">
               <li class="menu-item {{ request()->routeIs('user_setting_*') ? 'active' : '' }}">
                   <a href="{{ route('user_setting_index') }}" class="menu-link">
                       <i class="menu-icon icon-base ti tabler-smart-home"></i>
                       <div data-i18n="Page 1">User Configuration</div>
                   </a>
               </li>

               <li class="menu-item {{ request()->routeIs('admin_select_input_lists_*') ? 'active' : '' }}">
                   <a href="{{ route('admin_select_input_lists_index') }}" class="menu-link">
                       <i class="menu-icon icon-base ti tabler-app-window"></i>
                       <div data-i18n="Page 2">Select Demo</div>
                   </a>
               </li>
                <li class="menu-item {{ request()->routeIs('company_setting_*', 'system_message_*') ? 'active' : '' }}">
                    <a href="{{ route('company_setting_index') }}" class="menu-link">
                        <i class="menu-icon icon-base ti tabler-app-window"></i>
                        <div data-i18n="Page 2">Company List</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('global_setup_*') ? 'active' : '' }}">
                    <a href="{{ route('global_setup_index') }}" class="menu-link">
                        <i class="menu-icon icon-base ti tabler-adjustments-horizontal"></i>
                        <div data-i18n="Page 3">Global Setup</div>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
