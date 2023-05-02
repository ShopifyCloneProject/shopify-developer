<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Exception;

class CollectionApiController extends ApiController
{
    public function store(request $request)
    {
        // $collection = Collection::create($request->all());

        // if ($request->input('url', false)) {
        //     $collection->addMedia(storage_path('tmp/uploads/' . basename($request->input('url'))))->usingMediaId(0)->toMediaCollection('url');
        // }

        // if ($media = $request->input('ck-media', false)) {
        //     Media::whereIn('id', $media)->update(['model_id' => 0]);
        // }

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COLLECTION_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.COLLECTION_ADDED_SUCCESSFULLY.msg'),
        );
    }
}
