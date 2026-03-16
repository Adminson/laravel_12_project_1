<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu flex-grow-0">
    <div class="container-fluid d-flex h-100">
        <ul class="menu-inner py-1">
            <li class="menu-item {{ request()->routeIs('setting.user.*') ? 'active' : '' }}">
                <a href="{{ route('setting.user.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-smart-home"></i>
                    <div data-i18n="Page 1">User Configuration</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('setting.select_input_list.*') ? 'active' : '' }}">
                <a href="{{ route('setting.select_input_list.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-app-window"></i>
                    <div data-i18n="Page 2">Select Demo</div>
                </a>
            </li>

            <li
                class="menu-item {{ request()->routeIs('setting.company_setting.*', 'setting.system_message.*') ? 'active' : '' }}">
                <a href="{{ route('setting.company.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-app-window"></i>
                    <div data-i18n="Page 2">Company List</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('setting.ui_configuration.*') ? 'active' : '' }}">
                <a href="{{ route('setting.ui_configuration.edit', 1) }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-app-window"></i>
                    <div>Configuration</div>
                </a>
            </li>
        </ul>
    </div>
</aside>