<?php

/**
 * EnumerableCode class file.
 *
 * @author Gladchenko Oleg
 */
class EnumerableCode extends CCodeModel
{

    public $enumerableName;
    public $enumerablePath = 'application.models.enumerables';
    public $baseClass = 'CEnumerable';
    public $enumerableItems;
    public $preparedItems = array();
    public $author;

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('enumerableName, enumerablePath, baseClass, author', 'filter', 'filter' => 'trim'),
            array('enumerableName, enumerablePath, baseClass, enumerableItems', 'required'),
            array('enumerableName, enumerablePath', 'match', 'pattern' => '/^(\w+[\w\.]*|\*?|\w+\.\*)$/', 'message' => '{attribute} should only contain word characters, dots, and an optional ending asterisk.'),
            array('baseClass', 'match', 'pattern' => '/^[a-zA-Z_][\w\\\\]*$/', 'message' => '{attribute} should only contain word characters and backslashes.'),
            array('enumerablePath', 'validateEnumerablePath', 'skipOnError' => true),
            array('baseClass', 'validateReservedWord', 'skipOnError' => true),
            array('baseClass', 'validateBaseClass', 'skipOnError' => true),
            array('enumerableName, enumerablePath, baseClass', 'sticky'),
            array('enumerableItems', 'validateEnumerableItems', 'skipOnError' => true),
            array('author', 'safe'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'enumerableName' => 'Enumerable Name',
            'enumerablePath' => 'Enumerable Path',
            'baseClass' => 'Base Class',
            'enumerableItems' => 'Enumerable Items',
        ));
    }

    public function requiredTemplates()
    {
        return array(
            'enumerable.php',
        );
    }

    public function prepare()
    {
        $className = ucfirst($this->enumerableName);

        $params = array(
            'className' => $className,
        );

        $this->files[] = new CCodeFile(
                Yii::getPathOfAlias($this->enumerablePath) . '/' . $className . '.php', $this->render($this->templatepath . '/enumerable.php', $params)
        );
    }

    public function validateEnumerablePath($attribute, $params)
    {
        if (Yii::getPathOfAlias($this->enumerablePath) === false)
            $this->addError('enumerablePath', 'Enumerable Path must be a valid path alias.');
    }

    public function validateBaseClass($attribute, $params)
    {
        $class = @Yii::import($this->baseClass, true);
        if (!is_string($class) || !$this->classExists($class))
            $this->addError('baseClass', "Class '{$this->baseClass}' does not exist or has syntax error.");
        elseif ($class !== 'CEnumerable' && !is_subclass_of($class, 'CEnumerable'))
            $this->addError('baseClass', "'{$this->enumerableName}' must extend from CEnumerable.");
    }

    public function validateEnumerableItems($attribute, $params)
    {
        $startPosition = true;
        $enteredKeys = true;
        $listItems = preg_split('~\r\n~', $this->{$attribute});

        foreach ($listItems as $key => $item) {
            if (strpos($item, ':') !== false) {
                list($value, $item) = explode(':', $item, 2);
                $key = strtoupper(preg_replace('~\_{2,}~', '_', preg_replace('~[^a-z0-9\_]+~i', '_', $item)));
                if (!$enteredKeys && !$startPosition) {
                    $this->addError($attribute, "Wrong input format1.");
                    break;
                } elseif (array_key_exists($key, $this->preparedItems)) {
                    $this->addError($attribute, "Wrong input format2.");
                    break;
                } elseif (!preg_match('~^[a-z].*~i', $key)) {
                    $this->addError($attribute, "Wrong input format3.");
                    break;
                }
                $startPosition = false;
                $enteredKeys = true;
                $this->preparedItems[$key] = array('value' => $value, 'text' => $item);
            } else {
                $key = strtoupper(preg_replace('~\_{2,}~', '_', preg_replace('~[^a-z0-9\_]+~i', '_', $item)));

                if ($enteredKeys && !$startPosition) {
                    $this->addError($attribute, "Wrong input format.");
                    break;
                } elseif (array_key_exists($key, $this->preparedItems)) {
                    $this->addError($attribute, "Wrong input format.");
                    break;
                } elseif (!preg_match('~^[a-z].*~i', $key)) {
                    $this->addError($attribute, "Wrong input format.");
                    break;
                }

                $startPosition = false;
                $enteredKeys = false;
                $this->preparedItems[$key] = array('value' => $item, 'text' => $item);
            }
        }
    }

}
