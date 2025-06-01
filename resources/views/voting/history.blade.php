@extends('layouts.app')

@section('content')
<h2>My Voting History</h2>

@if($votes->count() == 0)
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        You haven't voted in any elections yet.
        <a href="{{ route('vote.index') }}" class="btn btn-primary btn-sm ms-3">Start Voting</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Election</th>
                    <th>Candidate</th>
                    <th>Vote Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($votes as $vote)
                <tr>
                    <td>
                        <strong>{{ $vote->election->title }}</strong><br>
                        <small class="text-muted">{{ $vote->election->description }}</small>
                    </td>
                    <td>
                        <strong>{{ $vote->candidate->name }}</strong><br>
                        <small class="text-muted">Candidate #{{ $vote->candidate->candidate_number }}</small>
                    </td>
                    <td>{{ $vote->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        @if($vote->election->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($vote->election->status == 'completed')
                            <span class="badge bg-secondary">Completed</span>
                        @else
                            <span class="badge bg-warning">{{ ucfirst($vote->election->status) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $votes->links() }}
@endif
@endsection