/**
 * Creates a menu that supports tooltip specs for it's items. Just add "tooltip: {text: 'txt', title: 'ssss'}" to
 * the menu item config, "title" value is optional.
 * @class Ext.ux.TooltipMenu
 * @extends Ext.menu.Menu
 */
Ext.ux.TooltipMenu = Ext.extend(Ext.menu.Menu, {
	afterRender: function() {
		Ext.ux.TooltipMenu.superclass.afterRender.apply(this, arguments);

		var menu = this;
		this.tip = new Ext.ToolTip({
			target: this.getEl().getAttribute('id'),
			renderTo: document.body,
			delegate: '.x-menu-item',
			title: 'dummy title',
			listeners: {
				beforeshow: function updateTip(tip) {
					var mi = menu.findById(tip.triggerElement.id);
					if(!mi || !mi.initialConfig.tooltip || !mi.initialConfig.tooltip.text) {
						return false;
					}
					var title = typeof(mi.initialConfig.tooltip.title) == 'undefined' ? '' : mi.initialConfig.tooltip.title;
					tip.header.dom.firstChild.innerHTML = title;
					tip.body.dom.innerHTML = mi.initialConfig.tooltip.text;
				}
			}
		});
	}
});