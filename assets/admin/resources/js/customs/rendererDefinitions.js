Ext.ns('APP.renderer');

Ext.apply(APP.renderer, {
    // transforms 0/1 boolean values to NU/DA
    yesNo: function(data, metadata, record, rowIndex, columnIndex, store) {
        return (data == 1) ? '<font color="green">DA</font>' : '<font color="red">NU</font>';
    },
    nowrap: function(val) {
        return '<div style="white-space:normal !important;">' + val + '</div>';
    },
    //transforma un string in format d.m.Y
    dateBusiness: function(value, metadata, record, rowIndex, columnIndex, store) {

        if (Ext.isDate(value)) {
            var oDt = value.dateFormat('d.m.Y H:i');
            return oDt;
        }

        if ((value) && (value != "")) {
            var hour = value.substr(11, 9);
            hour = (hour !== "00:00:00") ? hour : "";
            return value.substr(8, 2) + "." + value.substr(5, 2) + "." + value.substr(0, 4) + " " + hour;
        }
        return null;
    },
    // transforma coduri in texte
    permisie: function(data, metadata, record, rowIndex, columnIndex, store) {
        if (data) {
            switch (data) {
                case  '1' :
                    return "<font color=green>Allow</font>";
                case  '2' :
                    return "<font color=red>Deny</font>";
                default :
                    return "<font color=gray>Ignore</font>";
            }
        }
        return "<font color=gray>Ignore</font>";
    },
    depunere_ac: function(data, metadata, record, rowIndex, columnIndex, store) {
        var oAviz = ['CUAVZ21', "CUAVZ22", "CUAVZ23", "CUAVZ24", "CUAVZ25"];
        if (inArray(record.get('aviz_id'), oAviz)) {
            metadata.style = "background-color: #999 !important;";
        }
        return data;
    },
    tooltipRenderer: function(data, metadata, record, rowIndex, columnIndex, store) {
        metadata.attr = 'ext:qtip="' + data + '" ';
        return data;
    },
    status_evaluare: function(data, metadata, record, rowIndex, columnIndex, store) {

        var codEv = record.data.status_cod;

        switch (codEv) {

            case 'ev_simulare':
                metadata.css = 'icon-chart-curve no-repeat-bk';
                break;
            case 'ev_lucru':
                metadata.css = 'icon-fugue-node-design no-repeat-bk';
                break;
            case 'ev_finalizat':
                metadata.css = 'icon-award-star-silver-1 no-repeat-bk';
                break;
            case 'ev_sters':
                metadata.css = 'icon-fugue-minus-circle no-repeat-bk';
                break;
            case 'ev_inactiv':
                metadata.css = 'icon-fugue-shield no-repeat-bk';
                break;
            case 'ev_anulat_finalizat':
                metadata.css = 'icon-farm-award-star-delete no-repeat-bk';
                break;
            case 'ev_anulat_simulare':
                metadata.css = 'icon-chart-curve-delete no-repeat-bk';
                break;
            case 'ev_validat_anulat_eronat':
                metadata.css = 'icon-fugueov-keyboard-minus no-repeat-bk';
                break;
            case 'ev_validat_anulat_motiv':
                metadata.css = 'icon-fugueov-keyboard-plus no-repeat-bk';
                break;
            case 'ev_validat_expirat':
                metadata.css = 'icon-fugueov-keyboard-arrow no-repeat-bk';
                break;
            case 'ev_validat_reevaluat':
                metadata.css = 'icon-fugueov-keyboard-pencil no-repeat-bk';
                break;
            case 'ev_validat_ol':
                metadata.css = 'icon-fugue-credit-card-green no-repeat-bk';
                break;
            case 'ev_expirat_fara_ol':
                metadata.css = 'icon-fugue-ui-toolbar-bookmark no-repeat-bk';
                break;
            case 'ev_contract_reincredintat':
                metadata.css = 'icon-fugueov-folder-arrow no-repeat-bk';
                break;
            case 'ev_contract_reziliat':
                metadata.css = 'icon-fugueov-folder-minus no-repeat-bk';
                break;
            case 'ev_contract_suspendat':
                metadata.css = 'icon-fugueov-folder-pencil no-repeat-bk';
                break;
        }
        metadata.attr = 'ext:qtip="' + data + '" ';
        return  String.format('<img class="padding-img" src="{0}"/>', Ext.BLANK_IMAGE_URL) + data;
    },
    status_spf: function(data, metadata, record, rowIndex, columnIndex, store) {

        if (data == null) {
            return "";
        }

        var codEv;
        //statusul SPF poate veni si pe campul status_cod_spf; vezi modulul  "date centralizate"
        if ((record.data.status_cod_spf) && (record.data.status_cod_spf != "")) {
            codEv = record.data.status_cod_spf;
        }
        else {
            codEv = record.data.status_cod;
        }


        switch (codEv) {
            case 'spf_nou':
                metadata.css = 'icon-fugue-slide no-repeat-bk';
                break;
            case 'spf_anulat':
                metadata.css = 'icon-chart-curve-delete no-repeat-bk';
                break;
            case 'spf_spre_validare':
                metadata.css = 'icon-fugue-paint-brush no-repeat-bk';
                break;
            case 'spf_spre_validare_sef':
                metadata.css = 'icon-fugueov-paint-brush-plus no-repeat-bk';
                break;
            case 'spf_inactiv':
                metadata.css = 'icon-fugue-shield no-repeat-bk';
                break;
            case 'spf_sters':
                metadata.css = 'icon-fugue-minus-circle no-repeat-bk';
                break;
            case 'spv_respins':
                metadata.css = 'icon-fugue-minus-shield no-repeat-bk';
                break;
            case 'spf_aprobat':
                metadata.css = 'icon-fugue-tick-shield no-repeat-bk';
                break;
            case 'spf_validat_anulat':
                metadata.css = 'icon-fugueov-keyboard-minus no-repeat-bk';
                break;
            case 'spf_reevaluat':
                metadata.css = 'icon-fugueov-keyboard-arrow no-repeat-bk';
                break;
            case 'spf_validat_ol':
                metadata.css = 'icon-fugue-credit-card-green no-repeat-bk';
                break;
            case 'spf_expirat_fara_ol':
                metadata.css = 'icon-fugue-ui-toolbar-bookmark no-repeat-bk';
                break;
            case 'spf_contract_suspendat':
                metadata.css = 'icon-fugueov-folder-pencil no-repeat-bk';
                break;
            case 'spf_contract_reziliat':
                metadata.css = 'icon-fugueov-folder-minus no-repeat-bk';
                break;
        }
        metadata.attr = 'ext:qtip="' + data + '" ';
        return  String.format('<img class="padding-img" src="{0}"/>', Ext.BLANK_IMAGE_URL) + data;
    },
    status_sl: function(data, metadata, record, rowIndex, columnIndex, store) {

        if (data == null) {
            return "";
        }

        var status_cod = record.data.status_cod;

        switch (status_cod) {
            case 'sl_in_lucru':
                metadata.css = 'icon-fugue-node-insert-previous no-repeat-bk';
                break;
            case 'sl_anulat':
                metadata.css = 'icon-fugue-shield no-repeat-bk';
                break;
            case 'sl_spre_aprobare':
                metadata.css = 'icon-fugueov-paint-brush-plus no-repeat-bk';
                break;
            case 'sl_aprobat':
                metadata.css = 'icon-fugue-tick-circle no-repeat-bk';
                break;
            case 'sl_respins':
                metadata.css = 'icon-fugue-minus-circle no-repeat-bk';
                break;
            case 'sl_anulat_extra_caiet':
                metadata.css = 'icon-fugue-exclamation-shield no-repeat-bk';
                break;
            case 'sl_validat':
                metadata.css = 'icon-fugueov-folder-pencil no-repeat-bk';
                break;
        }
        metadata.attr = 'ext:qtip="' + data + '" ';
        return  String.format('<img class="padding-img" src="{0}"/>', Ext.BLANK_IMAGE_URL) + data;
    },

    motivRespingere: function(data, metadata, record, rowIndex, columnIndex, store) {
        if (data) {
            metadata.css = 'icon-fugue-exclamation no-repeat-bk';
            metadata.attr = 'ext:qtip="' + data + '" ';
            return  String.format('<img class="padding-img" src="{0}"/>', Ext.BLANK_IMAGE_URL) + data;
        }
        return data;
    }

}); 