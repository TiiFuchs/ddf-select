<?php

namespace App\Http\Requests;

use App\Models\EpisodeDuration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EpisodeFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'duration' => [Rule::enum(EpisodeDuration::class)],
            'with.*' => [Rule::in('album', 'tracks')],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'with' => $this->with ? explode(',', $this->with) : null,
        ]);
    }
}
