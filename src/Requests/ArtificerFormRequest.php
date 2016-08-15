<?php

namespace Mascame\Artificer\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Model\ModelManager;

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
     * @var bool
     */
    protected $isUpdating;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isUpdating) {
            $this->model = $this->modelManager->model->findOrFail($this->route('id'));
        }

        // Todo
        return true;
    }

    /**
     * Init the needed properties
     */
    protected function init() {
        $this->modelManager = Artificer::modelManager();
        $this->model = $this->modelManager->model;
        $this->isUpdating = (bool)($this->route('id'));
    }

    protected function getValidatorInstance()
    {
        $this->init();

        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->modelManager->getOption('rules', []);
    }

    /**
     * Apply the rules given for the model
     */
    protected function applyMassAssignmentRules() {
        $this->model->guard($this->modelManager->getGuarded());
        $this->model->fillable($this->modelManager->getFillable());
    }

    /**
     * Persist the data
     */
    public function persist() {
        $data = $this->getData();

        if ($this->isUpdating) {
            return $this->model->update($data);
        }

        return $this->model->create($data);
    }

    /**
     * @return array
     */
    protected function getData()
    {
        $this->applyMassAssignmentRules();

        return $this->all();



//        if ($this->modelManager->hasGuarded()) {
//            $filteredInput = [];
//
//            foreach ($data as $key => $value) {
//                if (in_array($key, $this->modelObject->columns)) {
//                    $filteredInput[$key] = $value;
//                }
//            }
//
//            return $this->except($this->modelManager->getGuarded(), $filteredInput);
//        }

        // Todo
//        $data = $this->handleFiles($data);
    }

    // Todo: Handle files

    /**
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
