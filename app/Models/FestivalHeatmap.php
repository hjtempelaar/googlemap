<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FestivalHeatmap
 *
 * @property int $id
 * @property string|null $evenement
 * @property int|null $bereik
 * @property int|null $jaar
 * @property string|null $startdatum
 * @property string|null $einddatum
 * @property int|null $datum_notitie_lib
 * @property string|null $locatienaam
 * @property int|null $provincieId
 * @property int|null $Bezoek_provincie_lib
 * @property string|null $longitude
 * @property string|null $latitude
 * @property string|null $nen_plaats
 * @property string|null $categorie_inhoud
 * @property string|null $zoekstring
 * @property string|null $provincie
 * @property int|null $TotaalDirectBereik
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap query()
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereBereik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereBezoekProvincieLib($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereCategorieInhoud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereDatumNotitieLib($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereEinddatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereEvenement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereJaar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereLocatienaam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereNenPlaats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereProvincie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereProvincieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereStartdatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereTotaalDirectBereik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereZoekstring($value)
 * @mixin \Eloquent
 * @property string|null $subcategorienaam
 * @property string|null $gemeente
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereGemeente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FestivalHeatmap whereSubcategorienaam($value)
 */
class FestivalHeatmap extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $connection = 'marisclone';
    protected $table = 'v_heatmap_festivals';
    protected $primaryKey = 'id';
}
