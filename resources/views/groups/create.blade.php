@extends('layouts.app')

@section('title', __('groups.create-new-group'))

@section('head')
<script src="{{ mix('js/create_group.js') }}" defer></script>
@endsection

@section('content')
<div class="container" id="create-new-groups-root"></div>
@endsection
