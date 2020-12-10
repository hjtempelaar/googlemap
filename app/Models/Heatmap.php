<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Heatmap
 *
 * @property int $id
 * @property int|null $bereik
 * @property int|null $totaal_direct_bereik
 * @property int|null $evenement_id
 * @property string|null $evenement
 * @property string|null $subcategorienaam
 * @property string|null $vormcategorienaam
 * @property string|null $nen_plaats
 * @property int|null $eveditie
 * @property string|null $startdatum
 * @property string|null $einddatum
 * @property string|null $datumnotitie
 * @property string|null $editiewijziging
 * @property int|null $aantal_dagen
 * @property string|null $locatienaam
 * @property int|null $jaar
 * @property int|null $deelnemers
 * @property int|null $corop
 * @property string|null $gemeente
 * @property int|null $eg_id
 * @property int|null $tblstoreegvormcategorie_numerik
 * @property int|null $tblstoreegsubinhoudscategorie_numerik
 * @property string|null $mra
 * @property string|null $jobcode
 * @property int|null $binnenbuiteneditie
 * @property int|null $entree
 * @property string|null $categorienaam
 * @property int|null $subcategorieid
 * @property int|null $landcode
 * @property string|null $provincie
 * @property float|null $longitude
 * @property float|null $latitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap query()
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereAantalDagen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereBereik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereBinnenbuiteneditie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereCategorienaam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereCorop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereDatumnotitie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereDeelnemers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereEditiewijziging($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereEgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereEinddatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereEntree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereEveditie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereEvenement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereEvenementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereGemeente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereJaar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereJobcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereLandcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereLocatienaam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereMra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereNenPlaats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereProvincie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereStartdatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereSubcategorieid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereSubcategorienaam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereTblstoreegsubinhoudscategorieNumerik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereTblstoreegvormcategorieNumerik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereTotaalDirectBereik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Heatmap whereVormcategorienaam($value)
 * @mixin \Eloquent
 * @property-read mixed $zoekstring
 */
class Heatmap extends Model
{
    use HasFactory;

    protected $guarded = [];

    //



}
