@extends('layouts.pdf')


@section('content')
    <div class="text-center">
        <h1 class="text-5xl font-semibold mb-3">{{ $election->title }}</h1>
        <h2 class="text-2xl mb-6 text-gray-700">{{ $election->description }}</h2>
    </div>
    @foreach ($election->positions as $position)
        <div>
            <h3 class="text-xl text-blue-700 font-semibold">{{ $position->name }}</h3>
            <div class="grid">
                @foreach ($position->candidates as $candidate)
                    <div class="card">
                        <h4>{{ $candidate->name }}</h4>
                        <div class="image">
                            @if ($candidate->photo)
                                <img src="{!! $candidate->photo !!}" alt="">
                            @else
                                <div style="background-color: red; height: 5rem; width: 5rem;"></div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endsection
