@extends('layouts.app')

@section('title', 'Kirche bearbeiten')

@section('content')
    @component('components.container')
    <div class="card">
        <div class="card-header">
            Kirche bearbeiten
        </div>
        <div class="card-body">
            @component('components.errors')
            @endcomponent
            <form method="post" action="{{ route('locations.update', $location->id) }}">
                @method('PATCH')
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ $location->name }}" />
                </div>
                <div class="form-group">
                    <label for="location_id">Kirchengemeinde:</label>
                    <select class="form-control" name="city_id">
                        @foreach ($cities->all() as $city)
                            <option value="{{ $city->id }}"
                                    @if ($city->id == old('city_id', $location->city_id))
                                    selected
                                @endif
                            >{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="default_time">Gottesdienst um:</label>
                    <input type="text" class="form-control" name="default_time" value="{{ Carbon\Carbon::createFromTimeString($location->default_time)->format('h:i')}}" />
                </div>
                <div class="form-group">
                    <label for="cc_default_location">Wenn parallel Kinderkirche stattfindet, dann normalerweise hier:</label>
                    <input type="text" class="form-control" id="cc_default_location" name="cc_default_location" value="{{ $location->cc_default_location }}"/>
                </div>
                <button type="submit" class="btn btn-primary">Speichern</button>
            </form>
        </div>
    </div>
    @endcomponent
@endsection
