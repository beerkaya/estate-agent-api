<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactStoreRequest;
use App\Http\Requests\Contact\ContactUpdateRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Repositories\Contact\ContactRepository;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Create a new ContactController instance.
     *
     * @return void
     */
    public function __construct(public ContactRepository $repository)
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ContactResource::collection($this->repository->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Contact\ContactStoreRequest $request
     * @return \App\Http\Resources\ContactResource
     */
    public function store(ContactStoreRequest $request)
    {
        return ContactResource::make($this->repository->create($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return new ContactResource($contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContactUpdateRequest $request, Contact $contact)
    {
        return ContactResource::make($this->repository->update($request->validated(), $contact->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $this->repository->delete($contact->id);
    }
}
