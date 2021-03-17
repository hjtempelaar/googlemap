<?php

namespace App\Http\Controllers;

use App\Models\FestivalHeatmap;
use App\Models\Heatmap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeatmapController extends Controller
{
    public function zoek($zoek)
    {
        $festivals = FestivalHeatmap::where('provincie', '!=', "");
        if ($zoek != '') {
            $festivals->where('evenement', 'like', '%' . $zoek . '%')
                ->orWhere('nen_plaats', 'like', '%' . strtoupper($zoek) . '%')
                ->orWhere('gemeente', 'like', '%' . $zoek . '%');
        }
        dd($festivals->get()->toarray());


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('m')) {
            if ($request->get('m') != "sdkkjdfjaksdfaksdjfakjdfkasdf") {
                return response("Wil je ook zo'n mooie monitor?");

            }
        } else {
            return response("");
        }
        //
        $provincie = FestivalHeatmap::select('provincie')->distinct()->orderBy('provincie')->pluck('provincie')->toArray();
        //$maanden = Heatmap::select(DB::raw('SELECT DISTINCT  MONTHNAME(startdatum) as maanden'));
        $monthss = array(
            "January" => "januari",
            "February" => "februari",
            "March" => "maart",
            "April" => "april",
            "May" => "mei",
            "June" => "juni",
            "July" => "Juli",
            "August" => "augustus",
            "September" => "september",
            "October" => "oktober",
            "November" => "november",
            "December" => "december"
        );
        $months = array(
            "januari",
            "februari",
            "maart",
            "april",
            "mei",
            "juni",
            "Juli",
            "augustus",
            "september",
            "oktober",
            "november",
            "december"
        );
        $provincieCount = FestivalHeatmap::select(DB::raw('provincie as provincie, count(*) as totaal'))->groupBy('provincie')->get();
        $subCategorieNaam = FestivalHeatmap::select('subcategorienaam')->orderBy('subcategorienaam')->distinct()->pluck('subcategorienaam')->toArray();
        $years = FestivalHeatmap::select('jaar')->orderBy('jaar')->distinct()->pluck('jaar')->toArray();
        return view('googlemap')->with('provincies', $provincieCount)
            ->with('subcategorienamen', $subCategorieNaam)
            ->with('maanden', $months)
            ->with('jaren', $years);
        //return view('profile.edit');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Heatmap $heatmap
     * @return \Illuminate\Http\Response
     */
    public function show(Heatmap $heatmap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Heatmap $heatmap
     * @return \Illuminate\Http\Response
     */
    public function edit(Heatmap $heatmap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Heatmap $heatmap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Heatmap $heatmap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Heatmap $heatmap
     * @return \Illuminate\Http\Response
     */
    public function destroy(Heatmap $heatmap)
    {
        //
    }

    public function festivalHeatMap(Request $request)
    {

        // get edities for given daterange with location and magnitude
        $festivals = FestivalHeatmap::where('provincie', '!=', "");
        if ($request->has('categorie')) {
            if (substr($request->get('categorie'), 0, 4) != "Alle") {
                $festivals->where('subcategorienaam', '=', $request->get('categorie'));
            }
        }
        if ($request->has('provincie')) {
            if (substr($request->get('provincie'), 0, 4) != "Alle") {
                $festivals->where('provincie', '=', $request->get('provincie'));
            }
        }
        if ($request->has('jaar')) {
            if (substr($request->get('jaar'), 0, 4) != "Alle") {
                $year = $request->get('jaar');
                $festivals->where('jaar', $year);
            }
        }

        if ($request->has('maand')) {
            if ($request->get('maand') == "Geen Doorgang") {
                $festivals->where('doorgang','=',0);

            } else {
                $festivals->where('doorgang','=',1);
                if (substr($request->get('maand'), 0, 4) != "Alle") {
                    $months = array(
                        "januari" => 1,
                        "februari" => 2,
                        "maart" => 3,
                        "april" => 4,
                        "mei" => 5,
                        "juni" => 6,
                        "Juli" => 7,
                        "augustus" => 8,
                        "september" => 9,
                        "oktober" => 10,
                        "november" => 11,
                        "december" => 12
                    );
                    $month = $months[$request->get('maand')];
                    $festivals->whereRaw('MONTH(startdatum) = ?', [$month]);
                }
            }

        }
        $festivals->whereNotNull('latitude');
        if ($request->has('zoek')) {
            $zoek = $request->get('zoek');
            if ($zoek != '' || $zoek != 'null') {
                $festivals->where('zoekstring', 'like', '%' . $zoek . '%');
            }
        }
        if ($request->has('aantal_bezoeken_min') && $request->has('aantal_bezoeken_max')) {
            $aantal_bezoeken_min = $request->get('aantal_bezoeken_min');
            $aantal_bezoeken_max = $request->get('aantal_bezoeken_max');
            if ($aantal_bezoeken_min > $aantal_bezoeken_max) {
                // wisselen!
                $effe = $aantal_bezoeken_max;
                $aantal_bezoeken_max = $aantal_bezoeken_min;
                $aantal_bezoeken_min = $effe;
            }

            if ($aantal_bezoeken_min != '' || $aantal_bezoeken_min != 'null') {
                $festivals->where('bereik', '>=', $aantal_bezoeken_min);
                $festivals->where('bereik', '<=', $aantal_bezoeken_max);
            }

        }

//$provincieCount = Heatmap::select(DB::raw('provincie as provincie, count(*) as totaal'))->groupBy('provincie')->get();
        $features = [];
        foreach ($festivals->get() as $festival) {
            //{"type":"FeatureCollection",
            //"features":[{"type":"Feature","properties":{"mag":3.3,"
            if ($festival->startdatum) {
                $startdatum = Carbon::createFromFormat('Y-m-d', $festival->startdatum)->format('d-m-Y');
            } else {
                $startdatum = ' nvt ';
            }
            if ($festival->einddatum) {
                $einddatum = Carbon::createFromFormat('Y-m-d', $festival->einddatum)->format('d-m-Y');
            } else {
                $einddatum = ' nvt ';
            }
            $features[] = array(
                'titel' => $festival->evenement,
                'jaar' => $festival->jaar,
                'datum_notitie_lib' => $festival->datum_notitie_lib,
                'startdatum' => $startdatum, //Carbon::createFromFormat('Y-m-d',$festival->startdatum)->format('d-m-Y'),
                'einddatum' => $einddatum, //Carbon::createFromFormat('Y-m-d',$festival->einddatum)->format('d-m-Y'),
                'locatie' => $festival->locatienaam,
                'plaats' => $festival->nen_plaats . "(" . $festival->gemeente . ")",
                'genre' => $festival->subcategorienaam,
                'bezoekersaantal' => $festival->bereik,
                'type' => 'Feature',
                'properties' => array('place' => $festival->evenement,
                    'mag' => round($festival->bereik / 100, 0)),
                'geometry' => array('type' => 'Point',
                    'coordinates' => array((float)$festival->longitude, (float)$festival->latitude),
                    'id' => $festival->evenement_id),

            );
        }


        $allfeatures = array('count' => count($features), 'type' => 'FeatureCollection', 'features' => $features);
        return response()->jsonp('eqfeedcallback', $allfeatures);
//return "eqfeedcallback(" . json_encode($allfeatures, JSON_PRETTY_PRINT) . ");";
    }

}
