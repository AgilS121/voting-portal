@extends('layouts.app')

@section('content')
<h2>Available Elections</h2>

@if($availableElections->count() == 0)
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No elections available for voting at the moment.
    </div>
@else
    <div class="row">
        @foreach($availableElections as $election)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $election->title }}</h5>
                        <p class="card-text">{{ $election->description }}</p>
                        <p class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $election->start_date->format('M d, Y') }} - {{ $election->end_date->format('M d, Y') }}
                        </p>
                        <p class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            {{ $election->candidates->count() }} Candidates
                        </p>
                        <a href="{{ route('vote.show', $election) }}" class="btn btn-success">
                            <i class="fas fa-vote-yea me-1"></i>Vote Now
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection