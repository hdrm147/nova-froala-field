<?php

namespace Froala\NovaFroalaField\Handlers;

use Illuminate\Http\Request;
use Froala\NovaFroalaField\Models\Attachment;

class DeleteAttachments
{
    /**
     * The field instance.
     *
     * @var \Froala\NovaFroalaField\Froala
     */
    public $field;

    /**
     * Create a new class instance.
     *
     * @param  \Froala\NovaFroalaField\Froala  $field
     * @return void
     */
    public function __construct($field)
    {
        $this->field = $field;
    }

    /**
     * Delete the attachments associated with the field.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $model
     * @return array
     */
    public function __invoke(Request $request, $model)
    {
        $path = parse_url($request->attachmentUrl, PHP_URL_PATH);
        $path = explode("/",$path);
        $path = storage_path(end($path));
        \File::delete($path);
        \Log::info($path);
        Attachment::where('attachable_type', get_class($model))
                ->where('attachable_id', $model->getKey())
                ->get()
                ->each
                ->purge();

        return [$this->field->attribute => ''];
    }
}
