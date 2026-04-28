<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="userForm" novalidate>
                <div class="modal-body">

                    {{-- Hidden user ID (empty = create, filled = edit) --}}
                    <input type="hidden" id="userId" name="id">

                    {{-- Name --}}
                    <div class="mb-4">
                        <x-form.input-label for="userName" value="Name" :required="true" />
                        <x-form.input-text
                            name="name"
                            id="userName"
                            placeholder="Full name"
                        />
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <x-form.input-label for="userEmail" value="Email" :required="true" />
                        <x-form.input-text
                            name="email"
                            id="userEmail"
                            type="email"
                            placeholder="email@example.com"
                        />
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <x-form.input-label for="userPassword" value="Password" :required="true" />
                        <div class="input-group input-group-merge form-password-toggle">
                            <input
                                type="password"
                                id="userPassword"
                                name="password"
                                class="form-control"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                autocomplete="new-password"
                            />
                            <span class="input-group-text cursor-pointer">
                                <i class="icon-base ti tabler-eye-off"></i>
                            </span>
                        </div>
                        <small id="passwordHint" class="text-muted" style="display:none;">
                            Leave blank to keep existing password.
                        </small>
                    </div>

                    {{-- User Type --}}
                    <div class="mb-4">
                        <x-form.input-label for="userType" value="User Type" :required="true" />
                        <x-form.input-select
                            name="user_type"
                            id="userType"
                            :options="['user' => 'User', 'staff' => 'Staff', 'admin' => 'Admin']"
                            placeholder="Select user type"
                        />
                    </div>

                    {{-- Mobile No --}}
                    <div class="mb-4">
                        <x-form.input-label for="userMobileNo" value="Mobile No" />
                        <x-form.input-text
                            name="mobile_no"
                            id="userMobileNo"
                            placeholder="+601X-XXXXXXXX"
                            maxlength="25"
                        />
                    </div>

                    {{-- Account Lock --}}
                    <div class="mb-2">
                        <x-form.checkbox
                            name="account_lock"
                            id="accountLock"
                            label="Lock this account"
                            value="1"
                        />
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>
