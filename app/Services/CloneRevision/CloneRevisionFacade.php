<?php

namespace App\Services\CloneRevision;

use App\Models\Message;
use App\Models\Question;
use App\Models\Revision;
use Illuminate\Database\Eloquent\Model;

class CloneRevisionFacade
{
    public function cloneRevision(Revision $oldRevision)
    {
        $newRevision = new Revision();
        $newRevision->channel_id = $oldRevision->channel_id;
        $newRevision->save();

        foreach ($oldRevision->questions as $oldQuestion) {
            $newQuestion = new Question();
            $newQuestion->setRawAttributes($this->getModelAttributes($oldQuestion));
            $newQuestion->revision_id = $newRevision->id;
            $newQuestion->save();

            foreach($oldQuestion->messages as $oldMessage) {
                $newMessage = new Message();
                $newMessage->setRawAttributes($this->getModelAttributes($oldMessage));
                $newMessage->question_id = $newQuestion->id;
                $newMessage->save();
            }
        }
    }

    private function getModelAttributes(Model $model)
    {
        $attributes = $model->getAttributes();
        unset($attributes['id']);
        return $attributes;
    }
}
