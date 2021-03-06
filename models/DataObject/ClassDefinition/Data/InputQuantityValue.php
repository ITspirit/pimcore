<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @category   Pimcore
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Model\DataObject\ClassDefinition\Data;

use Pimcore\Model;
use Pimcore\Model\DataObject\Data\InputQuantityValue as InputQuantityValueDataObject;

/**
 * TODO: Refactor - this class is very similar to the parent one so probably we can try to refactor parent and have better results here also
 *
 * Class InputQuantityValue
 *
 * @package Pimcore\Model\DataObject\ClassDefinition\Data
 */
class InputQuantityValue extends QuantityValue
{
    use Extension\ColumnType;
    use Extension\QueryColumnType;

    /**
     * @var string
     */
    public $fieldtype = 'inputQuantityValue';

    /**
     * Type for the column to query
     *
     * @var array
     */
    public $queryColumnType = [
        'value' => 'varchar(255)',
        'unit' => 'varchar(50)',
    ];

    /**
     * Type for the column
     *
     * @var array
     */
    public $columnType = [
        'value' => 'varchar(255)',
        'unit' => 'varchar(50)',
    ];

    /**
     * @param array $data
     * @param Model\DataObject\Concrete|null $object
     * @param array $params
     *
     * @return InputQuantityValueDataObject|null
     */
    public function getDataFromResource($data, $object = null, $params = [])
    {
        if ($data[$this->getName() . '__value'] || $data[$this->getName() . '__unit']) {
            $dataObject = $this->getNewDataObject($data[$this->getName() . '__value'], $data[$this->getName() . '__unit']);

            if (isset($params['owner'])) {
                $dataObject->_setOwner($params['owner']);
                $dataObject->_setOwnerFieldname($params['fieldname']);
                $dataObject->_setOwnerLanguage($params['language'] ?? null);
            }

            return $dataObject;
        }

        return null;
    }

    /**
     * @param array $data
     * @param Model\DataObject\Concrete|null $object
     * @param array $params
     *
     * @return InputQuantityValueDataObject|null
     */
    public function getDataFromEditmode($data, $object = null, $params = [])
    {
        if ($data['value'] || $data['unit']) {
            if ($data['unit']) {
                if ($data['unit'] == -1 || $data['unit'] == null || empty($data['unit'])) {
                    return $this->getNewDataObject($data['value'], null);
                }

                return $this->getNewDataObject($data['value'], $data['unit']);
            }
        }

        return null;
    }

    /**
     * @param mixed $data
     * @param bool $omitMandatoryCheck
     *
     * @throws Model\Element\ValidationException
     */
    public function checkValidity($data, $omitMandatoryCheck = false)
    {
        if ($omitMandatoryCheck) {
            return;
        }

        if ($this->getMandatory() &&
            ($data === null || $data->getValue() === null || $data->getUnitId() === null)) {
            throw new Model\Element\ValidationException('Empty mandatory field [ ' . $this->getName() . ' ]');
        }
    }

    /**
     * @param string $value
     * @param int $unitId
     *
     * @return InputQuantityValueDataObject
     */
    protected function getNewDataObject($value = null, $unitId = null)
    {
        return new InputQuantityValueDataObject($value, $unitId);
    }

    public function getParameterTypeDeclaration(): ?string
    {
        return '?\\' . Model\DataObject\Data\InputQuantityValue::class;
    }

    public function getReturnTypeDeclaration(): ?string
    {
        return '?\\' . Model\DataObject\Data\InputQuantityValue::class;
    }

    public function getPhpdocInputType(): ?string
    {
        return '\\' . Model\DataObject\Data\InputQuantityValue::class . '|null';
    }

    public function getPhpdocReturnType(): ?string
    {
        return '\\' . Model\DataObject\Data\InputQuantityValue::class . '|null';
    }

    /**
     * { @inheritdoc }
     */
    public function normalize($value, $params = [])
    {
        if ($value instanceof Model\DataObject\Data\InputQuantityValue) {
            return [
                'value' => $value->getValue(),
                'unitId' => $value->getUnitId(),
            ];
        }
    }

    /**
     * { @inheritdoc }
     */
    public function denormalize($value, $params = [])
    {
        if (is_array($value)) {
            return new Model\DataObject\Data\InputQuantityValue($value['value'], $value['unitId']);
        }

        return null;
    }
}
