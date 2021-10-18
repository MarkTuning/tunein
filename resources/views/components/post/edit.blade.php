@props(['heading', 'route', 'body', 'files'])

<div class="edit-post-container">
    <div class="edit-post-heading-text center">{{ $heading }}</div>

    <form id="editPostForm" method="POST" action="{{ $route }}" enctype="multipart/form-data">
        @csrf
        
        @method('PATCH')

        <x-form.textarea name="body" type="text" class="post-input-text scrollbar center" containerClass="post-input-container" rows="7" required="true" placeholder="What's happening?">
            {{ $body }}
        </x-form.textarea>

        <x-form.file name="uploadedFiles" class="post-file-upload center-text" containerClass="post-file-upload-container" multiple="true" accept=".png,.jpeg,.jpg,.gif,.mp4,.webm">
            Attach files...
        </x-form.file>

        <script type="text/javascript" src="{{ asset('/js/create-post.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/edit-post.js') }}"></script>

        <script>
            addFilesToForm("editPostForm");
            showPostFilesPreview('{{ $files }}');
            showUploadedFilesPreview('uploadedFiles');
        </script>

        <div class="post-submit-container block">
            <x-form.submit class="post-button center link">Save</x-form.submit>
        </div>
    </form>
</div>