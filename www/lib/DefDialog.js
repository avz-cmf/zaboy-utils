define(
    [
        'dojo/_base/declare',
        'dijit/form/Form', 'dijit/_WidgetsInTemplateMixin',
        'dojox/form/manager/_Mixin', 'dojox/form/manager/_NodeMixin', 'dojox/form/manager/_FormMixin', 'dojox/form/manager/_DisplayMixin',
        'dijit/form/ValidationTextBox'
    ],
    function (
        declare,
        Form, WidgetsInTemplateMixin,
        FormMgrMixin, FormMgrNodeMixin, FormMgrFormMixin, FormMgrDisplayMixin,
        ValidationTextBox
    ) {
        return declare([Form, WidgetsInTemplateMixin, FormMgrMixin, FormMgrNodeMixin, FormMgrFormMixin, FormMgrDisplayMixin], {
            templateString:null,
            resources:null,
            dialog:null,
            constructor:function (options) {
                declare.safeMixin(this, options);
                this.inherited(arguments)
            },

            onSubmit:function () {
                this.inherited(arguments);
                this.validate();
                if (this.isValid()) {
                    alert(this.resources.submitYourForm);
                }
                return false;
            }
        });
    }
);
