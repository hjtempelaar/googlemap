<?php

namespace App\Http\Controllers;

use App\Models\G50Heatmap;
use App\Models\Heatmap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class G50HeatmapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     * Ik wil graag een kaart hebben met 50 stippen (50 gemeenten). Per gemeente denk ik aan de volgende weergavevelden:
     * Overall ranking: 18
    Inwoners: 108.558
    Aantal evenementen: 75
    Aantal Bezoeken: 1.074.557
    Evenementensubsidie: € 1.917.298
    Subsidie per inwoner: € 17,66
    2:00
    Een relevante filtermenu zou mijnsinziens moeten bestaan uit:
    2:02
    - provincie
    - gemeente
    - aantal inwoners
    - evenementensubsidie (met van / tot )
    - subsidie per inwoner (met van / tot )
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
          //
        if ($request->has('m')){
            if ($request->get('m') != "sdkkjdfjaksdfaksdjfakjdfkasdf"){
                return response("Wil je ook zo'n mooie monitor?");

            }
        } else {
            return response("");
        }
        $provincies = G50Heatmap::select('provincie')->distinct()->orderBy('provincie')->pluck('provincie')->toArray();
        $gemeentes = G50Heatmap::select('gemeente')->distinct()->orderBy('gemeente')->pluck('gemeente')->toArray();
        $provincieCount = G50Heatmap::select(DB::raw('provincie as provincie, count(*) as totaal'))->groupBy('provincie')->get();
          return view('g50map')->with('provincies', $provincieCount)
              ->with('gemeentes', $gemeentes);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\G50Heatmap  $g50Heatmap
     * @return \Illuminate\Http\Response
     */
    public function show(G50Heatmap $g50Heatmap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\G50Heatmap  $g50Heatmap
     * @return \Illuminate\Http\Response
     */
    public function edit(G50Heatmap $g50Heatmap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\G50Heatmap  $g50Heatmap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, G50Heatmap $g50Heatmap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\G50Heatmap  $g50Heatmap
     * @return \Illuminate\Http\Response
     */
    public function destroy(G50Heatmap $g50Heatmap)
    {
        //
    }

    public function g50HeatMap(Request $request)
    {

        // get edities for given daterange with location and magnitude
        $gemeentes = G50Heatmap::where('provincie', '!=', "");
        if ($request->has('gemeente')) {
            if (substr($request->get('gemeente'), 0, 4) != "Alle") {
                $gemeentes->where('gemeente', '=', $request->get('gemeente'));
            }
        }
        if ($request->has('provincie')) {
            if (substr($request->get('provincie'), 0, 4) != "Alle") {
                $gemeentes->where('provincie', '=', $request->get('provincie'));
            }
        }

        if ($request->has('aantal_bezoeken_min') && $request->has('aantal_bezoeken_max')) {
            $aantal_bezoeken_min = $request->get('aantal_bezoeken_min');
            $aantal_bezoeken_max = $request->get('aantal_bezoeken_max');
            if ($aantal_bezoeken_min > $aantal_bezoeken_max){
                // wisselen!
                $effe = $aantal_bezoeken_max;
                $aantal_bezoeken_max = $aantal_bezoeken_min;
                $aantal_bezoeken_min = $effe;
            }

            if ($aantal_bezoeken_min != '' || $aantal_bezoeken_min != 'null') {
                $gemeentes->where('bereik', '>=', $aantal_bezoeken_min);
                $gemeentes->where('bereik', '<=', $aantal_bezoeken_max);
            }

        }

        //$provincieCount = Heatmap::select(DB::raw('provincie as provincie, count(*) as totaal'))->groupBy('provincie')->get();
        $features = [];
        foreach ($gemeentes->get() as $gemeente) {
            //{"type":"FeatureCollection",
            //"features":[{"type":"Feature","properties":{"mag":3.3,"
            $features[] = array(
                'aantal_bezoeken' => number_format(floatval($gemeente->aantal_bezoeken) ,0,',','.'),
                'aantal_evenementen' => $gemeente->aantal_evenementen,
                'evenement_subsidie' => '€ '. number_format(floatval(str_replace(",",".",$gemeente->evenement_subsidie)), 2, ',', '.'),
                'inwoners' => $gemeente->inwoners,
                'overall_ranking' => $gemeente->overall_ranking,
                'provincie' => $gemeente->provincie,
                'subsidie_per_inwoner' =>"€ " . number_format(floatval(str_replace(",",".",$gemeente->subsidie_per_inwoner)),2,',','.'),
                'titel' => $gemeente->gemeente,
                'type' => 'Feature',
                'properties' => array('place' => $gemeente->evenement,
                    'mag' => round($gemeente->bereik / 100, 0)),
                'geometry' => array('type' => 'Point',
                    'coordinates' => array((float)$gemeente->longitude, (float)$gemeente->latitude),
                    'id' => $gemeente->id),

            );
        }


        $allfeatures = array('count' => count($features),'type' => 'FeatureCollection', 'features' => $features);
        return response()->jsonp('g50feedcallback', $allfeatures);
        //return "eqfeedcallback(" . json_encode($allfeatures, JSON_PRETTY_PRINT) . ");";
    }
}
