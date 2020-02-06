<?php

namespace app\components\DynamicInput;

use yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class DynamicInput extends Widget{
    
        public $name;
        public $models;
	public $attributes;
        
        public static $id = 0;
        private $id_form;
        private $id_table;
        private $id_button;

        public function init(){
            DynamicInput::$id = DynamicInput::$id+1;
            $this->id_form = "dynamic-input-form-".  DynamicInput::$id;
            $this->id_table = "dynamic-input-table-".  DynamicInput::$id;
            $this->id_button = "dynamic-input-button-".  DynamicInput::$id;
            if($this->name === NULL) $this->name = "ADD";
            $view = $this->getView();
            $view->registerJsFile(Url::to(["js/DynamicInputGenerator.js"]), [], 'dynamic-input-generator');
            $view->registerJs( 
                "new DynamicInputGenerator({
                    table_elem : $('#".$this->id_table."'), 
                    button_add : $('#".$this->id_button."')
                    })", 
                \yii\web\View::POS_END
            );
	}

	public function run(){
            ?>
            <style>
                <?= "#".$this->id_table ?> td{
/*                    padding-left: 10px;
                    padding-right: 10px;*/
                    padding-bottom: 5px;
                }
            </style>

            <button type='button' id="<?= $this->id_button ?>" class="btn btn-primary"><?= $this->name ?></button>
            <div style="margin-top: 10px;"></div>
            <table id="<?= $this->id_table ?>">
                <tr>
                    <?php
                    foreach($this->attributes as $attr){
                        echo "<td style='text-align: center'>";
                        echo $attr['title'];
                        echo "</td>";
                    }
                    echo "<td></td>";
                    ?>
                </tr>
                <?php 
                foreach ($this->models as $model){
                    echo "<tr>";
                    foreach($this->attributes as $attr){
                        echo "<td>";
                        echo "<div ".$this->htmlOptionsToString($attr['htmlOptions']).">";
                            $this->insertInput($model, $attr);
                        echo "</div>";
                        echo "</td>";
                    }
                    echo "<td class='delete'>X</td>";
                    echo "</tr>";
                }
                ?>
               
                <tr class="hidden row-template">
                    <?php
                    $model = new \app\models\InvoicePay();
                    foreach($this->attributes as $attr){
                        echo "<td>";
                        echo "<div ".$this->htmlOptionsToString($attr['htmlOptions']).">";
                            $this->insertInput($model, $attr, true);
                        echo "</div>";
                        echo "</td>";
                    }
                    echo "<td class='delete'>X</td>";
                    ?>
                </tr>
            </table>
            <br><br>
            <?php
	}
        
        private function insertInput($model, $attribute, $template = false){
            $name = $attribute['name'];
            $type = $attribute['type'];
            
            $value = $attribute['attribute'];
            if ($template){
                $value = NULL;
            } else if (is_callable($value)){
                $value= $value($model);
            } else if (is_array($model)){
                $value = $model[$value];
            } else{
                $value = $model->$value;
            }
            
            if (!$template && $value === NULL){
                $value = "empty";
            }
            
            $values = $attribute['values'];
            if ($template && is_callable($values)){
                $values = [];
                $values["empty"] = "";
            } else if (is_callable($values)){
                $values = $values($model);
            } else if (!is_array($values)){
                $values = [];
            }
            
            if (!$template && $values === NULL){
                $values = [];
                $values["empty"] = "";
            }
            
            $this->input($type, $name, $value, $values); 
        }
        
        private function input($type, $name, $value, $values){
            if ($type === "dropdown")
                echo Html::dropDownList($name, $value, $values, ['class' => 'form-control']);
            else 
                echo Html::input ($type, $name, $value, ['class' => 'form-control']);
        }
        
        private function htmlOptionsToString($htmlOptions){
            $result = "";
            foreach($htmlOptions as $key => $option){
                $result .= $key."='".htmlentities($option)."' ";
            }
            return $result;
        }
}
?>