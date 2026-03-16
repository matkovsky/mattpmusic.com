<?php

namespace App\Http\Controllers;

class RedirectController extends Controller
{
    public function tumblrRedirect(string $id, ?string $slug = null)
    {
        $redirects = config('redirects');

        if (isset($redirects[$id])) {
            return redirect()->route('episodes.show', $redirects[$id], 301);
        }

        return redirect()->route('episodes.index');
    }
}
