<?php

namespace Froala\NovaFroalaField\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class FroalaUploadController extends Controller
{
    /**
     * Store an attachment for a Trix field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NovaRequest $request)
    {
        $found = true;
        $field = $request->newResource()
            ->availableFields($request)
            ->findFieldByAttribute($request->field, function () use(&$found) {
                $found = false;
            });

        if (!$found) {
            $name = str_replace("translations_","",$request->field);
            $name = str_replace("_en","",$name);
            $name = str_replace("_ar","",$name);
            $field = $request->newResource()
                ->availableFields($request)
                ->findFieldByAttribute($name, function () {
                    abort(404);
                });
        }
        return response()->json(['link' => call_user_func(
            $field->attachCallback,
            $request
        )]);


    }


    /**
     * Delete a single, persisted attachment for a Trix field by URL.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyAttachment(NovaRequest $request)
    {
        $found = true;
        $field = $request->newResource()
            ->availableFields($request)
            ->findFieldByAttribute($request->field, function () use(&$found) {
                $found = false;
            });

        if (!$found) {
            $name = str_replace("translations_","",$request->field);
            $name = str_replace("_en","",$name);
            $name = str_replace("_ar","",$name);
            $field = $request->newResource()
                ->availableFields($request)
                ->findFieldByAttribute($name, function () {
                    abort(404);
                });
        }

        return call_user_func($field->detachCallback, $request);
    }

    /**
     * Purge all pending attachments for a Trix field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyPending(NovaRequest $request)
    {
        $found = true;
        $field = $request->newResource()
            ->availableFields($request)
            ->findFieldByAttribute($request->field, function () use(&$found) {
                $found = false;
            });

        if (!$found) {
            $name = str_replace("translations_","",$request->field);
            $name = str_replace("_en","",$name);
            $name = str_replace("_ar","",$name);
            $field = $request->newResource()
                ->availableFields($request)
                ->findFieldByAttribute($name, function () {
                    abort(404);
                });
        }

        return call_user_func($field->discardCallback, $request);
    }
}
