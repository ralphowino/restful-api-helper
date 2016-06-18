namespace App\{{ config('starter.model.path') }};

@if( $archive )
use Illuminate\Database\Eloquent\SoftDeletes;
@endif
use App\{{ config('starter.model.path') }}\BaseModel;

class {{ ucwords($modelName) }} extends BaseModel
{
@if( $archive )
    use SoftDeletes;

@endif
    /**
     * The model's table name.
     */
    protected $table = '{{ str_plural(strtolower($modelName)) }}';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
