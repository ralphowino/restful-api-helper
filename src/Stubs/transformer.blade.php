
namespace App\Http\Transformers;

use App\{{ $modelPath }}\{{ $modelName }};
use League\Fractal\TransformerAbstract;


class {{ $modelName }}Transformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Turn this item object into a generic array
     *
     * @param {{ $modelName }} ${{ camel_case($modelName) }}
     * @return array
     */
    public function transform({{ $modelName }} ${{ camel_case($modelName)  }})
    {
        $transformed = [];

        return $transformed;
    }
}