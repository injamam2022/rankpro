<a href="{{ $href }}"
   class="rp-act rp-act-{{ $variant }}"
   title="{{ $title }}"
   aria-label="{{ $title }}"
   @isset($onclick) onclick="{{ $onclick }}" @endisset>
    <i class="material-icons">{{ $icon }}</i>
</a>
