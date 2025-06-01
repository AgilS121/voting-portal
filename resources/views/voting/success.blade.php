@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-success">
            <div class="card-header bg-success text-white text-center">
                <h4><i class="fas fa-check-circle me-2"></i>Vote Submitted Successfully!</h4>
            </div>
            <div class="card-body text-center">
                <div class="alert alert-success">
                    <h5>Thank you for participating in the democratic process!</h5>
                    <p class="mb-0">Your vote has been recorded and cannot be changed.</p>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6>Election Details:</h6>
                        <p><strong>{{ $election->title }}</strong></p>
                        <p class="text-muted">{{ $election->description }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Your Vote:</h6>
                        <p><strong>{{ $vote->candidate->name }}</strong></p>
                        <p class="text-muted">Candidate #{{ $vote->candidate->candidate_number }}</p>
                        <small class="text-muted">Voted on: {{ $vote->created_at->format('M d, Y H:i:s') }}</small>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('vote.index') }}" class="btn btn-primary me-2">Vote in Other Elections</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-2">Go to Dashboard</a>
                    <a href="{{ route('vote.history') }}" class="btn btn-outline-secondary">View My Votes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection