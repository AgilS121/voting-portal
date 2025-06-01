@extends('layouts.app')

@section('content')
<h2>Elections</h2>

@if($activeElections->count() > 0)
    <h4>Active Elections</h4>
    <div class="row mb-4">
        @foreach($activeElections as $election)
            <div class="col-md-6 mb-3">
                <div class="card border-success">
                    <div class="card-body">
                        <h5 class="card-title">{{ $election->title }}</h5>
                        <p class="card-text">{{ Str::limit($election->description, 150) }}</p>
                        <p class="text-success">
                            <i class="fas fa-play-circle me-1"></i>
                            Active until {{ $election->end_date->format('M d, Y H:i') }}
                        </p>
                        <p class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            {{ $election->candidates->count() }} Candidates
                        </p>
                        <a href="{{ route('elections.show', $election) }}" class="btn btn-outline-primary">View Details</a>
                        @auth
                            <a href="{{ route('vote.show', $election) }}" class="btn btn-success">Vote Now</a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($upcomingElections->count() > 0)
    <h4>Upcoming Elections</h4>
    <div class="row mb-4">
        @foreach($upcomingElections as $election)
            <div class="col-md-6 mb-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <h5 class="card-title">{{ $election->title }}</h5>
                        <p class="card-text">{{ Str::limit($election->description, 150) }}</p>
                        <p class="text-warning">
                            <i class="fas fa-clock me-1"></i>
                            Starts {{ $election->start_date->format('M d, Y H:i') }}
                        </p>
                        <p class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            {{ $election->candidates->count() }} Candidates
                        </p>
                        <a href="{{ route('elections.show', $election) }}" class="btn btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($completedElections->count() > 0)
    <h4>Completed Elections</h4>
    <div class="row">
        @foreach($completedElections as $election)
            <div class="col-md-6 mb-3">
                <div class="card border-secondary">
                    <div class="card-body">
                        <h5 class="card-title">{{ $election->title }}</h5>
                        <p class="card-text">{{ Str::limit($election->description, 150) }}</p>
                        <p class="text-secondary">
                            <i class="fas fa-check-circle me-1"></i>
                            Completed {{ $election->end_date->format('M d, Y H:i') }}
                        </p>
                        <p class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            {{ $election->candidates->count() }} Candidates
                        </p>
                        <a href="{{ route('elections.show', $election) }}" class="btn btn-outline-primary">View Details</a>
                        <a href="#" class="btn btn-outline-secondary">View Results</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($activeElections->count() == 0 && $upcomingElections->count() == 0 && $completedElections->count() == 0)
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle me-2"></i>
        No elections available at the moment.
    </div>
@endif
@endsection