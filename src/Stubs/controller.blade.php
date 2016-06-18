namespace App\{{ config('starter.controller.path') }};

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ResourcefulTrait;
@if($repository && !$plain)
use App\{{ config('starter.repository.path') . '\\' . studly_case($repository) }};
@endif
@if($transformer && !$plain)
use App\{{ config('starter.transformer.path') . '\\' . studly_case($transformer) }};
@endif

class {{ $name }} extends Controller
{
@if($plain)
    // Plain api controller.
@else
    use ResourcefulTrait;
    @if($repository)

    protected $repository;
    @endif
    @if($transformer)

    protected $transformer;
    @endif

    protected $create_rules = [];

    protected $update_rules = [];

    public function __construct(@if($repository){{ studly_case($repository) }} ${{ camel_case($repository) }}@endif @if($transformer && $repository),@endif @if($transformer){{ studly_case($transformer) }} ${{ camel_case($transformer) }}@endif{{ ')' }}
    {
        //The controller constructor method
@if($repository)
        $this->repository = ${{ camel_case($repository) }};
@endif
@if($transformer)
        $this->transformer = ${{ camel_case($transformer) }};
@endif
    }
@endif
}