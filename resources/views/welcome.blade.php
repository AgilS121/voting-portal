@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="hero-section bg-primary text-white p-4 rounded mb-4">
            <h1>Welcome to Voting System</h1>
            <p class="lead">Your voice matters. Participate in democratic elections and make your choice count.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-light btn-lg">Get Started</a>
            @else
                <a href="{{ route('vote.index') }}" class="btn btn-light btn-lg">Start Voting</a>
            @endguest
        </div>

        @if($activeElections->count() > 0)
            <h3>Active Elections</h3>
            <div class="row">
                @foreach($activeElections as $election)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $election->title }}</h5>
                                <p class="card-text">{{ Str::limit($election->description, 100) }}</p>
                                <small class="text-muted">
                                    Ends: {{ $election->end_date->format('M d, Y H:i') }}
                                </small>
                                <div class="mt-2">
                                    <a href="{{ route('elections.show', $election) }}" class="btn btn-outline-primary">View Details</a>
                                    @auth
                                        <a href="{{ route('vote.show', $election) }}" class="btn btn-success">Vote Now</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Active Elections:</span>
                    <strong>{{ $activeElections->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Upcoming Elections:</span>
                    <strong>{{ $upcomingElections->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection