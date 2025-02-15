<?php

namespace App\Http\Controllers\Backend\Transport;

use App\Enum\HafasTravelType;
use App\Enum\TripSource;
use App\Http\Controllers\Backend\Transport\ManualTripCreator as TripBackend;
use App\Http\Controllers\Controller;
use App\Models\HafasOperator;
use App\Models\Trip;
use App\Models\Station;
use App\Models\Stopover;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ManualTripCreator extends Controller
{

    private ?Trip $trip;
    //
    public HafasTravelType $category;
    public string          $lineName;
    public ?int            $journeyNumber;
    public ?HafasOperator $operator;
    public Station        $origin;
    public Carbon         $originDeparturePlanned;
    public Station $destination;
    public Carbon  $destinationArrivalPlanned;

    public function createTrip(): Trip {
        $this->trip = Trip::create([
                                            'trip_id'        => TripBackend::generateUniqueTripId(),
                                            'category'       => $this->category,
                                            'number'         => $this->lineName,
                                            'linename'       => $this->lineName,
                                            'journey_number' => $this->journeyNumber,
                                            'operator_id'    => $this->operator->id ?? null,
                                            'origin'         => $this->origin->ibnr,
                                            'destination'    => $this->destination->ibnr,
                                            'departure'      => $this->originDeparturePlanned,
                                            'arrival'        => $this->destinationArrivalPlanned,
                                            'source'         => TripSource::USER,
                                            'user_id'        => auth()->user()?->id ?? null,
                                        ]);
        return $this->trip;
    }

    public function createOriginStopover(): Stopover {
        if ($this->trip === null) {
            throw new \InvalidArgumentException('Cannot create stopover without trip');
        }
        return Stopover::create([
                                         'trip_id'           => $this->trip->trip_id,
                                         'train_station_id'  => $this->origin->id,
                                         'arrival_planned'   => $this->originDeparturePlanned,
                                         'departure_planned' => $this->originDeparturePlanned,
                                     ]);
    }

    public function createDestinationStopover(): Stopover {
        if ($this->trip === null) {
            throw new \InvalidArgumentException('Cannot create stopover without trip');
        }
        return Stopover::create([
                                         'trip_id'           => $this->trip->trip_id,
                                         'train_station_id'  => $this->destination->id,
                                         'arrival_planned'   => $this->destinationArrivalPlanned,
                                         'departure_planned' => $this->destinationArrivalPlanned,
                                     ]);
    }

    public static function generateUniqueTripId(): string {
        $tripId = Str::uuid();
        while (Trip::where('trip_id', $tripId)->exists()) {
            return self::generateUniqueTripId();
        }
        return $tripId;
    }
}
