$(document).on('click', "#newExcelGridViewRow", function(){
    console.log('a');
    var clone = $(this).closest('.ExcelGridView').find('.my-row-template').clone();
    clone.removeAttr("hidden");
    clone.removeClass("my-row-template");
    clone.addClass("my-row");
    $(this).closest('.ExcelGridView').find('.my-row-template').after(clone);
});

$(document).on('input', '.my-row', function(){
    $(this).addClass("my-row-edited");
});


