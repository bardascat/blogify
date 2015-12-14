<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/1">

<?php
$ExtFolder = 'assets/admin/ext-3.4.0';
?>
<base href="<? echo $this->config->config['base_url'] ?>"/>
<title>Helpie Login</title>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="Content-Language" content="Romanian"/>
<meta name="Author" content="Webmaster Office"/>
<meta name="Category" content="Services"/>
<meta name="Distribution" content="Global"/>
<meta name="Doc-class" content="Living Document"/>
<meta name="Document-rights" content="Copyrighted Work"/>
<meta name="Language" content="ro"/>
<meta name="MSSmartTagsPreventParsing" content="true"/>
<meta name="Rating" content="General"/>
<meta name="Resource-Type" content="document"/>
<meta name="Revisit-after" content="5 days"/>
<meta name="Robots" content="all"/>
<meta name="Subject" content="Bine ai venit!"/>
<meta http-equiv="Cache-Control" content="no-cache"/>

<script language="JavaScript" type="text/JavaScript" src="assets/frontend/jquery.1.10.min.js"></script>

<link rel="shortcut icon" href="<? echo $this->config->config['base_url'] ?>/favicon.ico" type="image/x-icon">

<link rel="stylesheet" type="text/css" href="<?= $ExtFolder ?>/resources/css/ext-all.css">
<link rel="stylesheet" type="text/css" href="<?= $ExtFolder ?>/resources/css/ext-all-notheme.css">
<link rel="stylesheet" type="text/css" href="<?= $ExtFolder ?>/resources/css/xtheme-gray.css">

<script http-equiv="content-script-type" content="text/javascript" src="<?= $ExtFolder ?>/adapter/ext/ext-base.js"></script>
<script http-equiv="content-script-type" content="text/javascript" src="<?= $ExtFolder ?>/ext-all.js"></script>
<script http-equiv="content-script-type" content="text/javascript" src="<?= $ExtFolder ?>/src/locale/ext-lang-ro.js"></script>

<!-- LOAD ALL EXTENSIONS -->
<script type="text/javascript" src="provider/extensions/<?= time() ?>.js"></script>
<link rel="stylesheet" type="text/css" href="provider/componentCss/<?= time() ?>.css"/>

<style type="text/css">
	div.nojs {
		margin: 100px auto;
		width: 400px;
		padding: 40px;
		background-color: rgb(255, 255, 255);
		border: solid 3px red;
	}

	body {
		/*	background: #7F99BE;*/
		background: #fff;
	}

	#header {
		border-bottom: 1px solid #666;
		background: #1E4176;
		padding: 5px;
		color: #fff;
		font-size: 20px;
		font-weight: bold;
		font-family: 'Lucida Grande', Arial, Sans;
	}

	#login_user .x-form-field, #login_password .x-form-field {
		font-size: 20px;
	}

	#remember_me .x-form-cb-label {
		font-size: 11px;
		color: #4d4d4d;
	}

	#login_button .x-btn-inner {
		font-size: 12px;
		font-weight: bold;
		color: #6666A2;
	}

</style>
<script>
function resizeToMax(id) {
	myImage = new Image()
	var img = document.getElementById(id);
	myImage.src = img.src;
	if (myImage.width / document.body.clientWidth > myImage.height / document.body.clientHeight) {
		img.style.width = "100%";
	} else {
		img.style.height = "100%";
	}
}


Ext.ns('APP');
APP = {
	security_question: []
};

