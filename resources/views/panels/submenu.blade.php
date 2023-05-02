{{-- For submenu --}}
{{--  @dd(Route::currentRouteName()); --}}
<ul class="pl-2 menu-content">
  @if(isset($menu))
  @foreach($menu as $submenu)
    @can($submenu->permission_access)
      <li class="{{ in_array(Route::currentRouteName(),$submenu->slug)? 'active' : '' }} {{ $sub_menu }}" >
        <a href="{{isset($submenu->url) ? url($submenu->url):'javascript:void(0)'}}" class="d-flex align-items-center  {{ isset($submenu->classlist)?$submenu->classlist:'' }} {{ $firsttag }}" target="{{isset($submenu->newTab) && $submenu->newTab === true  ? '_blank':'_self'}}">
          @if(isset($submenu->icon))
          <i data-feather="{{$submenu->icon}}"></i>
          @endif
          <span class="menu-item text-truncate">{{ __('locale.'.$submenu->name) }} </span>
        </a>
        @if (isset($submenu->submenu))
        @include('panels/submenu', ['menu' => $submenu->submenu, 'sub_menu' => 'second_sub_menu', 'firsttag' => 'firstsubtag'])
        @endif
      </li>
    @endcan
  @endforeach
  @endif
</ul>
