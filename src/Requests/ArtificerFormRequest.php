<?php

namespace Mascame\Artificer\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Fields\Field;
use Mascame\Artificer\Hooks\Hook;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Model\ModelSettings;

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
        $this->modelSettings = Artificer::modelManager()->current();
        $this->currentModel = $this->modelSettings->model;
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
     * @return bool
     */
    protected function isUpdating()
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
     * Apply the rules given for the model.
     */
    protected function applyMassAssignmentRules()
    {
        $this->currentModel->guard($this->modelSettings->getGuarded());
        $this->currentModel->fillable($this->modelSettings->getFillable());
    }

    /**
     * Persist the data.
     */
    public function persist()
    {
        $data = $this->getData();

        $data = $this->applyBeforeHook(
            $this->modelSettings->withValues($data)->toForm()
        );

        $data = $this->serializeForm($data);

        if ($this->isUpdating()) {
            $result = $this->currentModel->update($data);
        } else {
            $result = $this->currentModel->create($data);
        }

        return $this->applyAfterHook($result);
    }

    protected function serializeForm($fields)
    {
        $serialized = [];

        /**
         * @var Field
         */
        foreach ($fields as $name => $field) {
            if ($this->isUpdating() && $name == 'id') {
                continue;
            }

            $serialized[$name] = $field->getValue();
        }

        return $serialized;
    }

    protected function applyBeforeHook($data)
    {
        $hook = $this->isUpdating() ? Hook::UPDATING : Hook::CREATING;

        /*
         * @var $data [Array] of Mascame\Artificer\Fields\Field
         */
        return Artificer::hook()->fire($hook, $data);
    }

    protected function applyAfterHook($data)
    {
        $hook = $this->isUpdating() ? Hook::UPDATED : Hook::CREATED;

        /*
         * @var $data [Array] of Mascame\Artificer\Fields\Field
         */
        return Artificer::hook()->fire($hook, $data);
    }

    /**
     * @return array
     */
    protected function getData()
    {
        $this->applyMassAssignmentRules();

        // Todo
        // $data = $this->handleFiles($data);
        return $this->all();
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
