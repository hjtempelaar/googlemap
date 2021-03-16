<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\G50Heatmap
 *
 * @property int $id
 * @property string|null $gemeente
 * @property string|null $provincie
 * @property string|null $overall_ranking
 * @property int|null $inwoners
 * @property int|null $aantal_evenementen
 * @property int|null $aantal_bezoeken
 * @property string|null $evenement_subsidie
 * @property string|null $subsidie_per_inwoner
 * @property float|null $longitude
 * @property float|null $latitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap query()
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereAantalBezoeken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereAantalEvenementen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereEvenementSubsidie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereGemeente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereInwoners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereOverallRanking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereProvincie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereSubsidiePerInwoner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|G50Heatmap whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class G50Heatmap extends Model
{
    use HasFactory;
}
