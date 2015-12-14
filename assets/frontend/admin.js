var urlMain = "http://dev.getadeal.ro/";
var url = "http://dev.getadeal.ro/admin/";
var urlRoot = "http://dev.getadeal.ro/application/";

function alert(msg) {
    $.msgbox("<p>" + msg + "</p>", {
        type: "info"
    });

}
function load_offer_editor(width, height) {
    if (!width)
        width = "90%";
    if (!height)
        height = "200";
    var op = {
        filebrowserUploadUrl: urlRoot + 'Controllers/uploader/upload.php?type=Files',
        width: width,
        height: height,
        toolbar:
                [
                    '/',
                    {
                        name: 'styles',
                        items: ['Source', 'FontSize', 'Font', 'TextColor', 'BGColor', 'Bold', 'Italic', 'Strike']
                    },
                    {
                        name: 'insert',
                        items: ['Image', 'Table', 'PageBreak', 'Link', 'Unlink']
                    },
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                    },
                ]

    }
    CKEDITOR.replace('terms', op);
    CKEDITOR.replace('benefits', op);

}
function load_partner_editor(width, height) {
    if (!width)
        width = "90%";
    if (!height)
        height = "200";
    var op = {
        filebrowserUploadUrl: urlRoot + 'Controllers/uploader/upload.php?type=Files',
        width: width,
        height: height,
        toolbar:
                [
                    '/',
                    {
                        name: 'styles',
                        items: ['Source', 'FontSize', 'Font', 'TextColor', 'Image', 'Table']
                    },
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                    },
                ]

    }
    CKEDITOR.replace('description', op);

}
function load_page_editor(width, height) {
    if (!width)
        width = "90%";
    if (!height)
        height = "200";
    var op = {
        filebrowserUploadUrl: urlRoot + 'Controllers/uploader/upload.php?type=Files',
        width: width,
        height: height,
        toolbar:
                [
                    '/',
                    {
                        name: 'styles',
                        items: ['Source', 'FontSize', 'Font', 'TextColor', 'BGColor', 'Bold', 'Italic', 'Strike']
                    },
                    {
                        name: 'insert',
                        items: ['Image', 'Table', 'PageBreak', 'Link', 'Unlink']
                    },
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                    },
                ]

    }
    CKEDITOR.replace('description', op);

}
function new_image() {
    var new_image_html = "<div class='image_group'><input type='file' name='image[]'/></div>";

    $('.add_images').append(new_image_html);
}
function delete_image(id_image) {
    $.ajax({
        type: "POST",
        url: url + "offer/delete_image",
        data: "id_image=" + id_image,
        dataType: 'json',
        beforeSend: function() {

        },
        success: function(result) {

            if (result.type == "success") {
                $('#pictures_table #' + id_image).fadeOut(400, function() {
                    $('#pictures_table #' + id_image).remove();
                })
            }
            else {
                alert("Eroare: " + result.msg);
            }
            console.log(result);
        }})
}


/* categories */
function add_category(parent_id) {
    $('.filters_table table tbody').empty();
    $("#add_category_trigger").fancybox().trigger('click');
    $('#add_category .parent_id').val(parent_id);

    $('#tabs1_add table tbody').empty();
    $('#tabs1_add table tbody').append('<tr><td colspan="3"  style="padding-top: 15px;"><h4> Administreaza Filtre <a style="color: #0057D5" href="javascript:add_filter(1)"> Adauga Filtru nou</a></h4></td></tr>');


    $('#tabs2_add table tbody').empty();
    $('#tabs2_add table tbody').append('<tr><td colspan="2" style="padding-top: 15px;"><h4> Administreaza Specificatii <a style="color: #0057D5" href="javascript:add_specification(1)"> Adauga Specificatie noua</a></h4></td></tr>');
}

function remove_category(cat_id) {
    $('#remove_category .id_category').val(cat_id);
    $('#remove_category form').submit();
}

function submit_add_category() {
    if ($('#add_category .name').val())
        $('#add_category_form').submit();
    else
    {
        alert("<br/>Eroare, trebuie sa introduci numele categoriei !");
    }
}

function update_category(cat_id) {
    //get category data with ajax. like a boss
    $.ajax({
        type: "POST",
        url: url + 'categories/get_ajax_category_data',
        data: "id_category=" + cat_id,
        dataType: 'json'
    }).done(function(cat) {
        $('#update_category input[name="aggregate"]').prop('checked', false);
        $('#update_category select[name="layout"]').val("list");
        $('#update_category .name').val(cat.name);
        $('#update_category .category_id').val(cat.id_category);
        $('#update_category .parent_id').val(cat.id_parent);
        $('#update_category .old_category_picture').val(cat.photo);
        $("#update_category_trigger").fancybox().trigger('click');

        $('#tabs2 .variant_list ol').empty();
        $('#tabs2 .variant_list ol').append(cat.specifications);


    });

}
function submit_update_category() {
    if ($('#update_category .name').val())
        $('#update_category form').submit();
    else
    {
        alert("<br/>Eroare, trebuie sa introduci numele categoriei !");
    }
}

