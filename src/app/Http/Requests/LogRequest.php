<?php

namespace App\Http\Requests;

use App\Models\Device;
use App\Models\Station;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class LogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'device' => 'required|exists:App\Models\Device,id',
            'station' => 'required|exists:App\Models\Station,id',
            'date' => 'required|date'
        ];
    }

    public function validatedData(): array
    {
        $data = $this->validated();
        return [
            Device::findOrFail($data['device']),
            Station::findOrFail($data['station']),
            new Carbon($data['date'])
        ];
    }
}
