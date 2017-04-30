<?php

namespace Mascame\Artificer\Requests;

use Mascame\Artificer\Artificer;
use Illuminate\Database\Eloquent\Model;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Model\ModelSettings;
use Illuminate\Foundation\Http\FormRequest;

class ArtificerFormRequest extends FormRequest
{
    /**
     * @var ModelManager
     */
    protected $modelManager;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var ModelSettings
     */
    protected $modelSettings;

    /**
     * Init the needed properties.
     */
    protected function init()
    {
        $this->modelSettings = Artificer::modelManager()->current()->settings();
        $this->currentModel = Artificer::modelManager()->current()->model();
    }

    /**
     * Extends this method to initialize some vars.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $this->init();

        return parent::getValidatorInstance();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isUpdating()) {
            $this->currentModel = $this->currentModel->findOrFail($this->route('id'));
        }

        // Todo
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        flash()->error('Validation failed.');

        parent::failedValidation($validator);
    }

    /**
     * @return bool
     */
    public function isUpdating()
    {
        return (bool) ($this->route('id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->modelSettings->getOption('rules', []);
    }

    /**
     * Persist the data.
     */
    public function persist()
    {
        $data = Artificer::modelManager()->current()->serialize();

        if ($this->isUpdating()) {
            $result = $this->currentModel->update($data);
        } else {
            $result = $this->currentModel->create($data);
        }

        return $result;
    }

    // Todo: Handle files

    /*
     * @param $data
     * @return array
     */
//    protected function handleFiles($data)
//    {
//        $newData = [];
//        $fields = $this->getFields($data);
//
//        if (!is_null($fields)) {
//            foreach ($fields as $field) {
//                if ($this->isFileInput($field->type)) {
//                    if (Input::hasFile($field->name)) {
//                        $newData[$field->name] = $this->uploadFile($field->name);
//                    } else {
//                        unset($data[$field->name]);
//                    }
//                }
//            }
//        }
//
//        return array_merge($data, $newData);
//    }
//
//    /**
//     * @param $type
//     * @return bool
//     */
//    protected function isFileInput($type)
//    {
//        return ($type == 'file' || $type == 'image');
//    }
//
//    /**
//     * This is used for simple upload (no plugins)
//     *
//     * @param $fieldName
//     * @param null $path
//     * @return string
//     */
//    protected function uploadFile($fieldName, $path = null)
//    {
//        if (!$path) {
//            $path = public_path() . '/uploads/';
//        }
//
//        $file = Input::file($fieldName);
//
//        if (!file_exists($path)) {
//            File::makeDirectory($path);
//        }
//
//        $name = uniqid() . '-' . Str::slug($file->getFilename()) . '.' . $file->guessExtension();
//
//        $file->move($path, $name);
//
//        return $name;
//    }
}
