<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use App\Repositories\Contact\ContactRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class ContactRepositoryEloquent extends BaseRepository implements ContactRepository
{
    /**
     * @inheritDoc
     */
    public function model()
    {
        return Contact::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        //
    }
}
