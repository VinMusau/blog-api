<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
   

    
    public function updateAvatar(Request $request)
    {
        $request-> validate([
            'avatar' => 'required|image | mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars','public');

        $user->update([
            'avatar' => $path
        ]);

        return response() -> json ([
            'message' => 'Avatar updated successfully',
            'user' => $user,
            'url' => asset('storage/' .$path)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function deleteAvatar(Request $request)
    {
        $user = $request->user();
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);

            $user->update([
                'avatar' => null
            ]);
            return response()->json([
                'message' => 'Avatar Deleted successfully'
            ]);
        }
        return response()->json([
            'message' => 'No avatar to delete'
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
