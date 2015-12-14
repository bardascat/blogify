/**
*  Modified version:
*  Now with skinning suport!
*/
/*global Ext, Easy, window, $  */
Ext.ux.Toast = function() {
    var sysMsgWin;
    
    function insertElement(t, s) {
        return ['<div class="x-toast-wrapper">', 
                '<div class="x-toast-title ', 
                t.iconCls, 
                '"><b>', 
                t.title, 
                '</b>', 
                t.date, 
                '</div><div class="x-toast-content">', 
                s, 
                '</div></div>'].join('');
    }
    
    function insertTopElement(t, s) {
        return ['<div class="x-top-toast-wrapper ">', 
                '<table style="widht:100%"><tr><td class="x-top-toast-icon x-wite-space-normal" align="center" style="width:18px;">',
                '<div unselectable="on" title="', t.title, t.date,'" class="x-top-toast-title ', 
                t.iconCls, 
                '" style="width:18px;">&nbsp;</div>', 
                '</td><td style="" class="x-top-toast-content x-wite-space-normal">',
                '<div unselectable="on" style="" class="x-wite-space-normal x-top-toast-innercontent">',
                '<b>',
                t.time,
                ' </b>',
                s, 
                '</div>',
                '</td></tr></table></div>'].join('');
    }
    
    function insertTopTextElement(t, s) {
        return [t.title, t.date,
                '<br />',
                s,
                '<hr />',
                '',
                '<br />'].join('');
    }
    
    return {
        msg: function(title, format) {
            
            var bgColor = "FF8F6E";
            if (title.title == 'Informatie') {
                bgColor = "A4CAEF";
            }
            if (title.title == 'Avertisment') {
                bgColor = "EFC24A";
            }
            
            var s = String.format.apply(String, Array.prototype.slice.call(arguments, 1));
            var dtMsg = new Date();
            //title.title = title.title;
            title.date = ' [' + dtMsg.format('Y-m-d G:i:s') + ']';
            title.time= dtMsg.format('G:i');
            
            $('#x-toast-msg-content-topbar').prepend(insertTopElement(title, s));
            Ext.get('x-toast-msg-content-topbar').first().sequenceFx().slideIn('t', {
                duration: 0.2
            }).highlight(bgColor, {
                attr: 'background-color',
                duration: 1
            });
            
            var oOldTip = Ext.getCmp('x-toast-topbar-qtip-element');
            if(oOldTip){
                //console.log('Distrugem: ',oOldTip);
                // destroy old tip
                oOldTip.destroy();
                //console.log('Distrus: ',Ext.getCmp('x-toast-topbar-qtip-element'));
                
            }
            //console.info('Log: ',oItems, sToolTip);
            // adds the new item in log, and return last 4 items
            var oItems = APP.sysLog.add({
                title: title,
                s:s
            }, 4);
            
            var sToolTip = '';
            Ext.each(oItems, function(item){
                sToolTip += insertElement(item.title, item.s);
            });
            sToolTip += '...';
            
            //console.info('Log: ',oItems, sToolTip);
            var oTip = new Ext.ToolTip({
                target: 'x-toast-msg-content-topbar-container',
                id: 'x-toast-topbar-qtip-element',// unique tip id
                anchor: 'top',
                //anchorOffset: 160,
                width: 340,
                floating: true,
                padding:'1px 0',
                //frame: true,
                html: sToolTip
            });
            
            
        }
    };
    
}();
