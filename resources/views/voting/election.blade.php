@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>{{ $election->title }}</h4>
            </div>
            <div class="card-body">
                <p>{{ $election->description }}</p>
                
                <form action="{{ route('vote.submit', $election) }}" method="POST" id="voteForm">
                    @csrf
                    <h5>Select Your Candidate:</h5>
                    
                    @foreach($candidates as $candidate)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="candidate_id" 
                                           value="{{ $candidate->id }}" id="candidate{{ $candidate->id }}" required>
                                    <label class="form-check-label" for="candidate{{ $candidate->id }}">
                                        <div class="d-flex align-items-center">
                                            @if($candidate->photo)
                                                <img src="{{ asset('storage/' . $candidate->photo) }}" 
                                                     alt="{{ $candidate->name }}" class="rounded-circle me-3" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $candidate->candidate_number }}. {{ $candidate->name }}</h6>
                                                <small class="text-muted">{{ $candidate->position }}</small>
                                                @if($candidate->description)
                                                    <p class="mb-0 mt-1">{{ $candidate->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to submit your vote? This action cannot be undone.')">
                            <i class="fas fa-vote-yea me-2"></i>Submit Vote
                        </button>
                        <a href="{{ route('vote.index') }}" class="btn btn-secondary btn-lg ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Election Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Start Date:</strong><br>{{ $election->start_date->format('M d, Y H:i') }}</p>
                <p><strong>End Date:</strong><br>{{ $election->end_date->format('M d, Y H:i') }}</p>
                <p><strong>Total Candidates:</strong><br>{{ $candidates->count() }}</p>
                <div class="alert alert-warning">
                    <small><i class="fas fa-exclamation-triangle me-1"></i>
                    You can only vote once in this election. Please make your choice carefully.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection