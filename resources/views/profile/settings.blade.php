<x-metadata title="Settings - TuneInMedia">
    <div id="preview" class="preview-container block hidden"></div>
    <x-flash />

    <div class="left-sidebar-container block">
        <div class="left-sidebar-content scrollbar block">
            <x-sidebar.link-button href="{{ route('home') }}">Back to Home</x-sidebar.link-button>
        </div>
    </div>

    <div class="main-container block">
        <div class="heading-text center">Settings</div>

        @if ($errors->any())
            <div class="error center-text profile-settings-error-container">Oops. Some of the provided information is invalid.</div>
        @endif

        <form method="POST" action="{{ route('profile.settings.store', auth()->user()->username) }}" enctype="multipart/form-data">
            @csrf

            <div class="profile-settings-section-text center">Change Profile Details</div>
            <x-form.input name="name" type="text" class="register-input-text center" containerClass="register-input-container" value="{{ $user->name }}" title="Name">Name</x-form.input>
            <x-form.input name="username" type="text" class="register-input-text center" containerClass="register-input-container" value="{{ $user->username }}" title="Username">Username</x-form.input>
            {{-- <x-form.input name="email" type="email" class="register-input-text center" containerClass="register-input-container" value="{{ $user->email }}" title="Email">Email</x-form.input> --}}
            <div class="profile-settings-gender-container">
                <span>Gender:</span>
                <x-form.radio name="gender" id="male-radio-button" class="" containerClass="" value="Male" checked="{{ $user->gender === 'Male' ? 'checked' : '' }}">Male</x-form.radio>
                <x-form.radio name="gender" id="female-radio-button" class="" containerClass="" value="Female" checked="{{ $user->gender === 'Female' ? 'checked' : '' }}">Female</x-form.radio>
                <x-form.radio name="gender" id="unspecified-radio-button" class="" containerClass="" value="Unspecified" checked="{{ $user->gender == null ? 'checked' : '' }}">Unspecified</x-form.radio>
            </div>
            <div class="profile-settings-section-text center">Change Profile Picture</div>
            <x-profile.image-preview id="profile-picture" url="{{ $user->profile_picture }}" />
            <x-form.file name="uploadedProfilePictureFile" class="profile-image-preview-upload center" containerClass="profile-settings-change-text center" accept=".png,.jpeg,.jpg" hideUploadsContainer="true" containerClassPure="true"><span class="link link-color" id="uploadedProfilePictureFileButton">Change...</span></x-form.file>
            <div class="profile-settings-remove-image-container center">
                <x-form.checkbox name="profilePictureRemove" id="profile-picture-remove-button" class="profile-remove-picture-text" containerClass="center" inputClass="profile-remove-picture-checkbox">Remove Profile Picture</x-form.checkbox>
            </div>
            <script>
                showProfilePicturePreview("uploadedProfilePictureFile", "{{ asset('') }}");
                setRemoveProfilePictureButtonFunctionality("{{ asset('') }}");
            </script>
            <div class="profile-settings-section-text center">Change Background Picture</div>
            <x-profile.background-preview id="profile-background" url="{{ $user->background_picture }}" />
            <x-form.file name="uploadedBackgroundPictureFile" class="profile-image-preview-upload center" containerClass="profile-settings-prevent-hidden-behind-background center" accept=".png,.jpeg,.jpg" hideUploadsContainer="true" containerClassPure="true"><span class="link link-color" id="uploadedBackgroundPictureFileButton">Change...</span></x-form.file>
            <div class="profile-settings-remove-image-container center">
                <x-form.checkbox name="backgroundPictureRemove" id="background-picture-remove-button" class="profile-remove-picture-text" containerClass="center" inputClass="profile-remove-picture-checkbox">Remove Background Picture</x-form.checkbox>
            </div>
            <script>
                showBackgroundPicturePreview("uploadedBackgroundPictureFile", "{{ asset('') }}");
                setRemoveBackgroundPictureButtonFunctionality("{{ asset('') }}")
            </script>
            <div class="profile-settings-section-text center">Change Password</div>
            <x-form.input name="password_current" type="password" class="register-input-text center" containerClass="register-input-container" disableOldValue="true">Current Password</x-form.input>
            <x-form.input name="password" type="password" class="register-input-text center" containerClass="register-input-container">New Password</x-form.input>
            <x-form.input name="password_confirmation" type="password" class="register-input-text center" containerClass="register-input-container">Repeat New Password</x-form.input>

            <div class="profile-settings-submit-container block">
                <x-form.submit class="register-button center link">Save</x-form.submit>
            </div>
        </form>
    </div>

    <div class="right-sidebar-container block">
        <div class="right-sidebar-content scrollbar block">
        </div>
    </div>
</x-metadata>