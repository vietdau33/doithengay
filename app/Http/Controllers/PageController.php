<?php

namespace App\Http\Controllers;


use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showLogs(): Factory|View|Application
    {
        return view('logs');
    }

    public function home(): Factory|View|Application
    {
        return view('welcome');
    }

    public function profile(): Factory|View|Application
    {
        return view('user.profile');
    }

    public function changeProfile(): Factory|View|Application
    {
        return view('user.change_profile');
    }

    public function changeProfilePost(ProfileRequest $request): RedirectResponse
    {
        if (!$this->userService->changeProfile($request)) {
            session()->flash('notif', 'Đã có một vài lỗi xảy ra khi thay đổi thông tin. Hãy liên hệ với admin để kiểm tra!');
            return redirect()->back()->withInput();
        }
        return redirect()->route('profile');
    }

    public function changePassword(): Factory|View|Application
    {
        return view('user.change_password');
    }

    public function changePasswordPost(PasswordRequest $request): RedirectResponse
    {
        if (!$this->userService->changePassword($request)) {
            session()->flash('mgs_error', 'Không thể thay đổi mật khẩu. Hãy liên hệ với admin để kiểm tra!');
            return redirect()->back();
        }
        session()->flash('notif', 'Thay đổi mật khẩu thành công. Hãy đăng nhập lại!');
        auth()->logout();
        return redirect()->route('auth.view');
    }

    public function commingSoon(): RedirectResponse
    {
        session()->flash('notif', 'Chức năng đang phát triển. Vui lòng quay lại sau!');
        return back();
    }
}