Ext.onReady(function () {

	var formLogin = new Ext.FormPanel({
		labelWidth: 80,
		bodyStyle: "background:url('assets/admin/resources/img/logo_small.png') 4% 35% no-repeat; ",
		frame: true,
		id: 'frmLogin',
		title: 'Helpie Admin !',
		height: 482,
		width: 425,
		defaultType: 'textfield',
		labelAlign: 'top',
		layout: 'absolute',
		keys: [
			{
				key: [10, 13],
				fn: function () {
					fnLogin();
				}
			}
		],
		items: [
			{
				xtype: 'label',
				style: 'color: #333; font-weight: bold; font-size: 12px',
				text: 'Email:',

				x: 210,
				y: 20
			},
			{
				name: 'email',
				x: 210,
				allowBlank: false,
				y: 40,
				height: 25,
				width: 200
			},
			{
				xtype: 'label',
				style: 'color: #333; font-weight: bold; font-size: 12px',
				text: 'Parola:',
				x: 210,
				y: 70
			},
			{
				name: 'password',
				inputType: 'password',
				height: 25,
				allowBlank: false,
				width: 200,
				x: 210,
				y: 90
			},
			{
				xtype: 'box',
				name: "validare_text",
				style: 'font-size: 11px; text-decoration:none; font-family: sans-serif, helvetica, "aria black  " ; width: 280px;  padding-top :10px;     color:#e70;   ',
				x: 210,
				y: 110
			},
			{
				name: 'kickuser',
				xtype: 'hidden',
				value: 0
			}
		],
		buttons: [
			{
				xtype: 'button',
				text: "Login",
				iconCls: 'icon-fugue-key',
				name: "doFormSubmit",
				handler: fnLogin
			},
			{
				xtype: 'button',
				text: "DA",
				hidden: true,
				name: "doForceFormSubmit",
				handler: doForceSubmit
			},
			{
				xtype: 'button',
				text: "NU",
				hidden: true,
				name: "doForceCancel",
				handler: cancelForce
			},
			{
                                hidden:true,
				xtype: 'button',
				text: "Am uitat parola",
				iconCls: 'icon-fugue-information',
				name: "doPassReset",
				handler: fnResetLogin
			}
		]
	});

	function fnLogin() {

		if (!formLogin.getForm().isValid()) {
			return false;
		}

		Ext.getCmp('frmLogin').on({
			beforeaction: function () {
				if (formLogin.getForm().isValid()) {
					Ext.getCmp('winLogin2').body.mask();
					Ext.getCmp('sbWinLogin').showBusy();
				}
			}
		});

		Ext.Ajax.request({
			url: 'admin/sessions/xlogin',
			params: {
				data: Ext.encode(formLogin.getForm().getValues())
			},
			success: function (response) {
				var obj = Ext.util.JSON.decode(response.responseText);

				if (obj.error === true) {
					if (obj.type === 'multiplesession') {
						formLogin.find("name", "validare_text")[0].getEl().update(obj.description);
						formLogin.buttons[0].hide();
						formLogin.buttons[1].show();
						formLogin.buttons[2].show();
					} else {
						formLogin.find("name", "validare_text")[0].getEl().update(obj.description);
					}
				} else {
					formLogin.find("name", "validare_text")[0].getEl().update('Succes, redirectionare...');
					window.location = jQuery('base').attr("href") + 'admin/main';
				}

			}
		});
	}

	function doForceSubmit() {
		formLogin.getForm().findField("kickuser").setValue(1);
		fnLogin();
	}

	function cancelForce() {
		formLogin.getForm().findField("kickuser").setValue(0);
		formLogin.buttons[0].show();
		formLogin.buttons[1].hide();
		formLogin.buttons[2].hide();
		formLogin.find("name", "validare_text")[0].getEl().update("");
	}

	function onCapthaChange() {
		var captchaURL = "sessions/getCaptcha/";
		var curr = Ext.get('activateCodeImg');
		curr.slideOut('b', {
			callback: function () {
				Ext.get('activateCodeImg').dom.src = captchaURL + new Date().getTime();
				curr.slideIn('t');
			}
		}, this);
	}

	function fnResetLogin() {

		var boxCaptcha = new Ext.BoxComponent({
			columnWidth: .35,
			autoEl: {
				tag: 'img',
				id: 'activateCodeImg',
				title: 'Click to refresh code',
				src: "sessions/getCaptcha/" + new Date().getTime()
			},
			listeners: {
				'click': function () {
					//alert('test');
				}
			}
		});

		boxCaptcha.on('render', function () {
			var curr = Ext.get('activateCodeImg');
			curr.on('click', onCapthaChange, this);
		}, this);

		var winReset = new Ext.Window({
			modal: true,
			width: 500,
			height: 300,
			resizable: false,
			closable: true,
			items: [
				{
					xtype: "form",
					layout: "form",
					height: 650,
					labelWidth: 170,
					frame: true,
					monitorValid: true,
					bodyStyle: {
						padding: "5px"
					},
					defaults: {
						xtype: "textfield",
						allowBlank: false
					},
					title: "Resetare parola",
					items: [
						{
							name: 'email',
							anchor: "95%",
							fieldLabel: 'Email',
							minLength: 5
						},
						{
							name: 'security_question_1',
							readOnly: true,
							anchor: "95%",
							fieldLabel: 'Intrebare',
							value: APP.security_question[0]
						},
						{
							name: 'security_answer_1',
							fieldLabel: 'Raspuns securitate',
							minLenght: 5,
							anchor: "95%",
							maxLenght: 255,
							vtype: 'cleanTxt'
						},
						{
							name: 'security_question_2',
							readOnly: true,
							anchor: "95%",
							value: APP.security_question[1],
							fieldLabel: 'Intrebare'
						},
						{
							name: 'security_answer_2',
							fieldLabel: 'Raspuns securitate',
							minLenght: 5,
							anchor: "95%",
							maxLenght: 255,
							vtype: 'cleanTxt'
						},
						{
							name: 'code',
							fieldLabel: 'Completati codul de mai jos',
							minLenght: 5,
							anchor: "95%",
							maxLenght: 255,
							vtype: 'cleanTxt'
						},
						boxCaptcha
					]
				}
			],
			buttons: [
				{
					text: 'Resetare',
					iconCls: 'icon-fugue-key',
					formBind: true,
					handler: function () {
						var oFpAdd = winReset.find("xtype", "form")[0];
						oFpAdd.getForm().submit({
							url: "sessions/resetPasswd",
							success: function (form, response) {
							},
							failure: function (form, response) {
								var obj = Ext.decode(response.response.responseText);
								if (obj.description) {
									Ext.MessageBox.alert('Status', obj.description);
								}
							}
						});
					}
				},
				{
					text: 'Renunta',
					iconCls: 'icon-fugue-slash',
					handler: function () {
						winReset.close();
					}
				}
			]
		});
		winReset.show();
	}

	// 01. Window Register
	var winRegister = new Ext.Window({
		id: 'winLogin2',
		layout: 'fit',
		width: 450,
		height: 280,
		resizable: false,
		closable: false,
		listeners: {
			show: function (w) {
				var oPosition = w.getPosition();
				//w.setPosition(oPosition[0] + 200, oPosition[1]);
			}
		},
		items: [formLogin],
		bbar: new Ext.ux.StatusBar({
			id: 'sbWinLogin',
			iconCls: 'status-bar-login'
		})
	});

	winRegister.show();
});
</script>
<body>
<img id="image" src="resources/img/bk.jpg" onload="resizeToMax(this.id)">

<noscript>
	<div class="nojs">
		<span style="color: red; font-weight: bold; font-size: 24px;">EROARE:</span> <br/>
		Pentru a accesa aceasta aplicatie trebuie sa aveti <b>JAVASCRIPT</b> activat!
		<br/><br/>
		<?php
		$this->load->helper('url');
		?>
		Informatii client:<br/>
		Ip: <b><?= $this->input->ip_address(); ?></b><br/>
		Agent: <b><?= $this->input->user_agent(); ?></b><br/>
		Url: <b><?= current_url(); ?></b><br/>
	</div>
</noscript>
</body>
