@auth
  @include('inc.navbar-user')
@else
  @include('inc.navbar-guest')
@endauth
