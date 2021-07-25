<nav class="client__nav sticky top-0 flex flex-col items-center p-4 lg:w-full z-10">
    <div class="nav__brand mb-3 h-24 w-24">
        <a href="https://stu.edu.vn" class="w-full h-full block" target="_blank">
            <img src="{{ asset('images/Logo_STU.png') }}" class="block w-full">
        </a>
    </div>
    <ul class="nav__navbar flex flex-col items-center lg:w-4/6 mb-3">
        <li class="pill relative mb-3 w-full h-12">
            <a href="{{ route('home') }}" class="{{Route::current()->getName() == 'home' ? 'text-blue-400' : ''}} flex justify-evenly items-center w-full h-full pill-hover font-medium text-2xl text-gray-700">
                <i class="fas fa-home"></i>
                <span class="hidden lg:inline-block lg:text-base xl:text-xl lg:w-4/6">Trang chủ</span>
            </a>
        </li>
        <li class="pill relative mb-3 w-full h-12">
            <a href="{{ route('chat') }}" class="{{Route::current()->getName() == 'chat' ? 'text-blue-400' : ''}} flex justify-evenly items-center w-full h-full pill-hover font-medium text-2xl text-gray-700">
                <i class="far fa-envelope"></i>
                <span class="hidden lg:inline-block lg:text-base xl:text-xl lg:w-4/6">Tin nhắn</span>
            </a>
        </li>
        <li class="pill relative mb-3 w-full h-12">
            <a href="{{ route('profile', auth()->user()) }}" class="{{Route::current()->getName() == 'profile' ? 'text-blue-400' : ''}} flex justify-evenly items-center w-full h-full pill-hover font-medium text-2xl text-gray-700">
                <i class="far fa-user"></i>
                <span class="hidden lg:inline-block lg:text-base xl:text-xl lg:w-4/6">Hồ sơ</span>
            </a>
        </li>
        <li class="pill relative mb-3 w-full h-12" >
            <nav-search :user="{{auth()->user()}}"></nav-search>
        </li>
        <li class="pill relative mb-3 w-full h-12">
            <a href="{{ route('home') }}" class=" flex justify-evenly items-center w-full h-full pill-hover font-medium text-2xl text-gray-700">
                <i class="far fa-bell"></i>
                <span class="hidden lg:inline-block lg:text-base xl:text-xl lg:w-4/6">Thông báo</span>
            </a>
        </li>
        <li class="relative w-full mb-3">
            <!-- Quick Toast Place Here -->
            <quick-toast></quick-toast>
        </li>
    </ul>
    @auth
        <div class="relative flex items-center justify-center w-full px-2">
            <div class="bg-red-500 rounded-lg hover:bg-red-600 text-white">
                <form action="{{ route('logout') }}" method="post" class="w-full h-full">
                    @csrf
                    <button id="logout" class="w-full h-full px-2 py-3 z-50 focus:outline-none flex items-center justify-center" type="submit"><i class="fas fa-sign-out-alt text-2xl md:mr-2"></i> <span class="hidden md:block">Đăng xuất</span> <span class="hidden lg:block ml-2">{{ auth()->user()->username }}</span></button>
                </form>
            </div>
        </div>
    @endauth
</nav>