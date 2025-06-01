@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>{{ $election->title }}</h4>
                <div class="mt-2">
                    @if($election->status == 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($election->status == 'upcoming')
                        <span class="badge bg-warning">Upcoming</span>
                    @else
                        <span class="badge bg-secondary">Completed</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <p class="lead">{{ $election->description }}</p>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Start Date:</strong><br>{{ $election->start_date->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>End Date:</strong><br>{{ $election->end_date->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                @if($election->status == 'active')
                    @auth
                        <div class="alert alert-success">
                            <i class="fas fa-vote-yea me-2"></i>
                            This election is currently active. You can vote now!
                            <a href="{{ route('vote.show', $election) }}" class="btn btn-success btn-sm ms-3">Vote Now</a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Please <a href="{{ route('login') }}">login</a> to participate in voting.
                        </div>
                    @endauth
                @elseif($election->status == 'upcoming')
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        This election will start on {{ $election->start_date->format('M d, Y H:i') }}
                    </div>
                @else
                    <div class="alert alert-secondary">
                        <i class="fas fa-check-circle me-2"></i>
                        This election has ended. <a href="#">View results</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Candidates ({{ $election->candidates->count() }})</h5>
            </div>
            <div class="card-body">
                @if($election->candidates->count() > 0)
                    <div class="row">
                        @foreach($election->candidates as $candidate)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            @if($candidate->photo)
                                                <img src="{{ asset('storage/' . $candidate->photo) }}" 
                                                     alt="{{ $candidate->name }}" class="rounded-circle me-3" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $candidate->candidate_number }}. {{ $candidate->name }}</h6>
                                                <small class="text-muted">{{ $candidate->position }}</small>
                                                @if($candidate->description)
                                                    <p class="mb-0 mt-1">{{ Str::limit($candidate->description, 100) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No candidates registered for this election yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Election Statistics</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Candidates:</span>
                    <strong>{{ $election->candidates->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Status:</span>
                    <strong>{{ ucfirst($election->status) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Duration:</span>
                    <strong>{{ $election->start_date->diffInDays($election->end_date) }} days</strong>
                </div>
            </div>
        </div>

        @if($election->status == 'active')
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    @auth
                        <a href="{{ route('vote.show', $election) }}" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-vote-yea me-2"></i>Vote in This Election
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Vote
                        </a>
                    @endauth
                    <a href="{{ route('elections.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-list me-2"></i>View All Elections
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection