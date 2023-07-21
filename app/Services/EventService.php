<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    public function getData($request)
    {
        $search = $request->search;
        $filter_event = $request->filter_event;

        $query = Event::query();

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });
        $query->when(request('filter_category', false), function ($q) use ($filter_event) {
            $q->where('category_id', $filter_event);
        });

        return $query->paginate(10);
    }

    public function createData($request)
    {
        // Upload the image first
        $fileService = new FileService();
        $file = $fileService->uploadFile($request->file('thumbnail'));

        // Create the product after that
        $inputs = $request->only(['name', 'description', 'date', 'price']);
        $inputs['thumbnail'] = $file;

        // created_by using auth user id
        $inputs['created_by'] = auth()->user()->id;

        $event = Event::create($inputs);

        return $event;
    }
}
