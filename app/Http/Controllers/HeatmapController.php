<?php

namespace App\Http\Controllers;

use App\Models\Heatmap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeatmapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $provincie = Heatmap::select('provincie')->distinct()->orderBy('provincie')->pluck('provincie')->toArray();
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
        $provincieCount = Heatmap::select(DB::raw('provincie as provincie, count(*) as totaal'))->groupBy('provincie')->get();
        $subCategorieNaam = Heatmap::select('subcategorienaam')->orderBy('subcategorienaam')->distinct()->pluck('subcategorienaam')->toArray();
         return view('googlemap')->with('provincies', $provincieCount)
            ->with('subcategorienamen', $subCategorieNaam)
            ->with('maanden', $months);
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
        $festivals = Heatmap::where('provincie','!=', "");
        if ($request->has('categorie')){
            if (substr($request->get('categorie'),0,4) != "Alle") {
                $festivals->where('subcategorienaam', '=', $request->get('categorie'));
            }
        }
        if ($request->has('provincie')){
            if (substr($request->get('provincie'),0,4) != "Alle") {
                $festivals->where('provincie', '=', $request->get('provincie'));
            }
        }
        if ($request->has('maand')){
            if (substr($request->get('maand'),0,4) != "Alle") {
                $months = array(
                    "januari" => 1,
                    "februari" => 2,
                    "maart"  => 3,
                    "april"  => 4,
                    "mei"  => 5,
                    "juni"  => 6,
                    "Juli"  => 7,
                    "augustus"  => 8,
                    "september"  => 9,
                    "oktober"  => 10,
                    "november"  => 11,
                    "december"  => 12
                );
                $month = $months[$request->get('maand')];
                $festivals->whereRaw('MONTH(startdatum) = ?',[$month]);
            }

        }
        //$provincieCount = Heatmap::select(DB::raw('provincie as provincie, count(*) as totaal'))->groupBy('provincie')->get();

        foreach ($festivals->get() as $festival) {


            //{"type":"FeatureCollection",
            //"features":[{"type":"Feature","properties":{"mag":3.3,"
            $features[] = array(
                'titel' => $festival->evenement,
                'datums' => "Van: " . $festival->startdatum ." Tot en met:  " . $festival->einddatum,
                'locatie' => $festival->locatienaam,
                'plaats' => $festival->nen_plaats . "(". $festival->gemeente . ")",
                'bezoekersaantal' =>$festival->bereik,
                'type' => 'Feature',
                'properties' => array('place' => $festival->evenement,
                    'mag' => round($festival->bereik / 100, 0)),
                'geometry' => array('type' => 'Point',
                    'coordinates' => array((float)$festival->longitude, (float)$festival->latitude),
                    'id' => $festival->evenement_id),

            );        }
        $allfeatures = array('type' => 'FeatureCollection', 'features' => $features);
        //return json_encode($allfeatures)->withCallback('');
        //return response()
        //    ->json($allfeatures)
        //    ->withCallback($request->input('eqfeedcallback'));
        return response()->jsonp('eqfeedcallback',$allfeatures);
        //return "eqfeedcallback(" . json_encode($allfeatures, JSON_PRETTY_PRINT) . ");";
    }

}
