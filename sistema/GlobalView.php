<?php

namespace sistema;

class GlobalView {

    public static function createButton($vetCSS, $label, $type) {
        if ($vetCSS) {
            $classesCSS = '';
            foreach ($vetCSS as $class) {
                $classesCSS .= $class . ' ';
            }
        }

        return '
            <input type="'.$type.'" value="'.$label.'" class="'.$classesCSS.'">
        ';
    }

    public static function createErrorAlert($msg) {
        if (empty($msg)) {
            return '';
        }

        return '
            <div class="alert error">
                <b>Ops!</b> ' . $msg . '
            </div>
        ';

    }

    public static function createSuccessAlert($msg) {
        if (empty($msg)) {
            return '';
        }

        return '
            <div class="alert success">
                ' . $msg . '
            </div>
        ';

    }

    public static function createPageTitle($label) {
        if (empty($label)) {
            return '';
        }

        return '
            <div class="container-page-title"> ' . $label . ' </div>
        ';
    }

    public static function createInput($typeField, $label, $idField, $required = false, $valueDefault = '') {
        return '
            <div class="container-field flex-center">
                <label for="'.$idField.'">'.$label.'</label>
                <input type="'.$typeField.'" placeholder="'.$label.'" id="'.$idField.'" name="'.$idField.'" value="'.$valueDefault.'" '. ($required ? 'required' : '') .'>
            </div>
        ';
    }

    public static function createSelect($label, $idField, $vetOptions, $required = false, $valueDefault = '', $fieldDB, $firstEmptyOption) {
        $options = '';
        foreach ($vetOptions as $option) {
            $options .= '
                <option value="' . $option[$fieldDB] .'" '. ($option[$fieldDB] == $valueDefault ? 'selected' : '') .'>
                    ' . $option['sNome'] .'
                </option>';
        }

        return '
            <div class="container-field flex-center">
                <label for="'.$idField.'">'.$label.'</label>
                <select id="'.$idField.'" name="'.$idField.'" '. ($required ? 'required' : '') .'>
                    ' . ($firstEmptyOption ? '<option value="">Selecione...</option>' : '') . '
                    ' . $options . '
                </select>
            </div>
        ';
    }

    public static function createTextArea($label, $idField, $required = false, $valueDefault = '') {
        return '
            <div class="container-field flex-center">
                <label for="'.$idField.'">'.$label.'</label>
                <textarea rows="5" id="'.$idField.'" name="'.$idField.'" '. ($required ? 'required' : '') .'>'.$valueDefault.'</textarea>
            </div>
        ';
    }

    public static function createContainerSearch($pageAction, $labelField, $idField) {
        return '
            <form action="'.$pageAction.'" method="post">
                <div class="container-search">
                    ' . GlobalView::createInput('text', $labelField, $idField, false, '') . '
                    ' . GlobalView::createButton(['btn', 'btn-primary'], 'Buscar', 'submit') .'
                </div>
            </form>
            ';
    }

}