function addProduct() {

    $('#select_categories input[type=checkbox]').each(function() {

        if (this.checked) {
            var input = $("<input>").attr("type", "hidden").attr("name", "categories[]").val($(this).val());
            $('#addProductForm .categoriesInput').append(input);
        }
    });

    var eroare = "";

    if ($('#name').val() == "") {
        eroare += "Eroare: introduceti numele produsului \n";
        $('#addProductForm .categoriesInput').empty();
    }

    if (eroare != "")
        alert(eroare);
    else
    {
        $('#addProductForm').submit()

    }
}

function add_filter(type) {
    var rand = 1 + Math.floor(Math.random() * 99999);

    $('#tabs1 table tbody').append('<tr id="filter_' + rand + '"><td><input type="hidden" name="specification_category_name[]" value=""/><input type="hidden" name="id_specification[]" value=""/><input type="hidden" name="type[]" value="filter"/><label>Titlu Filtru</label></td><td><input style="width: 250px" type="text" name="title[]"/><input type="text" placeholder="nr" style="width:23px;" name="nr_spec[]" value=""/></td><td> <label> Nume </label></td><td><input style = "width: 100px" type = "text" name = "name[]" / > </td><td > <div style = "height: 30px; line-height: 30px;" onclick = "delete_filter(' + rand + ')" id = "submitBtn" > Sterge </div></td></tr>');
    $('#tabs1_add table tbody').append('<tr id="filter_' + rand + '"><td><input type="hidden" name="specification_category_name[]" value=""/><input type="hidden" name="id_specification[]" value=""/><input type="hidden" name="type[]" value="filter"/><label>Titlu Filtru</label></td><td><input style="width: 250px" type="text" name="title[]"/><input type="text" placeholder="nr" style="width:23px;" name="nr_spec[]" value=""/></td><td> <label> Nume </label></td><td><input style = "width: 100px" type = "text" name = "name[]" / > </td><td > <div style = "height: 30px; line-height: 30px;" onclick = "delete_filter(' + rand + ')" id = "submitBtn" > Sterge </div></td></tr>');
}
function add_specification(id_spec_category) {
    var rand = 1 + Math.floor(Math.random() * 99999);
    var html = ' <tr id="id_spec_' + rand + '"><td width="40"><label>Nume</label></td><td class="input"><input type="hidden" name="specification_category_name[]"/><input type="hidden" name="id_specification[]" value=""/><input type="text" name="name[]" value="" placeholder="nume specificatie"/><input style="width:23px;" type="text" value="1" name="nr_spec[]" placeholder="nr"/> <div class="is_filter">Filtru ?</div> <div class="filter_box"><input type="hidden" name="is_filter[]" value="0"/><input name="setFilterCheckbox" type="checkbox" onchange="setFilterOn(' + rand + ')"/></div><div style="margin-left:10px;" class="is_filter">Ascuns ?</div> <div class="filter_box"><input type="hidden" name="is_hidden[]" value="0"/><input name="setHiddenCheckbox" type="checkbox" onchange="setHiddenOn(' + rand + ')"/></div></td><td><div onclick="removeSpecification(' + rand + ',0)" class="removeAttribute">Remove</div></td></tr>';
    $('#specCategory_' + id_spec_category + ' .attributesTable').append(html);
    updateSpecficationCategoryName(id_spec_category);
}
function setHiddenOn(id_spec) {
    var checkbox = $('#id_spec_' + id_spec + ' input[name="setHiddenCheckbox"]');
    var isHidden = $('#id_spec_' + id_spec + ' input[name="is_hidden[]"]');

    if (checkbox.prop('checked') == true) {
        isHidden.val(1);
    }
    else {

        isHidden.val(0);
    }
}
function addAttribute(html, id_variant) {
    $('#variant_' + id_variant + ' .attributesTable').append(html);
    $.fancybox.close();
}
function removeAttribute(id_attribute, id_variant) {
    console.log("da");
    $('#variant_' + id_variant + ' #id_attribute_' + id_attribute).remove();
}
function hideItem(id_item) {

    $.ajax({
        type: "POST",
        url: url + "product/toggleHide",
        data: "id_item=" + id_item,
        dataType: 'json',
        success: function(result) {

        }
    });
}
function triggerDeleteConfirm(selectorButton, link) {
    $("#dialog-confirm").dialog({
        resizable: false,
        height: 110,
        height: 180,
                modal: true,
        buttons: {
            "Sterge": function() {
                if (link) {
                    var href = $(selectorButton).attr('href');
                    window.location.href = href; //causes the browser to refresh and load the requested url
                }
                else {
                    $(selectorButton).trigger("click");
                }

            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }
    });
}

function showSelectedCategories() {
    $('#selectedCategories').empty();
    var categories = '';
    $('#select_categories input[type=checkbox]').each(function() {
        if (this.checked) {
            categories += ($(this).attr('category_name')) + ', ';
        }
    });
    if (categories)
        $('#selectedCategories').append('(' + categories.slice(0, -2) + ')');
}

function addVariant() {
    var rand = 1 + Math.floor(Math.random() * 99999);
    var firstVariant = $('.variant_list ol li:first-child table tbody tr');

    var pret_intreg = '<tr><td width="90"><label>Pret Intreg</label></td><td class=""><input type="hidden" value="" name="id_attribute_value[]"><input name="id_attribute[]" value="1" type="hidden"><input  onkeyup="numericRestrict(this)"  name="attribute_value[]" value="" type="text"> lei</td></tr>';
    var pret_redus = '<tr><td width="90"><label>Pret Redus</label></td><td class=""><input type="hidden" value="" name="id_attribute_value[]"><input name="id_attribute[]" value="2" type="hidden"><input onkeyup="numericRestrict(this)"  name="attribute_value[]" value="" type="text"> lei</td> </tr>';
    var descriere = '<tr><td width="90"><label>Descriere</label></td><td class=""><input type="hidden" value="" name="id_attribute_value[]"><input name="id_attribute[]" value="3" type="hidden"><textarea name="attribute_value[]"></textarea></td></tr>';
    var status = '<tr><td width="90"><label>Activa?</label></td><td class=""><input type="hidden" value="" name="id_attribute_value[]"><input name="id_attribute[]" value="6" type="hidden"><select name="attribute_value[]"><option value="1">Da</option><option value="0">Nu</option></select></td></tr>';

    var attributes = pret_intreg + pret_redus + descriere + status;

    var html = '<li id="variant_' + rand + '"><table width = "70%" border = "0" class = "attributesTable"><tr><td colspan = "3" class = "variantTdHeader" width = "80"><div class = "variantHeader" > Varianta </div><div class = "removeVariant" onclick = "removeVariant(' + rand + ',0)"> Sterge Varianta </div><input type="hidden" name="id_variant[]" value=""/></td></tr>' + attributes + '</table></li>';
    $('.variants_table .variant_list ol').prepend(html);

    /*
     for (var i = 1; i < firstVariant.length; i++) {
     
     var attributes = firstVariant[i];
     
     var newRand = 1 + Math.floor(Math.random() * 99999);
     var newId = "id_attribute_" + newRand;
     $('#variant_' + rand + ' .attributesTable').append("<tr id=" + newId + ">" + $(attributes).html() + "</tr>");
     
     $("#" + newId + ' input[name="id_attribute_value[]"]').val("");
     $("#" + newId + ' input[name="attribute_value[]"]').val("");
     }
     */
}
function removeVariant(id_variant, action) {

    if (action) {
        $.ajax({
            type: "POST",
            url: url + "offer/toggleVariant",
            data: "id_variant=" + id_variant + '&action=' + action,
            dataType: 'json',
            success: function(result) {
                if (action == "active") {
                    var msg1 = "Varianta Activa";
                    var msg2 = "Dezactiveaza Varianta";
                    var new_action = "inactive";
                }
                else {
                    var msg1 = "Varianta Inactiva";
                    var msg2 = "Activeaza Varianta";
                    var new_action = "active";
                }
                /*
                 $('#variant_' + id_variant + ' .variantHeader').html(msg1);
                 $('#variant_' + id_variant + ' .removeVariant').html(msg2);
                 $('#variant_' + id_variant + ' .removeVariant').attr('onclick', "removeVariant(" + id_variant + ",'" + new_action + "')");
                 */
                $('#variant_' + id_variant).fadeOut(300, function() {
                    $('#variant_' + id_variant).remove();
                });
            }
        });
    } else
        $('#variant_' + id_variant).fadeOut(300, function() {
            $('#variant_' + id_variant).remove();
        });
}

function numericRestrict(input) {
    var $th = $(input);
    if (!$.isNumeric($th.val())) {
        $th.val('');
    }
}