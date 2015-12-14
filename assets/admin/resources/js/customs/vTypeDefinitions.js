/**
 * Contains custom vType definitions
 *
 * @package PortalGMS
 * @subpackage JSCOMPONENTS
 *
 * @author    Dan Ungureanu
 * @copyright (c) 2009, Distrigaz Sud
 * @date      03-Apr-2009
 * @version   1.0
 */
/*global Ext */

Ext.apply(Ext.form.VTypes, {
    MaxHours: function(v){
        return (/^1?[0-9]$|^[1-2]0$|^[2][1-4]$/).test(v);
    },
    MaxHoursMask: /^1?[0-9]$|^[1-2]0$|^[2][1-4]$/,
    MaxHoursText: 'Valoarea trebuie sa fie intre 0 si 24 ore!',
    
    UnsignedFloat: function(v){
        //return (/^\d*\.?\d*$/).test(v);
        return (/^\d{0,9}(\.\d{1,6})?$/).test(v);
    },
    UnsignedFloatMask: /^\d*\.?\d*$/,
    UnsignedFloatText: 'Valoarea trebuie sa fie un numar intreg sau zecimal pozitiv!<br />Separatorul zecimal este punct(.)!<br /> Ex: xxxxxxxxx.yyyyyy',


    UnsignedInt: function(v) {
        return (/^\d*$/).test(v);
    },
    UnsignedIntMask: /^\d*$/,
    UnsignedIntText: 'Valoarea trebuie sa fie un numar intreg pozitiv!',


    PasswordValid: function(val, field){
        if (val.length < 6) {
            return false;
        }
        return true;
    },
    PasswordValidMask: /^[\w\,\.\?\!\(\)\{\}\-\=\+\;\:\[\]\*]*$/,
    PasswordValidText: 'Parola trebuie sa contina minim 6 caractere.',
    //<br>Trebuie sa contina litere mici, majuscule, cifre si caractere speciale : a-zA-Z0-9,.?!(){}-=+;:[]*<br> Nu trebuie sa contina numele de utilizator sau parti din nume!',
    
    Password: function(val, field){
        if (field.initialPassField) {
            var pwd = Ext.getCmp(field.initialPassField);
            return (val == pwd.getValue());
        }
        return true;
    },
    PasswordText: 'Parolele nu coincid!',
    
    Phone: function(v){
        return (/^[0][0-9]{9}/).test(v);
    },
    PhoneText: 'Nr. introdus nu este corect!Trebuie să fie format din 10 cifre şi să înceapă cu 0.',
    
    CNP: function(v){
        return (/([1-8]{1})([0-9]{1}[0-9]{1})(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])([0-9]{6})/).test(v);
    },
    CNPText: 'Trebuie sa fie CNP valid format din 13 cifre!',
    
    DateRange: function(val, field){
        var date = field.parseDate(val);
        
        if (!date) {
            return;
        }
        
        if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
            var start = Ext.getCmp(field.startDateField);
            start.setMaxValue(date);
            this.dateRangeMax = date;
            start.validate();
            
        }
        else 
            if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
                var end = Ext.getCmp(field.endDateField);
                end.setMinValue(date);
                this.dateRangeMin = date;
                end.validate();
                
            }
        return true;
    },
    
    DateRangeText: 'Datele nu sunt corecte!'    ,


    cleanTxt : function(v) {
        return (/^[\w\s\@\,\.\?\!\(\)\{\}\-\=\+\%\;\:\'\[\]\$\*]*$/).test(v);
    }  ,
    cleanTxtText :"Caractere acceptate : a-zA-Z0-9 @,.?!(){}-=+%;:'[]*$" ,
    cleanTxtMask : /^[\w\s\@\,\.\?\!\(\)\{\}\-\=\+\%\;\:\'\[\]\$\*]*$/
    
});
