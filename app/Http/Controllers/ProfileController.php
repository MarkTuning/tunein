<?php

namespace App\Http\Controllers;

use App\Models\Following;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        $search = request()->query('search');

        if ($search) {
            $posts = $user->posts()->where('body', 'like', '%'.$search.'%')->get();
        }
        else {
            $posts = $user->posts;
        }

        $files = PostController::getPostsFiles($posts);

        $postLikes = PostController::getLikesOfPosts($posts);

        $userLikes = PostController::getUserLikedPosts($postLikes);

        $postBookmarks = PostController::getBookmarksOfPosts($posts);

        $userBookmarks = PostController::getUserBookmarkedPosts($postBookmarks);
        
        return view('profile.index', [
            'user' => $user,
            'posts' => $posts,
            'files' => $files,
            'postLikes' => $postLikes,
            'userLikes' => $userLikes,
            'postBookmarks' => $postBookmarks,
            'userBookmarks' => $userBookmarks,
        ]);
    }

    public function settings(User $user)
    {
        return view('profile.settings', [
            'user' => $user
        ]);
    }

    public function storeSettings(Request $request, User $user)
    {
        $genders = ['Male', 'Female', 'Unspecified'];

        $attributes = $request->validate([
            'name' => ['required', 'min:3', 'max:64'],
            'username' => ['required', 'min:3', 'max:32', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'gender' => ['required', Rule::in($genders)],
            'password_current' => ['nullable'],
            'password' => ['min:8', 'max:255', 'confirmed', 'nullable'],
            'password_confirmation' => ['nullable']
        ]);

        $profilePicture = $this->validatePicture($request, 'uploadedProfilePictureFile');

        if ($profilePicture != null) {
            $this->validateProfilePictureSize($profilePicture['uploadedProfilePictureFile']->getSize());
        }

        $profileBackgroundPicture = $this->validatePicture($request, 'uploadedBackgroundPictureFile');
        
        if ($profileBackgroundPicture != null) {
            $this->validateBackgroundPictureSize($profileBackgroundPicture['uploadedBackgroundPictureFile']->getSize());
        }

        $this->validatePasswords($user, $attributes);

        if (! isset($profilePicture['uploadedProfilePictureFile'])) {
            $profilePicture['uploadedProfilePictureFile'] = null;
        }

        if (! isset($profileBackgroundPicture['uploadedBackgroundPictureFile'])) {
            $profileBackgroundPicture['uploadedBackgroundPictureFile'] = null;
        }

        $this->saveProfileDetails($attributes, $profilePicture['uploadedProfilePictureFile'], $profileBackgroundPicture['uploadedBackgroundPictureFile']);

        return view('home');
    }

    public function follow(User $user) {
        if (auth()->user() == null) {
            return json_encode("Login");
        }

        $followAttributes = [
            'user_id' => auth()->user()->id,
            'following_id' => $user->id
        ];

        $followedProfile = Following::query()->where([
            ['user_id', '=', $followAttributes['user_id']],
            ['following_id', '=', $followAttributes['following_id']]
        ])->get()->first();

        if ($followedProfile) {
            $followedProfile->delete();

            return json_encode("Unfollowed");
        }

        Following::create($followAttributes);

        return json_encode("Followed");
    }



    protected function validatePicture(Request $request, string $name): array
    {
        $allowedImageExtensions = ['png', 'jpeg'];

        return $request->validate([
            $name => ['mimes:' . implode(',', $allowedImageExtensions), 'nullable']
        ], [
            $name . '.mimes' => 'The files uploaded must be of one of the following types: ' . implode(',', $allowedImageExtensions)
        ]);
    }

    protected function validateProfilePictureSize(int $size): void
    {
        $maxFileSize = 2; // Mbs

        if ($size > $maxFileSize * 1024 * 1024) {
            throw ValidationException::withMessages(['max_picture_size' => 'The maximum post files size must not be larger than ' . $maxFileSize . ' Mbs.']);
        }
    }

    protected function validateBackgroundPictureSize(int $size): void
    {
        $maxFileSize = 5; // Mbs

        if ($size > $maxFileSize * 1024 * 1024) {
            throw ValidationException::withMessages(['max_picture_size' => 'The maximum post files size must not be larger than ' . $maxFileSize . ' Mbs.']);
        }
    }

    protected function validatePasswords(User $user, array $attributes): void
    {
        if ($attributes['password_current'] != null || $attributes['password'] != null || $attributes['password_confirmation'] != null) {
            if (password_verify($attributes['password_current'], $user->password)) {
                if ($attributes['password'] == null) {
                    throw ValidationException::withMessages(['password' => 'Input a new password that you want to have your password changed to.']);
                }
            }
            else {
                throw ValidationException::withMessages(['password_current' => 'Wrong password. Input the current password for the profile.']);
            }
        }
    }

    protected function saveProfileDetails($attributes, $profilePicture, $backgroundPicture): void
    {
        $user = User::query()->where('id', '=', auth()->user()->id)->get()->first();

        if ($profilePicture != null) {
            $profilePictureName = auth()->user()->id . '_' . Str::random(32) . '.' . $profilePicture->extension();
        }

        if ($backgroundPicture != null) {
            $backgroundPictureName = auth()->user()->id . '_' . Str::random(32) . '.' . $backgroundPicture->extension();
        }

        $profileAttributes = [
            'name' => $attributes['name'],
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'profile_picture' => isset($profilePictureName) ? $profilePictureName : $user->profile_picture,
            'background_picture' => isset($backgroundPictureName) ? $backgroundPictureName : $user->background_picture,
            'gender' => $attributes['gender'] !== 'Unspecified' ? $attributes['gender'] : null,
        ];

        if ($profileAttributes['password'] == null) {
            unset($profileAttributes['password']);
        }

        if ($profilePicture != null) {
            $profilePicture->storeAs('profile_pictures', $profilePictureName, 'public');
            Storage::delete('public/profile_pictures/' . $user->profile_picture);
        }

        if ($backgroundPicture != null) {
            $backgroundPicture->storeAs('profile_backgrounds', $backgroundPictureName, 'public');
            Storage::delete('public/profile_backgrounds/' . $user->background_picture);
        }

        $user->update($profileAttributes);
    }
}