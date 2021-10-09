<x-metadata title="TuneIn - Explore">
    <div class="left-sidebar-container block">
        <div class="left-sidebar-content block">
            <x-sidebar.link-button href="/home">Home</x-sidebar.link-button>
            <x-sidebar.link-button href="/bookmarks">Bookmarks</x-sidebar.link-button>
            <x-sidebar.link-button href="/explore">Explore</x-sidebar.link-button>
            <x-sidebar.link-button href="/profile">Profile</x-sidebar.link-button>
            <x-sidebar.link-button href="/post/create">Post</x-sidebar.link-button>
            <x-sidebar.form-button href="/logout">Log Out</x-sidebar.form-button>
        </div>
    </div>

    <div class="main-container block">
        @foreach ($posts as $post)
            <x-post.panel profilePictureURL="images/pfp.jpg" profileName="{{ $post->author->username }}">
                <div>{{ $post->body }}</div>

                <x-post.file url="images/pfp.jpg" />
            </x-post.panel>
        @endforeach
    </div>

    <div class="right-sidebar-container block">
        <div class="right-sidebar-content block">
            <x-sidebar.search-bar href="/explore">Search...</x-sidebar.search-bar>
        </div>
    </div>
</x-metadata>