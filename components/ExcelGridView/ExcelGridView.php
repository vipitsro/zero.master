<?php

namespace app\components\ExcelGridView;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class ExcelGridView extends Widget {

    public $saveUrl;
    public $models;
    public $items;
    public $cols;

    public function init() {
        parent::init();
        ExcelGridViewAsset::register($this->getView());    
        $c = [];
        if ($this->cols === NULL){
            $this->cols = array_fill(0, count($this->items), "2");
        } else {
            foreach($this->cols as $key => $value){
                if (!isset($c[$key])) {
                    $c[$key] = "";
                }
                $c[$key] .= " my-col-".$value; 
            }
            $this->cols = $c;
        }
    }

    public function run() {
        $form = \yii\widgets\ActiveForm::begin();

        echo "<div class='ExcelGridView'>";
        echo "<button type='button' id='newExcelGridViewRow'>NEW</button>";
        echo "<table>";
            // HEAD
            echo "<thead>";
                echo "<tr class='my-row'>";
                foreach ($this->items as $key => $value) {
                    echo "<td class='".$this->cols[$key]."'>";
                    if (is_array($value)) {
                        if (isset($value['hidden']) && $value['hidden'] === true){
                            echo "";
                        } else {
                            echo (isset($value["name"])) ? $value["name"] : $this->models[0]->getAttributeLabel($value["attribute"]);
                        }
                    } else {
                        echo $this->models[0]->getAttributeLabel($value);
                    }
                    echo "</td>";
                }
                echo "</tr>";
            echo "</thead>";

            // BODY
            echo "<tbody>";
                // TEMPLATE
                echo "<tr class='my-row-template' hidden>";
                foreach ($this->items as $key2 => $value) {
                    echo "<td class='".$this->cols[$key2]."'>";
                    if (is_array($value)) {
                        if (isset($value["value"]) && (is_array($value["value"]) || is_callable($value["value"]))){
                            $dropdown_values = [];
                            if (is_callable($value["value"])){
                                $dropdown_values = $value["value"]($this->models[0]);
                            } else {
                                $dropdown_values = $value["value"];
                            }
                            echo Html::dropDownList($this->models[0]->formName() . "[" . $value["attribute"] . "]", NULL, $dropdown_values,[
                                "hidden" => isset($value['hidden']) ? $value["hidden"] : false,
                                "style" => isset($value['style']) ? $value["style"] : false,
                            ]);
                        } else {
                            echo Html::input("text", $this->models[0]->formName() . "[" . $value["attribute"] . "]", "",[
                                "hidden" => isset($value['hidden']) ? $value["hidden"] : false,
                                "style" => isset($value['style']) ? $value["style"] : false,
                            ]);
                        }
                    } else {
                        echo Html::input("text", $this->models[0]->formName() . $value, "");
                    }
                    echo "</td>";
                }
                echo "</tr>";
            
                // DATA
                foreach ($this->models as $key1 => $model) {
                    echo "<tr class='my-row'>";
                    foreach ($this->items as $key2 => $value) {
                        echo "<td class='".$this->cols[$key2]."'>";
                        if (is_array($value)) {
                            if (isset($value["value"]) && (is_array($value["value"]) || is_callable($value["value"]))){
                                $dropdown_values = [];
                                if (is_callable($value["value"])){
                                    $dropdown_values = $value["value"]($model);
                                } else {
                                    $dropdown_values = $value["value"];
                                }
                                echo Html::dropDownList($model->formName() . "[" . $value["attribute"] . "]", $model->$value["attribute"], $dropdown_values,[
                                    "hidden" => isset($value['hidden']) ? $value["hidden"] : false,
                                    "style" => isset($value['style']) ? $value["style"] : false,
                                ]);
                            } else {
                                echo Html::input("text", $model->formName() . "[" . $value["attribute"] . "]", $model->$value['attribute'],[
                                    "hidden" => isset($value['hidden']) ? $value["hidden"] : false,
                                    "style" => isset($value['style']) ? $value["style"] : false,
                                ]);
                            }
                        } else {
                            echo Html::input("text", $model->formName() . $value, $model->$value);
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
            echo "</tbody>";
        echo "</table>";
        echo "</div>";

        \yii\widgets\ActiveForm::end();
        
        echo 
        "<style>
            .control-label{
                margin: 0;
                display: block;
            }
            .help-block{
                margin: 0;
            }
            .form-group{
                margin: 0;
            }
        </style>";
    }
    
    private function getModelAttributeValue($model, $attribute){
        
    }
}

?>