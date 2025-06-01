@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2>Welcome, {{ auth()->user()->full_name }}!</h2>
        
        @if(!auth()->user()->is_verified)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Your account is pending verification. You cannot vote until verified.
            </div>
        @endif

        @if($availableElections->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Available Elections</h5>
                </div>
                <div class="card-body">
                    @foreach($availableElections as $election)
                        <div class="border-bottom pb-3 mb-3">
                            <h6>{{ $election->title }}</h6>
                            <p class="text-muted mb-2">{{ Str::limit($election->description, 100) }}</p>
                            <small class="text-muted">Ends: {{ $election->end_date->format('M d, Y H:i') }}</small>
                            <div class="mt-2">
                                <a href="{{ route('vote.show', $election) }}" class="btn btn-success btn-sm">Vote Now</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($myVotes->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5>Recent Votes</h5>
                </div>
                <div class="card-body">
                    @foreach($myVotes as $vote)
                        <div class="border-bottom pb-2 mb-2">
                            <strong>{{ $vote->election->title }}</strong><br>
                            <small class="text-muted">
                                Voted for: {{ $vote->candidate->name }} on {{ $vote->created_at->format('M d, Y H:i') }}
                            </small>
                        </div>
                    @endforeach
                    <a href="{{ route('vote.history') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Your Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Votes:</span>
                    <strong>{{ $stats['my_votes'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Available Elections:</span>
                    <strong>{{ $stats['available_votes'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Active Elections:</span>
                    <strong>{{ $stats['active_elections'] }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Account Status:</span>
                    <span class="badge {{ auth()->user()->is_verified ? 'bg-success' : 'bg-warning' }}">
                        {{ auth()->user()->is_verified ? 'Verified' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection