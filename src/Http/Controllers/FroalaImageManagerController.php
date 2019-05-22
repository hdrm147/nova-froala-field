<?php

namespace Froala\NovaFroalaField\Http\Controllers;

use Froala\NovaFroalaField\Froala;
use Laravel\Nova\Http\Requests\NovaRequest;

class FroalaImageManagerController
{
    public function index(NovaRequest $request)
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

        return call_user_func(
            $field->imagesCallback,
            $request
        );
    }

    public function destroy(NovaRequest $request)
    {
        $found = true;
        if (config('nova.froala-field.attachments_driver') !== Froala::DRIVER_NAME) {
            $request->replace(['attachmentUrl' => $request->input('src')] + $request->except('src'));
        }

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

        call_user_func(
            $field->detachCallback,
            $request
        );
    }
}
