@extends('layouts.app', ['noNavBar' => 1])

@section('title')Kinderkirche {{ $city->name }}@endsection

@section('content')
    @component('components.container')
        <h1>Kinderkirche {{ $city->name }}</h1>
        <p><b>Von {{ $start->format('d.m.Y') }} bis {{ $end->format('d.m.Y') }}</b></p>
        <a class="btn btn-primary btn-lg" href="{{ route('cc-public-pdf', $city->name) }}"><span
                    class="fa fa-file-pdf"></span> Als PDF herunterladen</a>
        <hr/>

        @if($count)

            <table class="table table-fluid table-striped">
                <thead></thead>
                <tbody>
                @foreach ($services as $dayServices)
                    @foreach ($dayServices as $service)
                        <tr>
                            <td valign="top" width="10%">{{ $service->day->date->format('d.m.Y') }}</td>
                            <td valign="top" width="10%">{{ strftime('%H:%M', strtotime($service->time)) }} Uhr</td>
                            <td valign="top" width="20%"
                                @if($service->hasNonStandardCCLocation()) style="color: red;" @endif>{{ $service->cc_location }}</td>
                            <td valign="top" width="30%">{{ $service->cc_lesson }}</td>
                            <td valign="top" width="30%">{{ $service->cc_staff }} </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

        @else
            <p>In dem angegebenen Zeitraum wurden keine Termine für die Kinderkirche gefunden.</p>
        @endif

    @endcomponent
@endsection