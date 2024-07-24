@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profile</h1>
    <p>Name: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
    <p>Registered on: {{ $user->created_at->format('d M Y') }}</p>
</div>
@endsection
