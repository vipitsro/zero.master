var DynamicInputGenerator = (function(){
    // CONSTRUCTOR
    var constructor=function(params) {
        var $_form_elem = params['form_elem'];
        var $_table = params['table_elem'];
        var rowsCount = $_table.find("tr").length;
        var $_addCode = params['button_add'];
        var self=this;
        /* PROTECTED METHODS */
            
        this.addNcbType = function() {
            
            var $rowTemplate1 = $_table.find("tr.row-template");
            var $newRow = $rowTemplate1.clone();
            $newRow.removeClass('hidden row-template');
            $newRow.find("input[type='hidden']").val(rowsCount); // ??
            $newRow.find(":input").each(function(){
                if(typeof jQuery(this).attr("name") != 'undefined'){
                    var newName = jQuery(this).attr("name").replace("XCounter", rowsCount);
                    jQuery(this).attr("name", newName);
                    if (jQuery(this).parent().hasClass('inv-date'))
                        jQuery(this).datepicker();
                }
            });
            $rowTemplate1.before($newRow);
            
            var $rowTemplate2 = $_table.find("tr.row-template2");
            var $newRow = $rowTemplate2.clone();
            $newRow.removeClass('row-template2');
            $newRow.find("input[type='hidden']").val(rowsCount); // ??
            $newRow.find(":input").each(function(){
                if(typeof jQuery(this).attr("name") != 'undefined'){
                    var newName = jQuery(this).attr("name").replace("XCounter", rowsCount);
                    jQuery(this).attr("name", newName);
                }
            });
            $rowTemplate1.before($newRow);
            
            rowsCount++;
            
            return false;
        };
        
        $_addCode.click(function(){
            self.addNcbType();
            return false;
        });
        
        $_table.on('click','.delete',function(){
            $(this).parent().remove();
        });
    }
        
    return constructor;
})();


