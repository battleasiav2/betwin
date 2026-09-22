@extends($activeTemplate . 'layouts.app')
@section('app')
    @include($activeTemplate . 'partials.user_header')

    <section class="p-0">
        <div class="container-fluid p-0">
            @yield('content')
        </div>
    </section>
@endsection

@push('script')
    <script>
        "use strict";
        $(document).on('click touchstart', function(e) {
            $('.win-loss-popup').removeClass('active');
        });
    </script>
@endpush