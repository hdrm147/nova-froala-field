<?php

namespace Froala\NovaFroalaField\Handlers;

use Froala\NovaFroalaField\Models\PendingAttachment;
use Illuminate\Http\Request;
use Froala\NovaFroalaField\Models\Attachment;

class DetachAttachment
{
    /**
     * Delete an attachment from the field.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        Attachment::where('url', $request->attachmentUrl)
                        ->get()
                        ->each
                        ->purge();

        $path = explode("/",$request->attachmentUrl);
        $path = end($path);
        PendingAttachment::where('attachment','=',$path)->get()->each->purge();
    }
}
