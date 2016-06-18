<?php namespace App\Http\Controllers\Oauth;

use App\Data\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateClientRequest;

class ClientsController extends Controller
{
    /**
     * Lists down the clients of the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //Fetch the authenticated user's clients
        $clients = \Auth::user()->clients()->latest()->paginate($request->get('per_page' , 10));
        return view('clients.index', compact('clients'));
    }

    /**
     * Loads the form to create a new client application
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a new client for the Oauth 2.0
     *
     * @param CreateClientRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateClientRequest $request)
    {
        //Create a new client
        \Auth::user()->clients()->create($request->all());

        //Retrive the created client id
        $id = Client::getClientId();

        //Redirect to the created client's page
        return redirect()->route('clients.show', $id);
    }

    /**
     * Shows a single client application
     *
     * @param Client $client
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Delete an already existing client
     *
     * @param Client $client
     */
    public function destroy(Client $client)
    {
        //Delete the client
        $client->delete();

        //Redirect back to the clients dashboard
        redirect('/clients');
    }
}