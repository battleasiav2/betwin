@extends($activeTemplate . 'layouts.master')
@section('content')
    @include($activeTemplate . 'partials.live_battles', ['showAllMatches' => true])
@endsection
