<div>
    {!! __('sitefrog.auth::widgets.welcome.heading',
    ['user' => '<strong>'.($user ? $user->username : __('sitefrog.auth::widgets.welcome.guest')).'</strong>']
) !!}
    @if ($user)
        <a href="#" hx-post="{{sitefrogRoute('auth', 'logout')}}" hx-include="#logout_form">{{__('sitefrog.auth::widgets.welcome.logout')}}</a>
        <form id="logout_form">
            @csrf
        </form>
    @endif

</div>
